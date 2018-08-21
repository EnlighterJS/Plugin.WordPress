<?php
/**
    Plugin Name: Enlighter - Customizable Syntax Highlighter
    Plugin URI: https://enlighterjs.org
    Description: Enlighter is a free, easy-to-use, syntax highlighting tool with a build-in theme editor
    Version: 4.0
    Author: Andi Dittrich
    Author URI: https://andidittrich.de
    License: GPL-2.0
    Text Domain: enlighter
    Domain Path: /lang
*/


// Plugin Bootstrap Operation
// AUTO GENERATED CODE - DO NOT EDIT !!!
define('ENLIGHTER_INIT', true);
define('ENLIGHTER_VERSION', '4.0');
define('ENLIGHTER_WPSKLTN_VERSION', '0.16.0');
define('ENLIGHTER_PLUGIN_TITLE', 'Enlighter - Customizable Syntax Highlighter');
define('ENLIGHTER_PLUGIN_HEADLINE', 'Enlighter is a free, easy-to-use, syntax highlighting tool with a build-in theme editor');
define('ENLIGHTER_PLUGIN_PATH', dirname(__FILE__));
define('ENLIGHTER_PLUGIN_URL', plugins_url('/enlighter/'));


// PHP Version Error Notice
function Enlighter_PhpEnvironmentError(){
    // error message
    $message = '<strong>Enlighter Plugin Error:</strong> Your PHP Version <strong style="color: #cc0a00">('. phpversion() .')</strong> is outdated! <strong>PHP 5.4 or greater</strong> is required to run this plugin!';

    // styling
    echo '<div class="notice notice-error is-dismissible"><p>', $message, '</p></div>';
}

// check php version
if (version_compare(phpversion(), '5.4', '>=')){
    // load classes
    require_once(ENLIGHTER_PLUGIN_PATH.'/skltn/HtmlUtil.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/skltn/SettingsManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/skltn/SettingsViewHelper.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/skltn/CacheManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/skltn/ResourceManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/skltn/PluginConfig.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/skltn/CssBuilder.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/skltn/Hash.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/skltn/VirtualPageManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/skltn/RewriteRuleHelper.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/skltn/Plugin.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/BBPress.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/CompatibilityModeFilter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/ConfigGenerator.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/ContentProcessor.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/ContextualHelp.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/Enlighter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/EnvironmentCheck.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/GfmFilter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/GoogleWebfontResources.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/InputFilter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/LanguageManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/LegacyShortcodeHandler.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/ResourceLoader.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/ShortcodeFilter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/SimpleTemplate.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/ThemeGenerator.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/ThemeManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/class/TinyMCE.php');

    
    // startup - NEVER CALL IT OUTSIDE THIS FILE !!
    Enlighter::run(__FILE__);
}else{
    // add admin message handler
    add_action('admin_notices', 'Enlighter_PhpEnvironmentError');
    add_action('network_admin_notices', 'Enlighter_PhpEnvironmentError');
}

