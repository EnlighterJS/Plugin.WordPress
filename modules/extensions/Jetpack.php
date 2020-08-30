<?php

namespace Enlighter\extensions;

use \Enlighter\skltn\ResourceManager as ResourceManager;

class Jetpack{

    public static function init($gfmCode=false){

        // markdown codeblock compatibility
        if ($gfmCode){
            // preserve gfm style codeblocks + extended inline codeblocks
            add_filter('jetpack_markdown_preserve_pattern', function ($patterns){
                // exclude inline codeblocks (standard+extended)
                $patterns[] = '/(^|\s)`[^`]+`\w*(\s|$)/Um';

                // exclude fenced codeblocks (GFM)
                $patterns[] = '/^```[\S\s]+?```\s*$/m';

                return $patterns;
            });
        }

    }

    // @see resources/extensions/jetpack-infinite-scroll.js
    public static function getInfiniteScrollCode(){
        return '!function(n){"undefined"!=typeof jQuery&&jQuery(document.body).on("post-load",function(){"undefined"!=typeof EnlighterJSINIT&&n.setTimeout(function(){EnlighterJSINIT.apply(n)},180)})}(window);';
    }
}