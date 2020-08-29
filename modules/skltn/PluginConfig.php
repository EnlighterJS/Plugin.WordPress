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

// Plugin Config Defaults

namespace Enlighter\skltn;

class PluginConfig{
    
    // config keys with default values
    private $_defaultConfig = array(
        'translation-enabled' => true,
        'enlighterjs-init' => 'inline',
        'enlighterjs-assets-js' => true,
        'enlighterjs-assets-themes' => true,
        'enlighterjs-assets-themes-external' => false,
        'enlighterjs-selector-block' => 'pre.EnlighterJSRAW',
        'enlighterjs-selector-inline' => 'code.EnlighterJSRAW',
        'enlighterjs-indent' => 4,
        'enlighterjs-ampersandcleanup' => true,
        'enlighterjs-linehover' => true,
        'enlighterjs-rawcodedbclick' => false,
        'enlighterjs-textoverflow' => 'break',
        'enlighterjs-linenumbers' => true,
        'enlighterjs-theme' => 'enlighter',
        'enlighterjs-retaincss' => false,
        'enlighterjs-language' => 'generic',
        'toolbar-visibility' => 'default',
        'toolbar-button-raw' => true,
        'toolbar-button-copy' => true,
        'toolbar-button-window' => true,
        'toolbar-button-enlighterjs' => true,
        'tinymce-backend' => true,
        'tinymce-frontend' => false,
        'tinymce-formats' => true,
        'tinymce-autowidth' => false,
        'tinymce-tabindentation' => false,
        'tinymce-keyboardshortcuts' => false,
        'tinymce-font' => 'sourcecodepro',
        'tinymce-fontsize' => '0.7em',
        'tinymce-lineheight' => '1.4em',
        'tinymce-color' => '#000000',
        'tinymce-bgcolor' => '#f9f9f9',
        'gutenberg-backend' => true,
        'quicktag-backend' => false,
        'quicktag-frontend' => false,
        'quicktag-mode' => 'html',
        'shortcode-mode' => 'disabled',
        'shortcode-inline' => true,
        'shortcode-type-generic' => true,
        'shortcode-type-language' => false,
        'shortcode-type-group' => false,
        'shortcode-filter-content' => true,
        'shortcode-filter-excerpt' => true,
        'shortcode-filter-widget' => false,
        'shortcode-filter-comment' => false,
        'shortcode-filter-commentexcerpt' => false,
        'gfm-enabled' => false,
        'gfm-inline' => true,
        'gfm-language' => 'raw',
        'gfm-filter-content' => true,
        'gfm-filter-excerpt' => true,
        'gfm-filter-widget' => false,
        'gfm-filter-comment' => false,
        'gfm-filter-commentexcerpt' => false,
        'compat-enabled' => false,
        'compat-crayon' => false,
        'compat-codecolorer' => false,
        'compat-type1' => false,
        'compat-type2' => false,
        'compat-filter-content' => true,
        'compat-filter-excerpt' => true,
        'compat-filter-widget' => false,
        'compat-filter-comment' => false,
        'compat-filter-commentexcerpt' => false,
        'cache-custom' => false,
        'cache-path' => '',
        'cache-url' => '',
        'dynamic-resource-invocation' => false,
        'ext-infinite-scroll' => false,
        'jetpack-gfm-code' => false,
        'ext-ajaxcomplete' => false,
        'bbpress-shortcode' => false,
        'bbpress-markdown' => false
    );

    // validation
    private $_validators = array(
        'translation-enabled' => 'boolean',
        'enlighterjs-init' => 'string',
        'enlighterjs-assets-js' => 'boolean',
        'enlighterjs-assets-themes' => 'boolean',
        'enlighterjs-assets-themes-external' => 'boolean',
        'enlighterjs-selector-block' => 'string',
        'enlighterjs-selector-inline' => 'string',
        'enlighterjs-indent' => 'int',
        'enlighterjs-ampersandcleanup' => 'boolean',
        'enlighterjs-linehover' => 'boolean',
        'enlighterjs-rawcodedbclick' => 'boolean',
        'enlighterjs-textoverflow' => 'string',
        'enlighterjs-linenumbers' => 'boolean',
        'enlighterjs-theme' => 'string',
        'enlighterjs-retaincss' => 'boolean',
        'enlighterjs-language' => 'string',
        'toolbar-visibility' => 'string',
        'toolbar-button-raw' => 'boolean',
        'toolbar-button-copy' => 'boolean',
        'toolbar-button-window' => 'boolean',
        'toolbar-button-enlighterjs' => 'boolean',
        'tinymce-backend' => 'boolean',
        'tinymce-frontend' => 'boolean',
        'tinymce-formats' => 'boolean',
        'tinymce-autowidth' => 'boolean',
        'tinymce-tabindentation' => 'boolean',
        'tinymce-keyboardshortcuts' => 'boolean',
        'tinymce-font' => 'string',
        'tinymce-fontsize' => 'string',
        'tinymce-lineheight' => 'string',
        'tinymce-color' => 'string',
        'tinymce-bgcolor' => 'string',
        'gutenberg-backend' => 'boolean',
        'quicktag-backend' => 'boolean',
        'quicktag-frontend' => 'boolean',
        'quicktag-mode' => 'string',
        'shortcode-mode' => 'string',
        'shortcode-inline' => 'boolean',
        'shortcode-type-generic' => 'boolean',
        'shortcode-type-language' => 'boolean',
        'shortcode-type-group' => 'boolean',
        'shortcode-filter-content' => 'boolean',
        'shortcode-filter-excerpt' => 'boolean',
        'shortcode-filter-widget' => 'boolean',
        'shortcode-filter-comment' => 'boolean',
        'shortcode-filter-commentexcerpt' => 'boolean',
        'gfm-enabled' => 'boolean',
        'gfm-inline' => 'boolean',
        'gfm-language' => 'string',
        'gfm-filter-content' => 'boolean',
        'gfm-filter-excerpt' => 'boolean',
        'gfm-filter-widget' => 'boolean',
        'gfm-filter-comment' => 'boolean',
        'gfm-filter-commentexcerpt' => 'boolean',
        'compat-enabled' => 'boolean',
        'compat-crayon' => 'boolean',
        'compat-codecolorer' => 'boolean',
        'compat-type1' => 'boolean',
        'compat-type2' => 'boolean',
        'compat-filter-content' => 'boolean',
        'compat-filter-excerpt' => 'boolean',
        'compat-filter-widget' => 'boolean',
        'compat-filter-comment' => 'boolean',
        'compat-filter-commentexcerpt' => 'boolean',
        'cache-custom' => 'boolean',
        'cache-path' => 'string',
        'cache-url' => 'string',
        'dynamic-resource-invocation' => 'boolean',
        'ext-infinite-scroll' => 'boolean',
        'jetpack-gfm-code' => 'boolean',
        'ext-ajaxcomplete' => 'boolean',
        'bbpress-shortcode' => 'boolean',
        'bbpress-markdown' => 'boolean'
    );

    // get the default plugin config
    public function getDefaults(){
        return $this->_defaultConfig;
    }

    // get all validators
    public function getValidators(){
        return $this->_validators;
    }

    // get corresponding validator in case its available
    public function getValidator($key){
        if (isset($this->_validators[$key])){
            return $this->_validators[$key];
        }else{
            return null;
        }
    }

    // add dynamics key/value/validator pairs
    public function add($key, $value, $validator = null){
        // add key/value pair
        $this->_defaultConfig[$key] = $value;

        // validator given ?
        if ($validator){
            $this->_validators[$key] = $validator;
        }
    }
}