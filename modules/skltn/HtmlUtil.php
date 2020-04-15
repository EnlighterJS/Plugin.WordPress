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

// Generate HTML Tags with a given list of attributes as key=>value array

namespace Enlighter\skltn;

class HtmlUtil{
    
    // generate a HTML tag and escape attribute/value pairs + content
    public static function generateTag($name, $htmlAttributes = array(), $selfClosing=true, $content=false){
        // generate tag start
        $html = '<'.strtolower($name);
        
        // generate html attributes
        foreach ($htmlAttributes as $key=>$value){
            $html .= ' '.esc_attr($key).'="'.esc_attr($value).'"';
        }
        
        // self closing and no content ?
        if ($selfClosing === true){

            // add content ?
            if ($content !== false){

                $html .= '>' . esc_html($content) . '</'.strtolower($name) . '>';

            // just close it
            }else{
                $html .= ' />';
            }
        }else{
            // add content ?
            if ($content !== false){
                $html .= '>' . esc_html($content);
            }else{
                $html .= '>';
            }
        }
        
        return $html;
    }
    
}