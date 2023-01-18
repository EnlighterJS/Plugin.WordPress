<?php

namespace Enlighter\filter;

class FragmentBuffer{

    // cached code content
    private $_codeFragments = array();

    public function __construct(){
    }

    // store code fragment within buffer
    public function storeFragment($code, $inlineCode=false){
        // push code on top of buffer
        $this->_codeFragments[] = $code;

        // wrapper tag
        $wrapper = ($inlineCode ? 'code' : 'pre');

        // get index of the top element
        return '<'.$wrapper.'>{{EJS' . (count($this->_codeFragments)-1) . '}}</'.$wrapper.'>';
    }

    // add filter to content section to restore the fragments
    public function registerRestoreFilter($name){
        // add restore filter to the end of filter chain - placeholders are replaced with rendered html
        add_filter($name, array($this, 'renderFragments'), 9998, 1);
    }

    // internal handler to insert the content
    public function renderFragments($content){

        // search for enlighter placeholders
        return preg_replace_callback('/<(pre|code)>{{EJS(\d+)}}<\/\1>/U', function($match){

            // get fragment id
            $fragmentID = intval($match[2]);

            // fragment exists ?
            if (isset($this->_codeFragments[$fragmentID])){
                return $this->_codeFragments[$fragmentID];
            }else{
                return 'ENLIGHTER::INVALID_CODE_FRAGMENT';
            }

        }, $content);
    }
}