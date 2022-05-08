<?php
/**
    Plugin Name: Enlighter - Customizable Syntax Highlighter
    Plugin URI: https://enlighterjs.org
    Description: all-in-one syntax highlighting solution
    Version: 4.5.0
    Author: Andi Dittrich
    Author URI: https://andidittrich.com
    License: GPL-2.0
    Text Domain: enlighter
    Domain Path: /lang
    Requires PHP: 5.6
*/


// Plugin Bootstrap Operation
// AUTO GENERATED CODE - DO NOT EDIT !!!
define('ENLIGHTER_INIT', true);
define('ENLIGHTER_VERSION', '4.5.0');
define('ENLIGHTER_WPSKLTN_VERSION', '0.28.0');
define('ENLIGHTER_PHP_VERSION', '5.6');
define('ENLIGHTER_PLUGIN_TITLE', 'Enlighter - Customizable Syntax Highlighter');
define('ENLIGHTER_PLUGIN_HEADLINE', 'all-in-one syntax highlighting solution');
define('ENLIGHTER_PLUGIN_PATH', dirname(__FILE__));
define('ENLIGHTER_PLUGIN_URL', plugins_url('/enlighter'));


// PHP Version Error Notice
function Enlighter_PhpEnvironmentError(){
    // error message
    $message = '<strong>Enlighter Plugin Error:</strong> Your PHP Version <strong style="color: #cc0a00">('. phpversion() .')</strong> is outdated! <strong>PHP 5.4 or greater</strong> is required to run this plugin!';

    // styling
    echo '<div class="notice notice-error is-dismissible"><p>', $message, '</p></div>';
}

// check php version
if (version_compare(phpversion(), ENLIGHTER_PHP_VERSION, '>=')){
    // load classes
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/CacheManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/CssBuilder.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/EnvironmentCheck.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/Hash.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/HtmlUtil.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/JsBuilder.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/Plugin.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/PluginConfig.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/ResourceManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/RewriteRuleHelper.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/SettingsManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/SettingsViewHelper.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/skltn/VirtualPageManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/admin/ContextualHelp.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/compatibility/CodeColorer.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/compatibility/Crayon.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/compatibility/GenericType1.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/compatibility/GenericType2.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/DynamicResourceInvocation.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/Enlighter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/EnlighterJS.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/EnvironmentCheck.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/FontManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/KSES.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/LanguageManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/ResourceLoader.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/ThemeManager.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/core/Versions.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/customizer/ThemeCustomizer.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/customizer/Toolbar.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/editor/EditorConfig.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/editor/Gutenberg.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/editor/QuickTags.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/editor/TinyMCE.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/extensions/BBPress.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/extensions/JQuery.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/extensions/Jetpack.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/filter/CompatibilityModeFilter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/filter/ContentProcessor.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/filter/FragmentBuffer.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/filter/GfmFilter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/filter/InputFilter.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/filter/LegacyShortcodeHandler.php');
    require_once(ENLIGHTER_PLUGIN_PATH.'/modules/filter/ShortcodeFilter.php');
if (defined('WP_CLI') && WP_CLI){
}

    
    // startup - NEVER CALL IT OUTSIDE THIS FILE !!
    Enlighter::run(__FILE__);
}else{
    // add admin message handler
    add_action('admin_notices', 'Enlighter_PhpEnvironmentError');
    add_action('network_admin_notices', 'Enlighter_PhpEnvironmentError');
}