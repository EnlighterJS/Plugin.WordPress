<?php

class Enlighter 
    extends \Enlighter\skltn\Plugin{

    // content processor instance (gfm, shortcode)
    protected $_contentProcessor;
    
    // resource loader instamce
    protected $_resourceLoader;

    // cache manager instance
    protected $_cacheManager;
    
    // theme manager (build in + user themes)
    protected $_themeManager;

    // language manager (build-in)
    protected $_languageManager;

    // handle fonts
    protected $_fontManager;

    // theme generator
    protected $_themeGenerator;

    // theme customizer
    protected $_themeCustomizer;

    // basic plugin initialization
    public function __construct(){
        parent::__construct();

        // create new cache manager instance
        $this->_cacheManager = new Enlighter\skltn\CacheManager();

        // loader to fetch user themes
        $this->_themeManager = new Enlighter\ThemeManager();

        // handle language files
        $this->_languageManager = new Enlighter\LanguageManager();

        // handle fonts for theme customizer + tinymce editor
        $this->_fontManager = new Enlighter\FontManager();

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

        // load language files
        if ($this->_settingsManager->getOption('translation-enabled')){
            load_plugin_textdomain('enlighter', null, 'enlighter/lang/');
        }

        // initialize theme customizer (generates the enlighterjs.css file!)
        $this->_themeCustomizer = new Enlighter\customizer\ThemeCustomizer($this->_settingsManager, $this->_cacheManager);
        
        // create new resource loader
        $this->_resourceLoader = new Enlighter\ResourceLoader(
                $this->_settingsManager, 
                $this->_cacheManager, 
                $this->_languageManager, 
                $this->_themeManager,
                $this->_fontManager,
                $this->_themeCustomizer
        );

        // enable EnlighterJS html attributes for Author's and Contributor's
        add_filter('wp_kses_allowed_html', array('\Enlighter\KSES', 'allowHtmlCodeAttributes'), 100, 2);

        // initialize jetpack extension (frontend+backend)
        Enlighter\extensions\Jetpack::init(
            $this->_settingsManager->getOption('jetpack-gfm-code')
        );
        
        // frontend or dashboard area ?
        if (is_admin()){

            // editor
            $this->_resourceLoader->backendEditor();

            // theme customizer settings
            add_action('admin_init', array($this->_themeCustomizer, 'registerSettings'));

        }else{

            // initialize bb_press extension
            Enlighter\extensions\BBPress::init(
                $this->_settingsManager->getOption('bbpress-markdown'),
                $this->_settingsManager->getOption('bbpress-shortcode')
            );

            // initialize content processor (shortcode, gfm)
            $this->_contentProcessor = new Enlighter\filter\ContentProcessor(
                $this->_settingsManager,
                $this->_languageManager,
                $this->_themeManager
            );
            
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
                    'resources' => array($this->_resourceLoader, 'backendSettingsCustomizer'),
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
                // compatibility options
                array(
                    'pagetitle' => ENLIGHTER_PLUGIN_TITLE,
                    'title' => 'Compatibility',
                    'slug' => 'enlighter-compatibility',
                    'template' => 'compatibility/CompatibilityPage',
                    'resources' => array($this->_resourceLoader, 'backendSettings'),
                    'render' => array($this, 'settingsPage'),
                    'help' => array('Enlighter\Admin\ContextualHelp', 'settings')
                ),
                // extensions
                array(
                    'pagetitle' => ENLIGHTER_PLUGIN_TITLE,
                    'title' => 'Extensions',
                    'slug' => 'enlighter-extensions',
                    'template' => 'extensions/ExtensionsPage',
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

    public function setupFrontend(){
        // load frontend css+js resources - highlighting engine
        $this->_resourceLoader->frontendEnlighter($this->_contentProcessor);

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

        // force theme cache reload
        $this->_themeManager->clearCache();
        
        // well...is there no action hook for updating settings in wp ?
        if (isset($_GET['settings-updated'])){
            // drop cached files
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
            'themes' => $this->_themeManager->getThemes(),
            'languages' => $this->_languageManager->getLanguages(),
            'webfonts' => array()
        );
    }

    // retrieve themes
    public function getThemes(){
        return $this->_themeManager->getThemes();
    }

    // retrieve languages
    public function getLanguages(){
        return $this->_languageManager->getLanguages();
    }

    public function _wp_plugin_upgrade($currentVersion){
        // upgrade from < 4.0 ? use v3.99 condition to ensure that beta versions are not altered!
        if (version_compare($currentVersion, '3.99', '<')){
            // load upgrader
            require_once(ENLIGHTER_PLUGIN_PATH.'/modules/upgrade/Upgrade_to_v4.php');

            // create upgrader instance
            $upgrader = new Enlighter\upgrade\Upgrade_to_v4();

            // run
            $upgrader->run($currentVersion, ENLIGHTER_VERSION);
        }
        
        // invalidate cache on upgrade!
        $this->_cacheManager->clearCache(true);

        return true;
    }
}