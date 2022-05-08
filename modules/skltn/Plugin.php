<?php
// ---------------------------------------------------------------------------------------------------------------
// -- WP-SKELETON AUTO GENERATED FILE - DO NOT EDIT !!!
// --
// -- Copyright (c) 2016-2020 Andi Dittrich
// -- https://github.com/AndiDittrich/WP-Skeleton
// --
// ---------------------------------------------------------------------------------------------------------------
// --
// -- This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
// -- If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
// --
// ---------------------------------------------------------------------------------------------------------------
 
// Plugin Base
namespace Enlighter\skltn;

class Plugin{

    // settings manager instance
    protected $_settingsManager;

    // settings view helper
    protected $_settingsUtility;

    // plugin activation redirect page
    protected $_activationRedirectPage = 'admin.php?page=enlighter-about';

    // additional plugin links
    protected $_pluginMetaSettingsPage = null;
    protected $_pluginMetaAboutPage = null;
    protected $_pluginMetaLinks = array();
    
    public function __construct(){
        // create new settings manager instance
        $this->_settingsManager = new SettingsManager();
    }

    // init hook executed with std priority
    public function _wp_init(){
        // frontend or admin area ?
        if (is_admin()){

            // plugin upgrade ? show about page
            if (get_option('enlighter-activation-redirect', '') === 'about-page'){
                // remove redirect flag - do it twice to avoid infinite redirect issues with broken caching plugins!
                $deleted = update_option('enlighter-activation-redirect', '') && delete_option('enlighter-activation-redirect');
                // ignore bulk actions - additional redirect check
                // current page has to be the plugin update dialog!
                if ($deleted && !isset($_GET['activate-multi'])){
                    // disable browser caching
                    nocache_headers();
                    // redirect to About Page
                    wp_redirect(admin_url($this->_activationRedirectPage), 307);
                    
                    exit;
                }
            }

            // initialize settings view helper
            $this->_settingsUtility = new SettingsViewHelper($this->_settingsManager);

            // add admin menu handler
            add_action('admin_menu', array($this, 'setupBackend'));
            
            // add plugin upgrade notification
            add_action('in_plugin_update_message-enlighter/Enlighter.php', array($this, 'showUpgradeAvailabilityNotification'), 10, 2);

            // plugin upgraded ?
            if (get_option('enlighter-upgrade', '-') === 'true'){
                // add admin message handler
                add_action('admin_notices', array($this, 'showUpgradeMessage'));
                add_action('network_admin_notices', array($this, 'showUpgradeMessage'));

                // clear flag - avoid issues with caching plugin - override AND delete the flag
                update_option('enlighter-upgrade', '-');
                delete_option('enlighter-upgrade');
            }
        }
    }

    // settings page initialization
    public function setupBackend(){
        if (!current_user_can('manage_options')){
            return;
        }

        // call register settings function
        add_action('admin_init', array($this->_settingsManager, 'registerSettings'));

        // add links to plugin list
        add_filter('plugin_action_links', array($this, 'addPluginPageSettingsLink'), 10, 2);
        add_filter('plugin_row_meta', array($this, 'addPluginMetaLinks'), 10, 2);

        // get menu structure
        $menu = $this->getBackendMenu();

        // menu set ?
        if ($menu === null){
            return;
        }

        // utility
        $addOnloadHandler = function($page, $menu){
            // add load handler
            add_filter('load-'.$page, function() use ($menu){
                // onload handler ?
                if (isset($menu['onLoad'])){
                    call_user_func($menu['onLoad']);
                }

                // resource handler ?
                if (isset($menu['resources'])){
                    call_user_func($menu['resources']);
                }

                // contextual help handler ?
                if (isset($menu['help'])){
                    call_user_func($menu['help']);
                }
            });
        };

        // template rendering
        $renderTemplate = function($menuItem){
            // vars to extract
            $vars = array();

            // on output hook set ?
            if (isset($menuItem['render'])){
                $vars = call_user_func($menuItem['render']);
            }

            // vars set ? extract; use references; add V_ prefix to each var
            extract($vars, EXTR_SKIP | EXTR_REFS | EXTR_PREFIX_ALL, 'V');

            // render settings view
            include(ENLIGHTER_PLUGIN_PATH.'/views/admin/'.$menuItem['template'].'.phtml');
        };

        if (!isset($menu['items'])){
            // initialize toplevel menu
            $optionsPage = add_menu_page(
                $menu['pagetitle'],
                $menu['title'],
                'administrator',
                $menu['slug'],
                function() use ($menu, $renderTemplate){
                    $renderTemplate($menu);
                },
                $menu['icon']
            );

            // add onLoad handler
            $addOnloadHandler($optionsPage, $menu);

        // submenu items set ?
        }else{
            // initialize toplevel menu group
            $optionsPage = add_menu_page(
                $menu['pagetitle'],
                $menu['title'],
                'administrator',
                $menu['slug'],
                '',
                $menu['icon']
            );

            // push mainmenu item on top of the list
            array_unshift($menu['items'], array(
                'pagetitle' =>  $menu['pagetitle'],
                'title' =>      isset($menu['title2']) ? $menu['title2'] : $menu['title'],
                'slug' =>       $menu['slug'],
                'template' =>   $menu['template'],
                'resources' =>  isset($menu['resources']) ? $menu['resources'] : null,
                'help' =>       isset($menu['help']) ? $menu['help'] : null,
                'onLoad' =>     isset($menu['onLoad']) ? $menu['onLoad'] : null,
                'render' =>     isset($menu['render']) ? $menu['render'] : null,
            ));

            // process submenu items
            foreach ($menu['items'] as $submenu){
                // initialize submenu
                $submenuPage = add_submenu_page(
                    $menu['slug'],
                    $submenu['pagetitle'],
                    $submenu['title'],
                    'administrator',
                    $submenu['slug'],
                    function() use ($submenu, $renderTemplate){
                        $renderTemplate($submenu);
                    }
                );

                // add onLoad handler
                $addOnloadHandler($submenuPage, $submenu);
            }
        }


    }

