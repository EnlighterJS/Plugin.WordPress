<?php
// ---------------------------------------------------------------------------------------------------------------
// -- WP-SKELETON AUTO GENERATED FILE - DO NOT EDIT !!!
// --
// -- Copyright (c) 2016-2018 Andi Dittrich
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

    public function __construct(){
    }

    // init hook executed with std priority
    public function _wp_init(){
        // frontend or admin area ?
        if (is_admin()){

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

    // init hook executed with priority 9999
    public function _wp_lateinit(){
    }

    // plugn hooks
    public function _wp_plugin_activate(){
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
        echo '<strong>Enlighter Plugin Upgrade:</strong> The Plugin has been upgraded to <strong>4.0</strong>';
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
                update_option('enlighter-version', '4.0');

            // plugin upgraded ?
            }else if (version_compare('4.0', $version, '>')){
                // run upgrade hook
                if ($i->_wp_plugin_upgrade($version)){
                    // store new version
                    update_option('enlighter-version', '4.0');

                    // set flag (string!)
                    update_option('enlighter-upgrade', 'true');
                }
            }
        }
    }


}