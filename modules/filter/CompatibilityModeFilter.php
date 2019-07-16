<?php

namespace Enlighter\filter;

use Enlighter\skltn\HtmlUtil;
use Enlighter\compatibility\Crayon as CrayonCompat;

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
    
    // used by e.g. JetPack markdown
    private function getCompatRegexType1(){
        // opening tag, language identifier (optional)
        return '/<pre><code(?:\s+class="([a-z]+)")?>' .

        // arbitrary multi-line content
        '([\S\s]*)' .

        // closing tags
        '\s*<\/code>\s*<\/pre>\s*' .

        // ungreedy, case insensitive, multiline
        '/Uim';
    }
    
    // used by e.g. Gutenberg Codeblock
    private function getCompatRegexType2(){
        // opening tag - no language identifier
        return '/<pre(?:[^>]+?)?><code>' .

        // arbitrary multi-line content
        '([\S\s]*)' .

        // closing tags
        '\s*<\/code>\s*<\/pre>\s*' .

        // ungreedy, case insensitive, multiline
        '/Uim';
    }

    /*
           // language identifier (tagname)
                $lang = $match[1];
    
                // language given ? otherwise use default highlighting method
                if (strlen($lang) == 0){
                    $lang = $T->_defaultLanguage;
                }
    
                // generate code
                $code = $this->renderFragment($match[2], $lang, $attb);
                */



    // strip the content
    // internal regex function to replace gfm code sections with placeholders
    public function stripCodeFragments($content){

        // crayon compat mode ?
        if ($this->_config['compat-crayon']){
            $content = preg_replace_callback(CrayonCompat::getRegex(), function($match){

                // run convert
                $code = CrayonCompat::convert($match);

                // generate code; retrieve placeholder
                return $this->_fragmentBuffer->storeFragment($code);
                
            }, $content);
        }

        // generic compat mode


        return $content;
    }




}