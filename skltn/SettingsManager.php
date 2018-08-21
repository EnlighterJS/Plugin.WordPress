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

// Manages set plugin settings/options

namespace Enlighter\skltn;

class SettingsManager{
    
    // local config storage
    private $_config = array();

    // default values
    private $_defaultConfig = array();

    // validators
    private $_configValidators = array();
    
    // initialize global plugin config
    public function __construct($pluginConfig){
        // store default config
        $this->_defaultConfig = $pluginConfig->getDefaults();

        // store validators
        $this->_configValidators = $pluginConfig->getValidators();

        // retrieve config - add default key/values
        $this->_config = array_merge($this->_defaultConfig, get_option('enlighter-options', array()));
    }
    
    // register settings
    public function registerSettings(){
        register_setting('enlighter-settings-group', 'enlighter-options', array($this, 'validateSettings'));
    }

    // sanitize callback
    public function validateSettings($settings){

        // is array ? if not invalid data is passed to the function, use secure default values!
        if (!is_array($settings)){
            return $this->_defaultConfig;
        }

        // new values
        $filteredValues = array();

        // filter values
        foreach ($this->_defaultConfig as $key => $value){

            // key exists ?
            if (!isset($settings[$key])){
                // use default value
                $filteredValues[$key] = $value;
                continue;
            }

            // extract value
            $v = $settings[$key];

            // invalid value ? only scalar option types are supported
            if (!is_scalar($v)){
                // use defaults
                $filteredValues[$key] = $value;
                continue;
            }

            // strip whitespaces in case it is a string value
            if (is_string($v)){
                $v = trim($v);
            }

            // validator available ?
            if (!isset($this->_configValidators[$key])){
                // just assign it as string
                $filteredValues[$key] = trim($v . '');
            }

            // get validator type
            $validator = $this->_configValidators[$key];

            // boolean value ?
            if ($validator == 'boolean'){
                $filteredValues[$key] = ($v === '1' || $v === true);

            // numeric int value
            }else if ($validator == 'int'){
                if (is_numeric($v)){
                    $filteredValues[$key] = intval($v);

                    // default value
                }else{
                    $filteredValues[$key] = $value;
                }

            // numeric float value
            }else if ($validator == 'float'){
                if (is_numeric($v)){
                    $filteredValues[$key] = floatval($v);

                // default value
                }else{
                    $filteredValues[$key] = $value;
                }

            // default: string
            }else{
                $filteredValues[$key] = trim($v . '');
            }
        }

        return $filteredValues;
    }
 
    // update a single option
    public function setOption($key, $value){
        // config key exists (is in default config ?)
        if (isset($this->_config[$key])){
            // assign new value
            $this->_config[$key] = $value;

            // update config, apply prior validation
            update_option('enlighter-options', $this->_config);
        }
    }

    // update a set of options
    public function setOptions($values){
        foreach ($values as $key => $value){
            // config key exists (is in default config ?)
            if (isset($this->_config[$key])){
                // assign new value
                $this->_config[$key] = $value;
            }
        }

        // update config, apply prior validation
        update_option('enlighter-options', $this->_config);
    }
    
    // fetch option by key
    public function getOption($key){
        return $this->_config[$key];
    }
    
    // fetch all plugin options as array
    public function getOptions(){
        return $this->_config;
    }
}