    // retrieve backend/admin menu structure
    protected function getBackendMenu(){
        return null;
    }

    // retrieve plugin config
    public function getPluginConfig(){
        return $this->_settingsManager->getOptions();
    }

    // links to the plugin website & author's twitter channel ()
    public function addPluginMetaLinks($links, $file){
        // current plugin ?
        if ($file == 'enlighter/Enlighter.php'){
            if ($this->_pluginMetaLinks !== null && count($this->_pluginMetaLinks) > 0){
                foreach ($this->_pluginMetaLinks as $link => $text){
                    $links[] = '<a target="_blank" href="'.esc_attr($link).'">'.esc_html($text).'</a>';
                }
            }
        }
        
        return $links;
    }

    // links on the plugin page
    public function addPluginPageSettingsLink($links, $file){
        // current plugin ?
        if ($file == 'enlighter/Enlighter.php'){
            // settings page link ?
            if ($this->_pluginMetaSettingsPage !== null){
                $links[] = '<a href="'.admin_url('admin.php?page=enlighter-'.$this->_pluginMetaSettingsPage). '">'.__('Settings', 'enlighter').'</a>';
            }

            // about page link ?
            if ($this->_pluginMetaAboutPage !== null){
                $links[] = '<a href="'.admin_url('admin.php?page=enlighter-'.$this->_pluginMetaAboutPage). '">'.__('About', 'enlighter').'</a>';
            }
        }
        return $links;
    }

    // init hook executed with priority 9999
    public function _wp_lateinit(){
    }

    // plugn hooks
    public function _wp_plugin_activate(){
        // set activation flag
        add_option('enlighter-activation-redirect', 'about-page');
    }

    public function _wp_plugin_deactivate(){
    }

    public function _wp_plugin_upgrade($currentVersion){
        return true;
    }

    // Show Upgrade Notification in Plugin List for an available new Version
    public function showUpgradeAvailabilityNotification($currentPluginMetadata, $newPluginMetadata){
        // check "upgrade_notice"
        if (isset($newPluginMetadata->upgrade_notice) && strlen(trim($newPluginMetadata->upgrade_notice)) > 0){
            echo '<br /><span style="display: inline-block; background-color: #d54e21; padding: 5px 10px 5px 10px; color: #f9f9f9; margin-top: 10px"><strong>Important Upgrade Notice:</strong> ';
            echo esc_html(strip_tags($newPluginMetadata->upgrade_notice)), '</span>';
        }
    }

    // Show Admin Notice for Successfull Plugin Upgrade
    public function showUpgradeMessage(){
        // styling
        echo '<div class="notice notice-success is-dismissible"><p>';
        echo '<strong>Enlighter Plugin Upgrade:</strong> The Plugin has been upgraded to <strong>4.5.0</strong>';
        echo '</p></div>';
    }

    // singleton instance
    private static $__instance;
    public static function getInstance(){
        return self::$__instance;
    }

    // static entry/initialize singleton instance
    public static function run($pluginName){
        // check if singleton instance is available
        if (self::$__instance==null){
            // create new instance if not - use php 5.3 late bindings
            $i = self::$__instance = new static();

            // register plugin related hooks
            register_activation_hook($pluginName, array($i, '_wp_plugin_activate'));
            register_deactivation_hook($pluginName, array($i, '_wp_plugin_deactivate'));
            add_action('init', array($i, '_wp_init'));
            add_action('init', array($i, '_wp_lateinit'), 9999);

            // fetch plugin version
            $version = get_option('enlighter-version', '0.0.0');

            // plugin installed ?
            if ($version == '0.0.0'){
                // store new version
                update_option('enlighter-version', '4.5.0');

            // plugin upgraded ?
            }else if (version_compare('4.5.0', $version, '>')){
                // run upgrade hook
                if ($i->_wp_plugin_upgrade($version)){
                    // store new version
                    update_option('enlighter-version', '4.5.0');

                    // set flag (string!)
                    update_option('enlighter-upgrade', 'true');
                }
            }
        }
    }


}