<?php

namespace Enlighter\filter;

use Enlighter\skltn\HtmlUtil;

class ShortcodeFilter{
    
    // stores the plugin config
    private $_config;
    
    // store registered shortcodes
    private $_languageTags = array();

    // internal fragment buffer to store code
    private $_fragmentBuffer;

    public function __construct($config, $languageManager, $fragmentBuffer){
        // store local plugin config
        $this->_config = $config;

        // store fragment buffer
        $this->_fragmentBuffer = $fragmentBuffer;

        // default shortcodes
        if ($this->_config['shortcode-type-generic']){
            $this->_languageTags[] = 'enlighter';
        }

        // enable language shortcodes ?
        if ($this->_config['shortcode-type-language']){
            $this->_languageTags = array_merge($this->_languageTags, array_keys($languageManager->getLanguages()));
        }
    }

    private function getShortcodeRegex($shortcodes){
        // opening tag based on enabled shortcodes
        return '/\[(' . implode('|', $shortcodes) . ')' .

        // shortcode attributes (optional), separated by spaces
        '( .*)?' .

        // close opening tag
        '\s*\]' .

        // arbitrary multi-line content
        '([\S\s]*)' .

        // closing tag using opening tag back reference
        '\[\/\1\]' .

        // ungreedy, case insensitive
        '/Ui';
    }

        // strip the content
    // internal shortcode processor - replace enlighter shortcodes by placeholder
    public function stripCodeFragments($content){

        // STAGE 1 - codegroups
        if ($this->_config['shortcode-type-group']){
            // process codegroup shortcodes
            $content = preg_replace_callback($this->getShortcodeRegex(array('codegroup')), function ($match){

                // create unique group identifier
                $group = uniqid('ejsg');

                // replace it with inner content an parse the shortcodes
                return $this->findShortcodes($match[3], $group);
            }, $content);
        }

        // STAGE 2 - shortcodes
        // regular, process non grouped shortcodes
        $content = $this->findShortcodes($content);

        return $content;
    }

    // internal regex function to replace shortcodes with placeholders
    // scoped to use it standalone as well as within shortcodes as second stage
    public function findShortcodes($content, $group = null){
        // process enlighter & language shortcodes
        return preg_replace_callback($this->getShortcodeRegex($this->_languageTags), function ($match) use ($group){

            // wordpress internal shortcode attribute parsing
            $attb = shortcode_parse_atts($match[2]);

            // shortcode_parse_atts return empty string in case no attributes were parsed...
            if (is_string($attb)){
                $attb = array();
            }

            // language identifier (tagname)
            $lang = $match[1];

            // generic shortcode ?
            if (strtolower($match[1]) == 'enlighter') {
                // set language
                if (isset($attb['lang'])) {
                    $lang = $attb['lang'];
                }
            }

            // generate code
            $code = $this->renderShortcode($match[3], $lang, $attb, $group);

            // generate code; retrieve placeholder
            return $this->_fragmentBuffer->storeFragment($code);

        }, $content);
    }

    // process shortcode attributes and generate html
    private function renderShortcode($code, $lang, $attb, $group){
        // default attribute settings
        $shortcodeAttributes = shortcode_atts(
                array(
                        'theme' => null,
                        'group' => false,
                        'tab' => null,
                        'highlight' => null,
                        'offset' => null,
                        'linenumbers' => null
                ), $attb);

        // html tag standard attributes
        $htmlAttributes = array(
                'data-enlighter-language' => InputFilter::filterLanguage($lang),
                'class' => 'EnlighterJSRAW'
        );
        
        // force theme ?
        if ($shortcodeAttributes['theme']){
            $htmlAttributes['data-enlighter-theme'] = InputFilter::filterTheme($shortcodeAttributes['theme']);
        }
        
        // handle as inline code ?
        if ($this->_config['shortcode-inline'] && strpos($code, "\n") === false){
            // generate html output
            return HtmlUtil::generateTag('code', $htmlAttributes, true, $code);
            
        // line-breaks found -> block code
        }else{
            // highlight specific lines of code ?
            if ($shortcodeAttributes['highlight']){
                $htmlAttributes['data-enlighter-highlight'] = trim($shortcodeAttributes['highlight']);
            }
            
            // line offset ?
            if ($shortcodeAttributes['offset']){
                $htmlAttributes['data-enlighter-lineoffset'] = InputFilter::filterInteger($shortcodeAttributes['offset']);
            }
            
            // force linenumber visibility ?
            if ($shortcodeAttributes['linenumbers']){
                $htmlAttributes['data-enlighter-linenumbers'] = (strtolower($shortcodeAttributes['linenumbers']) === 'true' ? 'true' : 'false');
            }
            
            // tab-name available ?
            if ($shortcodeAttributes['tab']){
                $htmlAttributes['data-enlighter-title'] = trim($shortcodeAttributes['tab']);
            }

            // auto grouping ?
            if ($group){
                $htmlAttributes['data-enlighter-group'] = trim($group);

            // manual grouping ?
            }else if ($shortcodeAttributes['group']){
                $htmlAttributes['data-enlighter-group'] = trim($shortcodeAttributes['group']);
            }
            
            // generate html output
            return HtmlUtil::generateTag('pre', $htmlAttributes, true, $code);
        }
    }
}