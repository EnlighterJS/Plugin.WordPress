<?php

namespace Enlighter\extensions;

use \Enlighter\skltn\ResourceManager as ResourceManager;

class JQuery{

    // @see resources/extensions/jquery-ajaxcomplete.js
    public static function getAjaxcompleteCode(){
        return '!function(e){"undefined"!=typeof jQuery&&jQuery(document).on("ajaxComplete",function(){"undefined"!=typeof EnlighterJSINIT&&e.setTimeout(function(){EnlighterJSINIT.apply(e)},180)})}(window);';
    }
}