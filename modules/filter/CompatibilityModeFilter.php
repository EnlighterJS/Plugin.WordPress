<?php

namespace Enlighter\filter;

use Enlighter\skltn\HtmlUtil;
use Enlighter\compatibility\Crayon as CrayonCompat;
use Enlighter\compatibility\CodeColorer as CodeColorerCompat;
use Enlighter\compatibility\GenericType1 as GenericType1Compat;
use Enlighter\compatibility\GenericType2 as GenericType2Compat;

class CompatibilityModeFilter{
    // stores the plugin config
    private $_config;
    
    // internal fragment buffer to store code
    private $_fragmentBuffer;

    public function __construct($config, $fragmentBuffer){
        // store local plugin config
        $this->_config = $config;

        // store fragment buffer
        $this->_fragmentBuffer = $fragmentBuffer;
    }

    // strip the content
    // internal regex function to replace gfm code sections with placeholders
    public function stripCodeFragments($content){

        // generic type1 mode ?
        if ($this->_config['compat-type1']){
            $content = preg_replace_callback(GenericType1Compat::getRegex(), function($match){

                // run convert
                $code = GenericType1Compat::convert($match);

                // generate code; retrieve placeholder
                return $this->_fragmentBuffer->storeFragment($code);
                
            }, $content);
        }

        // generic type1 mode ?
        if ($this->_config['compat-type2']){
            $content = preg_replace_callback(GenericType2Compat::getRegex(), function($match){

                // run convert
                $code = GenericType2Compat::convert($match);

                // generate code; retrieve placeholder
                return $this->_fragmentBuffer->storeFragment($code);
                
            }, $content);
        }

        // crayon compat mode ?
        if ($this->_config['compat-crayon']){
            $content = preg_replace_callback(CrayonCompat::getRegex(), function($match){

                // run convert
                $code = CrayonCompat::convert($match);

                // generate code; retrieve placeholder
                return $this->_fragmentBuffer->storeFragment($code);
                
            }, $content);
        }

        // code colorer compat mode ?
        if ($this->_config['compat-codecolorer']){
            $content = preg_replace_callback(CodeColorerCompat::getRegex(), function($match){

                // run convert
                $code = CodeColorerCompat::convert($match);

                // generate code; retrieve placeholder
                return $this->_fragmentBuffer->storeFragment($code);
                
            }, $content);
        }

        return $content;
    }




}