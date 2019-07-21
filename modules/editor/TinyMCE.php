<?php
namespace Enlighter\editor;

use Enlighter\skltn\ResourceManager;
use Enlighter\skltn\CssBuilder;

class TinyMCE{
    
    // stores the plugin config
    private $_config;

    // cache manager
    private $_cacheFilename = 'tinymce.css';
    private $_cacheManager;

    // language manager (build-in)
    protected $_languageManager;

    // fonts
    protected $_fontManager;
    
    public function __construct($config, $cacheManager, $languageManager, $fontManager){
        // store local plugin config
        $this->_config = $config;

        // store cache manager
        $this->_cacheManager = $cacheManager;

        // language manager to fetch build-in languages
        $this->_languageManager = $languageManager;

        // store font manager
        $this->_fontManager = $fontManager;
    }

    // run integration
    public function integrate(){
        // css cached ? otherwise regenerate it
        if (!$this->_cacheManager->fileExists($this->_cacheFilename)){
            $this->generateCSS();
        }
        
        // filter priority
        $priority = 101;

        // primary buttons (edit, insert) of the Visual Editor integration
        add_filter('mce_buttons', array($this, 'addButtons1'), $priority, 1);

        // load tinyMCE styles
        add_filter('mce_css', array($this, 'loadEditorCSS'), $priority, 1);

        // load tinyMCE enlighter plugin
        add_filter('mce_external_plugins', array($this, 'loadPlugin'), $priority, 1);

        // add pre-formatted styles ?
        if ($this->_config['tinymce-formats']){
            // add filter to enable the custom style menu - low priority to avoid conflicts with other plugins which try to overwrite the settings
            add_filter('mce_buttons_2', array($this, 'addButtons2'), $priority);

            // add filter to add custom formats (TinyMCE 4; requires WordPress 3.9) - low priority to avoid conflicts with other plugins which try to overwrite the settings
            add_filter('tiny_mce_before_init', array($this, 'insertFormats4'), $priority);
        }
    }
    
    // insert "code insert dialog button"
    public function addButtons1($buttons){
        // Enlighter insert already available ?
        if (!in_array('EnlighterInsert', $buttons)){
            $buttons[] = 'EnlighterInsert';
            $buttons[] = 'EnlighterEdit';
        }
        return $buttons;
    }
    
    // insert styleselect menu into the $buttons array
    public function addButtons2($buttons){
        // style-select menu already enabled ?
        if (!in_array('styleselect', $buttons)){
            array_unshift($buttons, 'styleselect');
        }
        return $buttons;
    }
    
    // callback function to filter the MCE settings
    public function insertFormats4($tinyMceConfigData){
        // new style formats
        $styles = array();
        
        // style formats already defined ?
        if (isset($tinyMceConfigData['style_formats'])){
            $styles = json_decode($tinyMceConfigData['style_formats'], true);
        }

        // do not allow additional formatting within pre/code tags!
        $validChildTags = '-code[strong|em|del|a|table|sub|sup],-pre[strong|em|del|a|table|sub|sup]';

        // valid html tags
        if (isset($tinyMceConfigData['valid_children'])){
            $tinyMceConfigData['valid_children'] .= ',' . $validChildTags;
        }else{
            $tinyMceConfigData['valid_children'] = $validChildTags;
        }

        // get language list
        $languages = $this->_languageManager->getLanguages();
        
        // create new "Enlighter Codeblocks" item
        $blockstyles = array();
        
        // add all supported languages as Enlighter Style
        foreach ($languages as $slug => $name){
            // define new enlighter style formats
            $blockstyles[] = array(
                    'title' => ''.$name,
                    'block' => 'pre',
                    'classes' => 'EnlighterJSRAW',
                    'wrapper' => false,
                    'attributes' => array(
                        'data-enlighter-language' => $slug
                    )
            );
        }
        
        // add block styles
        $styles[] = array(
            'title' => __('Enlighter Codeblocks', 'enlighter'),
            'items' => $blockstyles
        );
        
        // inline highlighting enabled ?
        $inlinestyles = array();
        
        foreach ($languages as $slug => $name){
            // define new enlighter inline style formats
            $inlinestyles[] =    array(
                    'title' => ''.$name,
                    'inline' => 'code',
                    'classes' => 'EnlighterJSRAW',
                    'wrapper' => false,
                    'selector' => '',
                    'attributes' => array(
                        'data-enlighter-language' => $slug
                    )
            );
        }
        
        // add inline styles
        $styles[] = array(
                'title' => __('Enlighter Inline', 'enlighter'),
                'items' => $inlinestyles
        );
        
        // dont overwrite all settings
        $tinyMceConfigData['style_formats_merge'] = true;
        
        // apply modified style data
        $tinyMceConfigData['style_formats'] = json_encode($styles);
        
        // tab indentation mode enabled ?
        if ($this->_config['tinymce-tabindentation']){
            // remove tabfocus plugin
            $tinyMceConfigData['plugins'] = str_replace('tabfocus,', '', $tinyMceConfigData['plugins']);
        }

        return $tinyMceConfigData;
    }

    // generate the editor css
    public function generateCSS(){

        $builder = new CssBuilder();

        // load base styles
        $builder->addFile(ENLIGHTER_PLUGIN_PATH.'/resources/tinymce/enlighterjs.tinymce.min.css');

        // add editor styles
        $builder->add('code.EnlighterJSRAW, pre.EnlighterJSRAW', array(
            'font-family'       => $this->_fontManager->getFontByName($this->_config['tinymce-font']) . ' !important',
            'font-size'         => $this->_config['tinymce-fontsize'] . ' !important',
            'line-height'       => $this->_config['tinymce-lineheight'] . ' !important',
            'color'             => $this->_config['tinymce-color'] . ' !important',
            'background-color'  => $this->_config['tinymce-bgcolor'] . ' !important',
        ));

        // generate language titles
        foreach ($this->_languageManager->getLanguages() as $slug => $name){

            // default title
            $defaultTitle = 'Enlighter: ' . $name;

            // generate codeblock title name
            $title = apply_filters('enlighter_codeblock_title', $defaultTitle, $slug, $name);
            
            // generate css rule
            $builder->add('pre.EnlighterJSRAW[data-enlighter-language="' . $slug . '"]:before', array(
                'content' => '"' . addslashes($title) . '"'
            ));
        }

        // Automatic Editor width
        if ($this->_config['tinymce-autowidth']){
            $builder->add('.mceContentBody', array(
                'max-width' => 'none !important'
            ));
        }

        // store generated styles
        $this->_cacheManager->writeFile($this->_cacheFilename, $builder->render());
    }

    public function loadEditorCSS($mce_css){
        // add hash from last settings update to force a cache update
        $url = ResourceManager::getResourceUrl('cache/' . $this->_cacheFilename, true);

        // other styles loaded ?
        if (empty($mce_css)){
            return $url;

            // append custom TinyMCE styles to editor stylelist
        }else{
            return $mce_css . ','.$url;
        }
    }

    public function loadPlugin($mce_plugins){
        // TinyMCE plugin js
        $mce_plugins['enlighterjs'] = ResourceManager::getResourceUrl('tinymce/enlighterjs.tinymce.min.js', ENLIGHTER_VERSION);
        return $mce_plugins;
    }
}