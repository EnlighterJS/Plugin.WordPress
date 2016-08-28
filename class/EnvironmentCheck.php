<?php
/**
Environment Check to ensure plugin compatibility with current WordPress setup
Version: 1.0
Author: Andi Dittrich
Author URI: http://andidittrich.de
Plugin URI: http://andidittrich.de/go/enlighterjs
License: MIT X11-License

Copyright (c) 2013-2016, Andi Dittrich

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Enlighter;

class EnvironmentCheck{

    // check for common environment errors
    public static function check(){

        // list of errors
        $errors = array();
        $warnings[] = array();

        // bad xhtml fixing has been removed from WordPress v2, but sometime it is still enabled, only the setting is removed from the settings page !
        // see https://core.trac.wordpress.org/changeset/3223
        if (get_option('use_balanceTags') != 0){
            $errors[] = __('Option "use_balanceTags" is enabled - this option is DEPRECATED. Might cause a weired behaviour by inserting random closing html tags into your code.', 'enlighter');
        }

        // Crayon Syntax highlighter may take over the Enlighter <pre> elements
        if (is_plugin_active('crayon-syntax-highlighter/crayon_wp.class.php')){
            $errors[] = __('Plugin "Crayon Syntax Highlighter" is enabled - it may take over the Enlighter pre elements - highlighting will not work!', 'enlighter');
        }

        return array(
            'errors' => $errors,
            'warnings' => $warnings
        );
    }
}