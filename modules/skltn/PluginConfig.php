<?php
// ---------------------------------------------------------------------------------------------------------------
// -- WP-SKELETON AUTO GENERATED FILE - DO NOT EDIT !!!
// --
// -- Copyright (c) 2016-2018 Andi Dittrich
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
        'enlighterjs-resources-init' => true,
        'enlighterjs-resources-themes' => true,
        'enlighterjs-resources-js' => true,
        'enlighterjs-selector-block' => 'pre.EnlighterJSRAW',
        'enlighterjs-selector-inline' => 'code.EnlighterJSRAW',
        'enlighterjs-theme' => 'enlighter',
        'enlighterjs-language' => 'generic',
        'enlighterjs-linehover' => true,
        'enlighterjs-linenumbers' => true,
        'enlighterjs-indent' => 4,
        'enlighterjs-ampersandcleanup' => true,
        'enlighterjs-rawcodedbclick' => false,
        'toolbar-visibility' => 'default',
        'toolbar-button-raw' => true,
        'toolbar-button-copy' => true,
        'toolbar-button-window' => true,
        'shortcode-mode' => 'modern',
        'shortcode-type-generic' => true,
        'shortcode-type-language' => true,
        'tinymce-backend' => true,
        'tinymce-frontend' => true,
        'cache-custom' => false,
        'cache-path' => '',
        'cache-url' => '',
        'dynamic-resource-invocation' => false,
        'cryptex' => false,
        'cryptex-email-fallback' => 'mail@example.tld',
        'jetpack-infinite-scroll' => false,
        'bbpress-shortcode' => false,
        'bbpress-markdown' => false
    );

    // validation
    private $_validators = array(
        'translation-enabled' => 'boolean',
        'enlighterjs-resources-init' => 'boolean',
        'enlighterjs-resources-themes' => 'boolean',
        'enlighterjs-resources-js' => 'boolean',
        'enlighterjs-selector-block' => 'string',
        'enlighterjs-selector-inline' => 'string',
        'enlighterjs-theme' => 'string',
        'enlighterjs-language' => 'string',
        'enlighterjs-linehover' => 'boolean',
        'enlighterjs-linenumbers' => 'boolean',
        'enlighterjs-indent' => 'int',
        'enlighterjs-ampersandcleanup' => 'boolean',
        'enlighterjs-rawcodedbclick' => 'boolean',
        'toolbar-visibility' => 'string',
        'toolbar-button-raw' => 'boolean',
        'toolbar-button-copy' => 'boolean',
        'toolbar-button-window' => 'boolean',
        'shortcode-mode' => 'string',
        'shortcode-type-generic' => 'boolean',
        'shortcode-type-language' => 'boolean',
        'tinymce-backend' => 'boolean',
        'tinymce-frontend' => 'boolean',
        'cache-custom' => 'boolean',
        'cache-path' => 'string',
        'cache-url' => 'string',
        'dynamic-resource-invocation' => 'boolean',
        'cryptex' => 'boolean',
        'cryptex-email-fallback' => 'string',
        'jetpack-infinite-scroll' => 'boolean',
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