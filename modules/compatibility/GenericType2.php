<?php

namespace Enlighter\compatibility;

use Enlighter\skltn\HtmlUtil;
use Enlighter\filter\InputFilter;

class GenericType2{

    // used by e.g. JetPack markdown
    public static function getRegex(){
        // opening tag - no language identifier
        return '/<pre(?:[^>]+?)?><code>' .

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

        // generate new html tag
        return HtmlUtil::generateTag('pre', $htmlAttributes, true, $match[1]);
    }

}