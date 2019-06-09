<?php

namespace Enlighter;

class ResourceLoader{
        
    // js config generator
    private $_jsConfigGenerator;
    
    // available language keys
    private $_languageKeys;
    
    // stores the plugin config
    private $_config;
    
    // list of external themes
    private $_themeManager;

    // tinymce editor
    private $_tinymce;

    // update hash
    private $_uhash;

    // theme generator
    private $_themeGenerator;

    public function __construct($settingsUtil, $cacheManager, $themeManager, $languageKeys){
        // store local plugin config
        $this->_config = $settingsUtil->getOptions();

        // store language keys
        $this->_languageKeys = $languageKeys;

        // local theme manager instance (required for external themes)
        $this->_themeManager = $themeManager;

        // visual editor integration
        $this->_tinymce = new TinyMCE($settingsUtil, $cacheManager, $languageKeys);

        // get last update hash
        $this->_uhash = get_option('enlighter-settingsupdate-hash', '0A0B0C');

        // create new js config generator
        $this->_jsConfigGenerator = new ConfigGenerator($settingsUtil, $cacheManager);

        // create new theme generator instance
        $this->_themeGenerator = new ThemeGenerator($settingsUtil, $cacheManager);
    }

    // Load the Frontend Editor Resources
    public function frontendEditor(){
        // Inline Editor Configuration (Themes...)
        add_action('wp_head', array($this, 'appendInlineEditorConfig'));

        // apply editor modifications
        if ($this->_config['enableFrontendTinyMceIntegration']) {
            $this->_tinymce->integrate();
        }

        // load text editor ?
        if ($this->_config['enableQuicktagFrontendIntegration']){
            add_action('wp_enqueue_scripts', array($this, 'appendTextEditorJS'));
        }
    }

    // Load the Backend Editor Resources
    public function backendEditor(){
        // Inline Editor Configuration (Themes...)
        add_action('admin_print_scripts', array($this, 'appendInlineEditorConfig'));

        // apply editor modifications
        if ($this->_config['enableTinyMceIntegration']) {
            $this->_tinymce->integrate();
        }

        // load text editor ?
        if ($this->_config['enableQuicktagBackendIntegration']){
            add_action('admin_enqueue_scripts', array($this, 'appendTextEditorJS'));
        }
    }

    // Load the Backend About Page Resources
    public function backendAboutPage(){
        add_action('admin_enqueue_scripts', array($this, 'appendAboutPageResources'));
    }

    public function appendAboutPageResources(){
        // new UI styles
        $this->enqueueStyle('enlighter-about', 'admin/About.css');

        // settings init script
        $this->enqueueScript('enlighter-about', 'admin/About.js', array('jquery'));
    }

    // Load the Backend Settings Resources
    public function backendSettings(){
        add_action('admin_enqueue_scripts', array($this, 'appendAdminResources'));
    }

    public function appendAdminResources(){
        // colorpicker css
        $this->enqueueStyle('enlighter-jquery-colorpicker', 'extern/colorpicker/css/colorpicker.css');

        // new UI styles
        $this->enqueueStyle('enlighter-settings', 'admin/EnlighterSettings.css');

        // colorpicker js
        $this->enqueueScript('enlighter-jquery-colorpicker', 'extern/colorpicker/js/colorpicker.js', array('jquery'));

        // theme data
        $this->enqueueScript('enlighter-themes', 'admin/ThemeStyles.js');

        // settings init script
        $this->enqueueScript('enlighter-settings', 'admin/EnlighterSettings.js',
            array('jquery', 'jquery-color', 'enlighter-jquery-cookies', 'enlighter-jquery-colorpicker', 'enlighter-themes'));
    }

    // append the Enlighter Editor/Settings Config
    public function appendInlineEditorConfig(){
        $this->inlineScript($this->_jsConfigGenerator->getEditorPluginConfig());
    }

    // helper function to print inline javascript
    private function inlineScript($script){
        // apply inline filter - useful to remove inline scripts
        $script = apply_filters('enlighter_inline_javascript', $script);

        // remove leading/trailing whitespaces
        $script = trim($script);

        // output script
        if (strlen($script) > 0){
            echo '<script type="text/javascript">/* <![CDATA[ */', $script, ';/* ]]> */</script>';
        }
    }

    public function appendTextEditorJS(){
        // text editor plugin
        $this->enqueueScript('enlighter-texteditor', 'editor/TextEditor.js', array('jquery'));
    }

