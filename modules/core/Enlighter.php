<?php

class Enlighter 
    extends \Enlighter\skltn\Plugin{

    // shortcode handler instance
    protected $_shortcodeHandler;

    // content processor instance (gfm, shortcode)
    protected $_contentProcessor;
    
    // resource loader instamce
    protected $_resourceLoader;

    // cache manager instance
    protected $_cacheManager;
    
    // theme loader/manager
    protected $_themeManager;

    // basic plugin initialization
    public function __construct(){
        parent::__construct();

        // create new cache manager instance
        $this->_cacheManager = new Enlighter\skltn\CacheManager();

        // loader to fetch user themes
        $this->_themeManager = new Enlighter\ThemeManager();

        // use custom cache path/url ?
        if ($this->_settingsManager->getOption('cache-custom')){
            $this->_cacheManager->setCacheLocation($this->_settingsManager->getOption('cache-path'), $this->_settingsManager->getOption('cache-url'));
        }

        // set default settings page
        $this->_pluginMetaSettingsPage = 'appearance';
        $this->_pluginMetaAboutPage = 'about';

        // plugin meta links
        $this->_pluginMetaLinks = array(
            'https://twitter.com/andidittrich' => __('News & Updates', 'enligther'),
            'https://github.com/EnlighterJS/Plugin.WordPress/issues' => __('Report Bugs', 'enligther'),
            'https://enlighterjs.org' => __('EnlighterJS Website', 'enligther')
        );
    }

    // initialized on init
    public function _wp_init(){
        // execute extended functions
        parent::_wp_init();
        
        // run startup filter to disable Enlighter by third Party Plugins
        $startup = apply_filters('enlighter_startup', true);
        if ($startup === false){
            return;
        }

        // fetch languages
        $languages = Enlighter\LanguageManager::getLanguages();

        // load language files
        if ($this->_settingsManager->getOption('translation-enabled')){
            load_plugin_textdomain('enlighter', null, 'enlighter/lang/');
        }
        
        // create new resource loader
        $this->_resourceLoader = new Enlighter\ResourceLoader($this->_settingsManager, $this->_cacheManager, $this->_themeManager, $languages);

        // enable EnlighterJS html attributes for Author's and Contributor's
        add_filter('wp_kses_allowed_html', array($this, 'ksesAllowHtmlCodeAttributes'), 100, 2);

        // frontend or dashboard area ?
        if (is_admin()){

            // force theme cache reload
            //$this->_themeManager->forceReload();

            // editor
            //$this->_resourceLoader->backendEditor();

        }else{

            /*
            // enable bb_press shortcode extension ?
            if ($this->_settingsUtility->getOption('bbpressShortcode')){
                Enlighter\BBPress::enableShortcodeFilter();
            }

            // enable bb_press markdown extension ?
            if ($this->_settingsUtility->getOption('bbpressMarkdown')){
                Enlighter\BBPress::enableMarkdownFilter();
            }

            // disable the backtick code filter ?
            if ($this->_settingsUtility->getOption('bbpressShortcode') || $this->_settingsUtility->getOption('bbpressMarkdown')){
                Enlighter\BBPress::disableCodeFilter();
            }

            // initialize the classic shortcode handler ?
            if ($this->_settingsUtility->getOption('shortcodeMode') == 'legacy'){
                $this->_shortcodeHandler = new Enlighter\LegacyShortcodeHandler($this->_settingsUtility, $languages);
            }

            // initialize content processor (shortcode, gfm)
            $this->_contentProcessor = new Enlighter\ContentProcessor($this->_settingsUtility, $languages);
            */
            
            // frontend resources & extensions
            $this->setupFrontend();


            
        }

        // trigger init hook
        do_action('enlighter_init', $this);
    }

    // backend menu structure
    protected function getBackendMenu(){
        // menu group + first entry
        return array(
            'pagetitle' => ENLIGHTER_PLUGIN_TITLE,
            'title' => 'Enlighter',
            'title2' => 'Appearance',
            'slug' => 'enlighter-appearance',
            'icon' => 'dashicons-editor-code',
            'template' => 'appearance/AppearancePage',
            'resources' => array($this->_resourceLoader, 'backendSettings'),
            'render' => array($this, 'settingsPage'),
            'help' => array('Enlighter\Admin\ContextualHelp', 'settings'),
            'items' => array(
                // theme Customizer
                array(
                    'pagetitle' => ENLIGHTER_PLUGIN_TITLE,
                    'title' => 'Theme Customizer',
                    'slug' => 'enlighter-customizer',
                    'template' => 'customizer/CustomizerPage',
                    'resources' => array($this->_resourceLoader, 'backendSettings'),
                    'render' => array($this, 'settingsPage'),
                    'help' => array('Enlighter\Admin\ContextualHelp', 'settings')
                ),
                // editing options
                array(
                    'pagetitle' => ENLIGHTER_PLUGIN_TITLE,
                    'title' => 'Editing',
                    'slug' => 'enlighter-editing',
                    'template' => 'editing/EditingPage',
                    'resources' => array($this->_resourceLoader, 'backendSettings'),
                    'render' => array($this, 'settingsPage'),
                    'help' => array('Enlighter\Admin\ContextualHelp', 'settings')
                ),
                // advanced options
                array(
                    'pagetitle' => ENLIGHTER_PLUGIN_TITLE,
                    'title' => 'Options',
                    'slug' => 'enlighter-options',
                    'template' => 'options/OptionsPage',
                    'resources' => array($this->_resourceLoader, 'backendSettings'),
                    'render' => array($this, 'settingsPage'),
                    'help' => array('Enlighter\Admin\ContextualHelp', 'settings')
                ),
                // about
                array(
                    'pagetitle' => ENLIGHTER_PLUGIN_TITLE,
                    'title' => 'About',
                    'slug' => 'enlighter-about',
                    'template' => 'about/AboutPage',
                    'resources' => array($this->_resourceLoader, 'backendSettings')
                )
            )
        );
    }

    // enable EnlighterJS html attributes for Authors and Contributors
    public function ksesAllowHtmlCodeAttributes($data, $context){
        // only apply filter on post-context
        if ($context === 'post'){

            // list of all available enlighterjs attributes
            $allowedAttributes = array(
                'data-enlighter-language' => true,
                'data-enlighter-theme' => true,
                'data-enlighter-group' => true,
                'data-enlighter-title' => true,
                'data-enlighter-linenumbers' => true,
                'data-enlighter-highlight' => true,
                'data-enlighter-lineoffset' => true
            );

            // apply to pre and code tags
            if (isset($data['pre'])){
                $data['pre'] = array_merge($data['pre'], $allowedAttributes);
            }
            if (isset($data['code'])){
                $data['code'] = array_merge($data['code'], $allowedAttributes);
            }
        }

        return $data;
    }

    public function setupFrontend(){
        // load frontend css+js resources - highlighting engine
        $this->_resourceLoader->frontendEnlighter();

        /*
        // frontend resource optimization ?
        if ($this->_settingsUtility->getOption('dynamicResourceInvocation')){
            // php 5.3 compatibility
            $T = $this;

            // deregister footer scripts
            add_action('wp_footer', function() use ($T){
                // enlighter codeblocks active within current page ?
                $enlighterCodeFound = $T->_contentProcessor->hasContent() || ($T->_shortcodeHandler !== null && $T->_shortcodeHandler->hasContent());

                // disable
                if ($enlighterCodeFound === false){
                    $T->_resourceLoader->disableFrontendScripts();
                }
            }, 1);
        }
        */

        // check frontend user privileges
        $canEdit = is_user_logged_in() && (current_user_can('edit_posts') || current_user_can('edit_pages'));

        // apply filter
        $canEdit = apply_filters('enlighter_frontend_editing', $canEdit);

        // editor enabled ?
        if ($canEdit === true){
            $this->_resourceLoader->frontendEditor();
        }
    }

    // options page
    public function settingsPage(){
        // well...is there no action hook for updating settings in wp ?
        if (isset($_GET['settings-updated'])){
            $this->_cacheManager->clearCache();
        }
        
        // permission fix request ?
        if (isset($_GET['cache-permission-fix'])){
            $this->_cacheManager->autosetPermissions();
        }

        // throw notifications
        $envCheck = new \Enlighter\EnvironmentCheck($this->_cacheManager);
        $envCheck->throwNotifications();

        return array(
            'themes' => $this->_themeManager->getThemeList(),
            'webfonts' => array()
        );
    }
 
    public function _wp_plugin_upgrade($currentVersion){
        // upgrade from < 4.0 ? use v3.99 condition to ensure that beta versions are not altered!
        if (version_compare($currentVersion, '3.99', '<')){
            // load upgrader
            require_once(ENLIGHTER_PLUGIN_PATH.'/modules/upgrade/Upgrade_to_4_0_0.php');

            // create upgrader instance
            $upgrader = new Enlighter\Upgrade\Upgrade_to_4_0_0();

            // run
            $upgrader->run($currentVersion, ENLIGHTER_VERSION);
        }
        
        // invalidate cache on upgrade!
        $this->_cacheManager->clearCache(true);

        return true;
    }
}