<?php

namespace Enlighter;

class EnvironmentCheck
    extends \Enlighter\skltn\EnvironmentCheck{

    private $_cacheManager;

    public function __construct($cacheManager){
        $this->_cacheManager = $cacheManager;
    }

    // @TODO replace self url
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

        // plugin path wp-content/plugins/enlighter ?
        if (strpos(__DIR__, 'enlighter'.DIRECTORY_SEPARATOR.'modules') === false){
            $errors[] = __('The plugin is located within an invalid path - the <code>enlighter/</code> directory name is <strong>mandatory</strong>', 'enlighter');
        }

        // Experimental Compat Plugin enabled ?
        if (defined('ENLIGHTERJS3_COMPAT_VERSION')){
            $errors[] = __('Plugin "EnlighterJS3 Compatibility" is enabled - please disable it to use Enlighter v4! ', 'enlighter');
        }

        return array(
            'errors' => $errors,
            'warnings' => $warnings
        );
    }
}