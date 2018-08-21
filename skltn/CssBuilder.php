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

// Generate Dynamic CSS Files

namespace Enlighter\skltn;

class CssBuilder{
    
    // cached css ruleset
    private $_buffer = array();

    // cached raw css
    private $_rawBuffer = '';
    
    public function __construct(){
    }

    // add new ruleset
    public function add($selector, $rules = array()){
        // selector already used ?
        if (isset($this->_buffer[$selector])){
            // merge
            $this->_buffer[$selector] = array_merge($this->_buffer[$selector], $rules);
        }else{
            $this->_buffer[$selector] = $rules;
        }
    }

    // add raw css
    public function addRaw($css){
        $this->_rawBuffer .= "\n" . $css;
    }

    // render ruleset
    public function render(){
        // local output buffer
        $css = '/* Enlighter dynamic generated stylesheet - DO NOT EDIT */';

        // get rulesets
        foreach ($this->_buffer as $selector => $rules){
            // new ruleset
            $css .= "\n" . $selector . '{';

            // process rules
            foreach ($rules as $prop => $value){
                $css .= $prop . ':' . $value . ';';
            }

            // close ruleset
            $css .= '}';
        }

        // add raw css
        $css .= $this->_rawBuffer;

        return $css;
    }
    
}