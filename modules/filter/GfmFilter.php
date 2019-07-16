<?php

namespace Enlighter\filter;

use Enlighter\skltn\HtmlUtil;

class GfmFilter{

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

    private function getGfmRegex(){
        // opening tag
        return '/^```' .

        // language identifier (optional)
        '([a-z]+)?' .

        // EOL
        '\s*$' .

        // arbitrary multi-line content
        '([\S\s]*)' .

        // closing tag
        '^```\s*$' .

        // ungreedy, case insensitive, multiline
        '/Uim';
    }

    private function getInlineGfmRegex(){
        // opening tag
        return '/`' .

        // arbitrary single-line content
        '([^`]*)' .

        // closing tag
        '`'.
        
        // optional language identifier (non gfm standard)
        '(?:([a-z]+)\b)?' .

        // ungreedy, case insensitive
        '/i';
    }

    // strip the content
    // internal regex function to replace gfm code sections with placeholders
    public function stripCodeFragments($content){

        // Block Code
        $content = preg_replace_callback($this->getGfmRegex(), function ($match){

            // language identifier (tagname)
            $lang = $match[1];

            // language given ? otherwise use default highlighting method
            if (strlen($lang) == 0){
                $lang = $this->_config['gfm-language'];
            }

            // generate code
            $code = $this->renderFragment($match[2], $lang, false);

            // generate code; retrieve placeholder
            return $this->_fragmentBuffer->storeFragment($code);

        }, $content);

        // Inline Code
        if ($this->_config['gfm-inline']){
            $content = preg_replace_callback($this->getInlineGfmRegex(), function ($match){

                // default language
                $lang = $this->_config['gfm-language'];

                // language given ? otherwise use default highlighting method
                if (isset($match[2]) && strlen($match[2]) > 0){
                    $lang = $match[2];
                }

                // generate code
                $code = $this->renderFragment($match[1], $lang, true);

                // generate code; retrieve placeholder
                return $this->_fragmentBuffer->storeFragment($code);

            }, $content);
        }

        return $content;
    }

    // internal handler to insert the content
    public function renderFragment($code, $lang, $isInline=false){

        // html tag standard attributes
        $htmlAttributes = array(
            'data-enlighter-language' => InputFilter::filterLanguage($lang),
            'class' => 'EnlighterJSRAW'
        );

        // generate html output
        return HtmlUtil::generateTag(($isInline ? 'code' : 'pre'), $htmlAttributes, true, $code);
    }
}