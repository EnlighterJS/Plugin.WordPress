<?php

namespace Enlighter;

class Versions{

    // utility function to extract the first version string from file
    private static function extractVersionString($filename, $regex='#^[\S\s]+\s+(\d\.\d+\.\d+)?\s#U'){
        if (!file_exists(ENLIGHTER_PLUGIN_PATH.'/resources/' . $filename)){
            return 'NA';
        }

        $content = file_get_contents(ENLIGHTER_PLUGIN_PATH.'/resources/' . $filename);

        // extract version
        $r = preg_match($regex, $content, $matches);

        // valid result ?
        if ($r!==1){
            return 'NaN';
        }else{
            return $matches[1];
        }
    }

    // gets the current EnlighterJS version from js file
    public static function getEnlighterJSVersion(){
        return self::extractVersionString('enlighterjs/enlighterjs.min.js');
    }

    // gets the current EnlighterJS.TinyMCE version from js file
    public static function getTinyMCEPluginVersion(){
        return self::extractVersionString('tinymce/enlighterjs.tinymce.min.js');
    }

    // gets the current EnlighterJS.Gutenberg version from js file
    public static function getGutenbergPluginVersion(){
        return self::extractVersionString('gutenberg/enlighterjs.gutenberg.min.js');
    }

    // gets the current EnlighterJS.ThemeCustomizer version from js file
    public static function getThemeCustomizerPluginVersion(){
        return self::extractVersionString('customizer/enlighterjs.customizer.min.js');
    }
}