    // initialzize the frontend
    public function frontendEnlighter(){

        // frontend js + css
        add_action('wp_enqueue_scripts', array($this, 'appendEnlighterCSS'), 50);
        add_action('wp_enqueue_scripts', array($this, 'appendEnlighterJS'), 50);
            
        // display frontend config - backward compatible to v2.9
        if ($this->_config['jsType'] != 'external' && $this->_config['jsType'] != 'none'){

            // header or footer location ? set priority to 30 to print scripts after enqueued one (20)
            if ($this->_jsInFooter){
                add_action('wp_footer', array($this, 'appendInlineEnlighterConfig'), 30);
            }else{
                add_action('wp_head', array($this, 'appendInlineEnlighterConfig'), 30);
            }
        }

        // external config file - regenerate cache file if not existent
        if ($this->_config['jsType'] == 'external' && !$this->_jsConfigGenerator->isCached()){
            $this->_jsConfigGenerator->generate();
        }
    }

    // disable frontend scripts (in footer; resource optimization)
    public function disableFrontendScripts(){
        wp_dequeue_script('mootools-local');
        wp_dequeue_script('mootools-google-cdn');
        wp_dequeue_script('mootools-cloudfare-cdn');
        wp_dequeue_script('mootools-jsdelivr-cdn');
        wp_dequeue_script('enlighter-local');
        wp_dequeue_script('enlighter-config');
        wp_dequeue_script('enlighter-jetpack-infinitescroll');        
        remove_action('wp_footer', array($this, 'appendInlineEnlighterConfig'), 30);
    }

    // append javascript based config
    public function appendInlineEnlighterConfig(){
        $this->inlineScript($this->_jsConfigGenerator->getInitializationConfig());
    }
    
    // append css
    public function appendEnlighterCSS(){
        // only include css if enabled
        if ($this->_config['embedEnlighterCSS']){
            // include generated css ?
            if ($this->_config['defaultTheme']=='wpcustom'){
                $this->enqueueStyle('enlighter-wpcustom', 'cache/EnlighterJS.custom.css', array(), $this->_uhash);

            }else{
                // include standard css file ?
                $this->enqueueStyle('enlighter-local', 'EnlighterJS.min.css');
            }
        }
        
        // load user themes ?
        if ($this->_config['embedExternalThemes']) {

            // embed available external themes
            foreach ($this->_themeManager->getUserThemes() as $theme => $sources) {
                $this->enqueueStyle('enlighter-external-' . strtolower($theme), $sources[1], array(), $this->_uhash);
            }
        }

        // list of google webfonts to load
        $webfontList = array();

        // get all available webfonts
        $webfonts = GoogleWebfontResources::getMonospaceFonts();

        // load enabled fonts
        foreach ($webfonts as $name => $font){
            $fid = preg_replace('/[^A-Za-z0-9]/', '', $name);
            if ($this->_config['webfonts'.$fid]){
                $webfontList[] = $font;
            }
        }

        // load webfonts ?
        if (count($webfontList) > 0){
            $this->enqueueStyle('enlighter-webfonts', '//fonts.googleapis.com/css?family=' . implode('|', $webfontList));
        }
    }
    
    // append js
    public function appendEnlighterJS(){
        // include mootools from local source ?
        if ($this->_config['mootoolsSource'] == 'local'){
            // include local mootools
            $this->enqueueScript('mootools-local', self::$cdnLocations['mootools-local'], array());
        }
    
        // include mootools from google cdn ?
        if ($this->_config['mootoolsSource'] == 'google'){
            // include local mootools hosted by google's cdn
            $this->enqueueScript('mootools-google-cdn', self::$cdnLocations['mootools-google'], array(), null);
        }
        
        // include mootools from cloudfare cdn ?
        if ($this->_config['mootoolsSource'] == 'cdnjs'){
            // include local mootools hosted by cloudfares's cdn
            $this->enqueueScript('mootools-cloudfare-cdn', self::$cdnLocations['mootools-cdnjs'], array(), null);
        }

        // include mootools from jsdelivr cdn ?
        if ($this->_config['mootoolsSource'] == 'jsdelivr'){
            // include local mootools hosted by cloudfares's cdn
            $this->enqueueScript('mootools-jsdelivr-cdn', self::$cdnLocations['mootools-jsdelivr'], array(), null);
        }

        // dependencies
        $ejsDependencies = array();
    
        // only include EnlighterJS js if enabled
        if ($this->_config['embedEnlighterJS']){
            // include local css file
            $this->enqueueScript('enlighter-local', 'EnlighterJS.min.js');

            // Script required by other components
            $ejsDependencies = array('enlighter-local');
        }
        
        // only include EnlighterJS config if enabled
        if ($this->_config['jsType'] == 'external'){
            // include local css file
            $this->enqueueScript('enlighter-config', 'cache/EnlighterJS.init.js', $ejsDependencies, $this->_uhash);
        }

        // jetpack InfiniteScroll Extension enabled ?
        if ($this->_config['extJetpackInfiniteScroll']){
            // include local css file
            $this->enqueueScript('enlighter-jetpack-infinitescroll', 'plugin/JetpackInfiniteScroll.js', $ejsDependencies, $this->_uhash);
        }
    }
}