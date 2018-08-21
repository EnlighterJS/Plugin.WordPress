<?php

namespace Enlighter;

class CompatibilityModeFilter{
    
    // default fallback language
    private $_defaultLanguage;

    // cached code content
    private $_codeFragments = array();

    public function __construct($settingsUtil){
        $this->_defaultLanguage = $settingsUtil->getOption('compatDefaultLanguage');
    }
    
    private function getCompatRegex(){
        // opening tag, language identifier (optional)
        return '/<pre><code(?:\s+class="([a-z]+)")?>' .

        // arbitrary multi-line content
        '([\S\s]*)' .

        // closing tags
        '\s*<\/code>\s*<\/pre>\s*' .

        // ungreedy, case insensitive, multiline
        '/Uim';
    }


    // strip the content
    // internal regex function to replace gfm code sections with placeholders
    public function stripCodeFragments($content){

        // PHP 5.3 compatibility
        $T = $this;

        // Block Code
        return preg_replace_callback($this->getCompatRegex(), function ($match) use ($T){

            // language identifier (tagname)
            $lang = $match[1];

            // language given ? otherwise use default highlighting method
            if (strlen($lang) == 0){
                $lang = $T->_defaultLanguage;
            }

            // generate code fragment
            $T->_codeFragments[] = array(
                // the language identifier
                'lang' => $lang,

                // code to highlight
                'code' => $match[2]
            );

            // replace it with a placeholder
            return '{{EJS2-' . count($T->_codeFragments) . '}}';
        }, $content);
    }


    // internal handler to insert the content
    public function renderFragments($content){

        // replace placeholders by html
        foreach ($this->_codeFragments as $index => $fragment){
            // html tag standard attributes
            $htmlAttributes = array(
                'data-enlighter-language' => InputFilter::filterLanguage($fragment['lang']),
                'class' => 'EnlighterJSRAW'
            );

            // generate html output
            $html = $this->generateCodeblock($htmlAttributes, $fragment['code']);

            // replace placeholder with rendered content
            $content = str_replace('{{EJS2-' . ($index + 1) . '}}', $html, $content);
        }

        return $content;
    }


    // Generate HTML output (code within "pre"/"code"-tag including options)
    private function generateCodeblock($attributes, $content, $tagname = 'pre'){
        // generate "pre" wrapped html output
        $html = HtmlUtil::generateTag($tagname, $attributes, false);

        // strip special-chars
        $content = esc_html($content);

        // add closing tag
        return $html.$content.'</'.$tagname.'>';
    }

}