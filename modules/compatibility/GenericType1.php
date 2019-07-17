<?php

namespace Enlighter\compatibility;

use Enlighter\skltn\HtmlUtil;
use Enlighter\filter\InputFilter;

class GenericType1{

    // used by e.g. JetPack markdown
    public static function getRegex(){
        // opening tag, language identifier (optional)
        return '/<pre><code(?:\s+class="([a-z]+)")?>' .

        // arbitrary multi-line content
        '([\S\s]*)' .

        // closing tags
        '\s*<\/code>\s*<\/pre>\s*' .

        // ungreedy, case insensitive, multiline
        '/Uim';
    }

    // convert regex match to enlighter codeblock
    public static function convert($match){

        // enlighter html tag standard attributes
        $htmlAttributes = array(
            'class' => 'EnlighterJSRAW'
        );
        
        // language set ?
        if (isset($match[1])){
            $htmlAttributes['data-enlighter-language'] = InputFilter::filterLanguage($match[1]);
        }

        // generate new html tag
        return HtmlUtil::generateTag('pre', $htmlAttributes, true, $match[2]);
    }

}