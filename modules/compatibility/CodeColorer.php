<?php

namespace Enlighter\compatibility;

use Enlighter\skltn\HtmlUtil;
use Enlighter\filter\InputFilter;

class CodeColorer{

    // used by CodeColorer
    public static function getRegex(){
        // opening tag based on enabled shortcodes
        // ccIESNBWL_lang
        return '/\[(cc\w*?)'.

        // shortcode attributes (optional)
        '\s*(.*)\s*' .

        // close opening tag
        '\]' .

        // arbitrary multi-line content
        '([\S\s]*)' .

        // closing tag using opening tag back reference
        '\[\/\1]' .

        // ungreedy, case insensitive
        '/Ui';
    }

    // convert regex match to enlighter codeblock
    public static function convert($match){

        // inline or block ?
        $mode='block';

        // enlighter html tag standard attributes
        $htmlAttributes = array(
            'class' => 'EnlighterJSRAW'
        );

        // wordpress internal shortcode attribute parsing
        $ccAttributes = shortcode_parse_atts($match[2]);

        // shortcode_parse_atts return empty string in case no attributes were parsed...
        if (is_string($ccAttributes)){
            $ccAttributes = array();
        }

        // try to parse flags
        if (preg_match('/^cc([IESNBWL]+)?(?:_(\w+))?$/Ui', $match[1], $flagMatches)){

            // language set ?
            if (isset($flagMatches[2]) && strlen($flagMatches[2]) > 0){
                $ccAttributes['lang'] = $flagMatches[2];
            }

            // short options set ?
            if (isset($flagMatches[1]) && strlen($flagMatches[1]) > 0){
                // inline enabled ?
                if (strpos($flagMatches[1], 'i') !== false){
                    $mode="inline";
                }

                // line numbering enforced ?
                if (strpos($flagMatches[1], 'n') !== false){
                    $htmlAttributes['data-enlighter-linenumbers'] = 'true';
                }else if (strpos($flagMatches[1], 'N') !== false){
                    $htmlAttributes['data-enlighter-linenumbers'] = 'false';
                }
            }
        }

        // supported attributes
        // -------------------------

        // language set ?
        if (isset($ccAttributes['lang'])){
            $htmlAttributes['data-enlighter-language'] = InputFilter::filterLanguage($ccAttributes['lang']);
        }
 
        // line offset set ?
        if (isset($ccAttributes['first_line'])){
            $htmlAttributes['data-enlighter-lineoffset'] = intval($ccAttributes['first_line']);
        }

        // highlight set ?
        if (isset($ccAttributes['highlight'])){
            $htmlAttributes['data-enlighter-highlight'] = $ccAttributes['highlight'];
        }

        // linenumbers set ?
        if (isset($ccAttributes['line_numbers'])){
            $htmlAttributes['data-enlighter-linenumbers'] = $ccAttributes['line_numbers'];
        }

        // inline set ?
        if (isset($ccAttributes['inline']) && $ccAttributes['inline'] == 'true'){
            $mode="inline";
        }

        // generate new html tag
        if ($mode === 'block'){
            return HtmlUtil::generateTag('pre', $htmlAttributes, true, $match[3]);
        }else{
            return HtmlUtil::generateTag('code', $htmlAttributes, true, $match[3]);
        }
    }
}