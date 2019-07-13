<?php

namespace Enlighter;

// @see https://codex.wordpress.org/Function_Reference/wp_kses
class KSES{

    // enable EnlighterJS html attributes for Authors and Contributors
    public static function allowHtmlCodeAttributes($data, $context){
        // only apply filter on post-context
        if ($context === 'post'){

            // list of all available enlighterjs attributes
            $allowedAttributes = array(
                'data-enlighter-language' => true,
                'data-enlighter-theme' => true,
                'data-enlighter-group' => true,
                'data-enlighter-title' => true,
                'data-enlighter-linenumbers' => true,
                'data-enlighter-highlight' => true,
                'data-enlighter-lineoffset' => true
            );

            // apply to pre and code tags
            if (isset($data['pre'])){
                $data['pre'] = array_merge($data['pre'], $allowedAttributes);
            }
            if (isset($data['code'])){
                $data['code'] = array_merge($data['code'], $allowedAttributes);
            }
        }

        return $data;
    }

}