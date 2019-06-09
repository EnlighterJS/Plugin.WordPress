<?php

namespace Enlighter;

class EnvironmentCheck{

    private $_cacheManager;

    public function __construct($cacheManager){
        $this->_cacheManager = $cacheManager;
    }

    // external triggered as admin_notices
    public function throwNotifications(){
        // trigger check
        $results = self::check();

        // show errors
        foreach ($results['errors'] as $err){
            // styling
            echo '<div class="notice notice-error enlighter-notice"><p><strong>Enlighter Plugin Error: </strong>', $err, '</p></div>';
        }

        // show warnings
        foreach ($results['warnings'] as $err){
            // styling
            echo '<div class="notice notice-warning enlighter-notice"><p><strong>Enlighter Plugin Warning: </strong>', $err, '</p></div>';
        }
    }

    // check for common environment errors
    public function check(){

        // list of errors
        $errors = array();
        $warnings = array();

        // bad xhtml fixing has been removed from WordPress v2, but sometime it is still enabled, only the setting is removed from the settings page !
        // see https://core.trac.wordpress.org/changeset/3223
        if (get_option('use_balanceTags') != 0){
            $warnings[] = __('Option <code>use_balanceTags</code> is enabled - this option is <strong>DEPRECATED</strong>. Might cause a weired behaviour by inserting random closing html tags into your code.', 'enlighter');
        }

        // Smilies shouldn't render within sourcecode
        if (get_option('use_smilies') != 0){
            $warnings[] = __('Option <code>use_smilies</code> is enabled. Legacy smiley sequences like :) are replaced by images which also affects posted sourcecode.', 'enlighter');
        }

        // Crayon Syntax highlighter may take over the Enlighter <pre> elements
        if (is_plugin_active('crayon-syntax-highlighter/crayon_wp.class.php')){
            $errors[] = __('Plugin "Crayon Syntax Highlighter" is enabled - it may take over the Enlighter pre elements - highlighting will not work!', 'enlighter');
        }

        // cache accessible ?
        if (!$this->_cacheManager->isCacheAccessible()){
            $errors[] = __('The cache-directory <code>'. $this->_cacheManager->getCachePath(). '</code> is not writable! Please change the directory permission (chmod <code>0774</code> or <code>0777</code>) to use the ThemeCustomizer (the generated stylesheets are stored there). - <a href="'.admin_url('admin.php?page=Enlighter').'&cache-permission-fix=true">Autoset Permissions</a>');

            // fix successful ?
            if (isset($_GET['cache-permission-fix'])){
                $errors[] = __('Autoset Permissions failed - Please change the directory permission (chmod <code>0644</code> or <code>0777</code>) manually!');
            }
        }

        return array(
            'errors' => $errors,
            'warnings' => $warnings
        );
    }
}