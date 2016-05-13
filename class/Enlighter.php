<?php

/**
    Enlighter Class
    Version: 3.1
    Author: Andi Dittrich
    Author URI: http://andidittrich.de
    Plugin URI: http://enlighterjs.org
    License: MIT X11-License
    
    Copyright (c) 2013-2016, Andi Dittrich

    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class Enlighter{

    // shortcode handler instance
    private $_shortcodeHandler;
    
    // resource loader instamce
    private $_resourceLoader;
    
    // settings utility instance
    private $_settingsUtility;

    // cache manager instance
    private $_cacheManager;
    
    // theme loader/manager
    private $_themeManager;

    // get available languages
    public static function getAvailableLanguages(){
        return Enlighter\LanguageManager::getLanguages();
    }
    
    // get available themes
    public static function getAvailableThemes(){
        return self::getInstance()->_themeManager->getThemes();
    }

    // basic plugin initialization
    public function __construct(){
        // create new settings utility class
        $this->_settingsUtility = new Enlighter\SettingsUtil('enlighter-', \Enlighter\PluginConfig::getDefaults());

        // create new cache manager instance
        $this->_cacheManager = new Enlighter\CacheManager($this->_settingsUtility);

        // loader to fetch user themes
        $this->_themeManager = new Enlighter\ThemeManager();
    }

    // initialized on init
    public function _wp_init(){
        // fetch languages
        $languages = Enlighter\LanguageManager::getLanguages();

        // load language files
        if ($this->_settingsUtility->getOption('enableTranslation')){
            load_plugin_textdomain('enlighter', null, 'enlighter/lang/');
        }
        
        // create new resource loader
        $this->_resourceLoader = new Enlighter\ResourceLoader($this->_settingsUtility, $this->_cacheManager, $this->_themeManager, $languages);

        // enable EnlighterJS html attributes for Author's and Contributor's
        add_filter('wp_kses_allowed_html', array($this, 'ksesAllowHtmlCodeAttributes'), 100, 2);

        // frontend or dashboard area ?
        if (is_admin()){
            // add admin menu handler
            add_action('admin_menu', array($this, 'setupBackend'));

            // add plugin upgrade notification
            add_action('in_plugin_update_message-enlighter/Enlighter.php', array($this, 'showUpgradeNotification'), 10, 2);

            // force theme cache reload
            $this->_themeManager->forceReload();

            // editor
            $this->_resourceLoader->backendEditor();
        }else{

            // enable bb_press shortcode extension ?
            if ($this->_settingsUtility->getOption('bbpressShortcode')){
                Enlighter\BBPress::enableShortcodeFilter();
            }

            // initialize the shortcode handler
            switch ($this->_settingsUtility->getOption('shortcodeMode')){

                // legacy (WordPress based) shortcode handling ?
                case 'legacy':
                    $this->_shortcodeHandler = new Enlighter\LegacyShortcodeHandler($this->_settingsUtility, $languages);
                    break;

                // do nothing
                case 'disabled':
                    break;

                // default : Low Level Processor
                default:
                    $this->_shortcodeHandler =  new Enlighter\LowlLevelShortcodeProcessor($this->_settingsUtility, $languages);
                    break;
            }

            // frontend resources & extensions
            $this->setupFrontend();

            // change wpauto filter priority ?
            if ($this->_settingsUtility->getOption('wpAutoPFilterPriority')!='default'){
                remove_filter('the_content', 'wpautop');
                add_filter('the_content', 'wpautop' , 12);
            }
        }

        // trigger init hook
        do_action('enlighter_init', $this);
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
        // load frontend css+js resources
        $this->_resourceLoader->frontendEnlighter();

        // check frontend user privileges
        $canEdit = is_user_logged_in() && (current_user_can('edit_posts') || current_user_can('edit_pages'));
        if ($canEdit){
            $this->_resourceLoader->frontendEditor();
        }
    }
    
    public function setupBackend(){
        if (current_user_can('manage_options')){
            // add options page
            $optionsPage = add_menu_page(__('Enlighter - Customizable Syntax Highlighter', 'enlighter'), 'Enlighter', 'administrator', 'Enlighter', array($this, 'settingsPage'), 'dashicons-editor-code');

            // add links
            add_filter('plugin_action_links', array($this, 'addPluginPageSettingsLink'), 10, 2);
            add_filter('plugin_row_meta', array($this, 'addPluginMetaLinks'), 10, 2);

            // settings page resources
            add_filter('load-'.$optionsPage, array($this->_resourceLoader, 'backendSettings'));

            // call register settings function
            add_action('admin_init', array($this->_settingsUtility, 'registerSettings'));
            
            // contextual help
            $ch = new Enlighter\ContextualHelp($this->_settingsUtility);
            add_filter('load-'.$optionsPage, array($ch, 'contextualHelp'));
        }
    }

    // links to the plugin website & author's twitter channel ()
    public function addPluginMetaLinks($links, $file){
        // current plugin ?
        if ($file == 'enlighter/Enlighter.php'){
            $links[] = '<a target="_blank" href="https://twitter.com/andidittrich">'.__('News & Updates', 'enlighter').'</a>';
            $links[] = '<a target="_blank" href="http://enlighterjs.org">'.__('EnlighterJS Website', 'enlighter').'</a>';
        }
        
        return $links;
    }

    // links on the plugin page
    public function addPluginPageSettingsLink($links, $file){
        // current plugin ?
        if ($file == 'enlighter/Enlighter.php'){
            $links[] = '<a href="'.admin_url('admin.php?page=Enlighter').'">'.__('Settings', 'enlighter').'</a>';
        }

        return $links;
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

        // fetch the theme list
        $themeList = $this->_themeManager->getThemeList();

        // get webfont list
        $webfonts = \Enlighter\GoogleWebfontResources::getMonospaceFonts();
                
        // include admin page
        include(ENLIGHTER_PLUGIN_PATH.'/views/admin/SettingsPage.phtml');
    }

    // gets the current EnlighterJS version from js file
    public static function getEnlighterJSVersion(){
        $content = file_get_contents(ENLIGHTER_PLUGIN_PATH.'/resources/EnlighterJS.min.js');

        // extract version
        $r = preg_match('#^[\S\s]+ (\d.\d+.\d+)#U', $content, $matches);

        // valid result ?
        if ($r!==1){
            return 'NaN';
        }else{
            return $matches[1];
        }
    }

    // plugin upgrade notification
    public function showUpgradeNotification($currentPluginMetadata, $newPluginMetadata){
        // check "upgrade_notice"
        if (isset($newPluginMetadata->upgrade_notice) && strlen(trim($newPluginMetadata->upgrade_notice)) > 0){
            echo '<p style="background-color: #d54e21; padding: 10px; color: #f9f9f9; margin-top: 10px"><strong>Important Upgrade Notice:</strong> ';
            echo esc_html($newPluginMetadata->upgrade_notice), '</p>';
       }
    }

    public function _wp_plugin_activate(){

    }

    public function _wp_plugin_deactivate(){

    }

    public function _wp_plugin_upgrade($currentVersion){
        // invalidate cache on upgrade!
        $this->_cacheManager->clearCache();

        return true;
    }


//!WP::SKELETON

    // static entry/initialize singleton instance
    public static function run($pluginName){
        // check if singleton instance is available
        if (self::$__instance==null){
            // create new instance if not
            $i = self::$__instance = new self();

            // register plugin related hooks
            register_activation_hook($pluginName, array($i, '_wp_plugin_activate'));
            register_deactivation_hook($pluginName, array($i, '_wp_plugin_deactivate'));
            add_action('init', array($i, '_wp_init'));

            // fetch plugin version
            $version = get_option('enlighter-version', '0.0.0');

            // plugin upgraded ?
            if (version_compare(ENLIGHTER_VERSION, $version, '>')){
                // run upgrade hook
                if ($i->_wp_plugin_upgrade($version)){
                    // store new version
                    update_option('enlighter-version', ENLIGHTER_VERSION);
                }
            }
        }
    }

    // singleton instance
    private static $__instance;
    public static function getInstance(){
        return self::$__instance;
    }
//!!WP::SKELETON
}