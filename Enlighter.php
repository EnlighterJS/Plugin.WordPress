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
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/HtmlUtil.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/SettingsManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/SettingsViewHelper.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/CacheManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/ResourceManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/PluginConfig.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/CssBuilder.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/Hash.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/VirtualPageManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/RewriteRuleHelper.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/Plugin.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/BBPress.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/CompatibilityModeFilter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/ConfigGenerator.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/ContentProcessor.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/ContextualHelp.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/Enlighter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/EnlighterJSConfig.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/EnvironmentCheck.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/GfmFilter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/GoogleWebfontResources.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/InputFilter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/LanguageManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/LegacyShortcodeHandler.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/ResourceLoader.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/ShortcodeFilter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/SimpleTemplate.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/ThemeGenerator.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/ThemeManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/TinyMCE.php');

    
    // startup - NEVER CALL IT OUTSIDE THIS FILE !!
    Enlighter::run(__FILE__);
}else{
    // add admin message handler
    add_action('admin_notices', 'Enlighter_PhpEnvironmentError');
    add_action('network_admin_notices', 'Enlighter_PhpEnvironmentError');
}

