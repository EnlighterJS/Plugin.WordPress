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

// Generate Dynamic JS Files

namespace Enlighter\skltn;

class JsBuilder{
    
    // cached raw css
    private $_rawBuffer = '';
    
    public function __construct(){
    }

     // add raw js
    public function addRaw($js){
        $this->_rawBuffer .= $js . "\n";
    }

    // add raw css from file
    public function addFile($filename){
        $this->_rawBuffer .= file_get_contents($filename) . "\n" ;
    }

    // render ruleset
    public function render(){
        // local output buffer
        $header = "/* Enlighter dynamic generated script - DO NOT EDIT */\n";

        return $header . $this->_rawBuffer;
    }
    
}