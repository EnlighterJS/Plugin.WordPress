<?php

namespace Enlighter\compatibility;

use Enlighter\skltn\HtmlUtil;
use Enlighter\filter\InputFilter;

class Crayon{

    // used by Crayon
    // note: full filtering is used to avoid wpautop issues!
    public static function getRegex(){
        // opening tag, language identifier (required), additional attributes (optional)
        return '/<pre\s+class="(.*lang:.+)"\s*(?:title="(.+)")?\s*>' .

        // arbitrary multi-line content
        '([\S\s]*)' .

        // closing tag
        '<\/pre>' .

        // ungreedy, case insensitive, multiline
        '/Uim';
    }

    // convert regex match to enlighter codeblock
    public static function convert($match){

        // enlighter html tag standard attributes
        $htmlAttributes = array(
            'class' => 'EnlighterJSRAW'
        );
        
        // list of cryon attributes
        $crayonAttributes = array();

        // separate options; remove multiple whitespaces
        $options = explode(' ', preg_replace('/\s+/', ' ', $match[1]));

        // parse
        foreach ($options as $opt){
            $parts = explode(':', $opt);

            // key:value pair provided ?
            if (count($parts) === 2){
                $key = preg_replace('/[^a-z]/', '_', $parts[0]);
                $crayonAttributes[$key] = $parts[1];
            }
        }

        // supported attributes
        // -------------------------
        // start-line
        // lang
        // nums
        // mark

        // language set ?
        if (isset($crayonAttributes['lang'])){
            $htmlAttributes['data-enlighter-language'] = InputFilter::filterLanguage($crayonAttributes['lang']);
        }

        // line offset set ?
        if (isset($crayonAttributes['start-line'])){
            $htmlAttributes['data-enlighter-lineoffset'] = intval($crayonAttributes['start-line']);
        }

        // highlight set ?
        if (isset($crayonAttributes['mark'])){
            $htmlAttributes['data-enlighter-highlight'] = $crayonAttributes['mark'];
        }

        // linenumbers set ?
        if (isset($crayonAttributes['nums'])){
            $htmlAttributes['data-enlighter-linenumbers'] = $crayonAttributes['nums'];
        }

        // title set ?
        if (isset($match[2])){
            $htmlAttributes['data-enlighter-title'] = trim($match[2]);
        }

        // generate new html tag
        return HtmlUtil::generateTag('pre', $htmlAttributes, true, $match[3]);
    }
}