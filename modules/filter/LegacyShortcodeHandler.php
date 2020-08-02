<?php

namespace Enlighter\filter;

use Enlighter\skltn\HtmlUtil;

class LegacyShortcodeHandler{
    
    // stores the plugin config
    private $_config;
    
    // store registered shortcodes
    private $_registeredShortcodes = array();
    
    // currently active codegroup
    private $_activeCodegroup = null;

    public function __construct($config, $languageManager){
        // store local plugin config
        $this->_config = $config;

        // add texturize filter
        add_filter('no_texturize_shortcodes', array($this, 'texturizeHandler'));

        // enable generic shortcode ?
        if ($this->_config['shortcode-type-generic']){
            add_shortcode('enlighter', array($this, 'genericShortcodeHandler'));
            $this->_registeredShortcodes[] = 'enlighter';
        }

        // enable codegroup shortcode ?
        if ($this->_config['shortcode-type-group']){
            add_shortcode('codegroup', array($this, 'codegroupShortcodeHandler'));
            $this->_registeredShortcodes[] = 'codegroup';
        }

        // enable language shortcodes ?
        if ($this->_config['shortcode-type-language']){
            foreach ($languageManager->getLanguages() as $lang => $name){
                add_shortcode($lang, array($this, 'microShortcodeHandler'));
                $this->_registeredShortcodes[] = $lang;
            }
        }
    }
    
    // handle codegroups
    public function codegroupShortcodeHandler($shortcodeAttributes=NULL, $content='', $tagname=''){
        // default attribute settings
        $shortcodeAttributes = shortcode_atts(
                array(
                    'theme' => $this->_config['enlighterjs-theme']
                ), $shortcodeAttributes);
    
        // html "pre"-tag attributes
        $htmlAttributes = array(
                'data-enlighter-theme' => $shortcodeAttributes['theme'],
                'class' => 'EnlighterJSRAW',
                'data-enlighter-group' => uniqid()
        );
    
        // assign html attrivutes
        $this->_activeCodegroup = $htmlAttributes;
    
        // call micro shortcode handlers
        $content = do_shortcode($content);
        
        // remove automatic generated html editor tags (from wpautop())
        $content = $this->removeWpAutoP($content);
        
        // disable group flag
        $this->_activeCodegroup = NULL;
        
        // return parsed content
        return $content;
    }
    
    // handle micro shortcode [php,js ..] ...
    public function microShortcodeHandler($shortcodeAttributes=NULL, $content='', $tagname=''){
        // default attribute settings
        $shortcodeAttributes = shortcode_atts(
                array(
                        'theme' => null,
                        'group' => false,
                        'tab' => null,
                        'highlight' => null,
                        'offset' => null,
                        'linenumbers' => null
                ), $shortcodeAttributes);

        // html tag standard attributes
        $htmlAttributes = array(
                'data-enlighter-language' => trim($tagname),
                'class' => 'EnlighterJSRAW'
        );
        
        // force theme ?
        if ($shortcodeAttributes['theme']){
            $htmlAttributes['data-enlighter-theme'] = trim($shortcodeAttributes['theme']);
        }
                
        // handle as inline code ?
        if ($this->_config['shortcode-inline'] && strpos($content, "\n") === false){
            // generate html output
            return $this->generateCodeblock($htmlAttributes, $content, 'code');
            
        // linebreaks found -> block code    
        }else{
            // highlight specific lines of code ?
            if ($shortcodeAttributes['highlight']){
                $htmlAttributes['data-enlighter-highlight'] = trim($shortcodeAttributes['highlight']);
            }
            
            // line offset ?
            if ($shortcodeAttributes['offset']){
                $htmlAttributes['data-enlighter-lineoffset'] = intval($shortcodeAttributes['offset']);
            }
            
            // force linenumber visibility ?
            if ($shortcodeAttributes['linenumbers']){
                $htmlAttributes['data-enlighter-linenumbers'] = (strtolower($shortcodeAttributes['linenumbers']) === 'true' ? 'true' : 'false');
            }
            
            // tab-name available ?
            if ($shortcodeAttributes['tab']){
                $htmlAttributes['data-enlighter-title'] = trim($shortcodeAttributes['tab']);
            }
            
            // codegroup active ?
            if ($this->_activeCodegroup !== NULL){
                // overwrite settings
                $htmlAttributes['data-enlighter-group'] = $this->_activeCodegroup['data-enlighter-group'];
            }else{
                // manual grouping ?
                if ($shortcodeAttributes['group']){
                    $htmlAttributes['data-enlighter-group'] = trim($shortcodeAttributes['group']);
                }
            }
            
            // generate html output
            return $this->generateCodeblock($htmlAttributes, $content);
        }
    }
    
    // handle wp shortcode [enlighter ..] ... [/enlighter] - generic handling
    public function genericShortcodeHandler($shortcodeAttributes=NULL, $content='', $tagname=''){
        // default language
        $language = (isset($shortcodeAttributes['lang']) ? $shortcodeAttributes['lang'] : 'generic');
    
        // run micro shortcode handler with given language key
        return $this->microShortcodeHandler($shortcodeAttributes, $content, $language);
    }
    
    // Generate HTML output (code within "pre"/"code"-tag including options)
    private function generateCodeblock($attributes, $content, $tagname = 'pre'){
        
        // remove automatic generated html editor tags (from wpautop())
        $content = $this->removeWpAutoP($content);
   
        // add closing tag
        return HtmlUtil::generateTag($tagname, $attributes, false, $content);
    }

    // Removes wordpress auto-texturize handler from used shortcodes
    public function texturizeHandler($shortcodes) {
        return array_merge($shortcodes, $this->_registeredShortcodes);
    }
    
    // Removes automatic generated html editor tags (from wpautop()) and restores linebreaks
    private function removeWpAutoP($content){
        // fallback: remove added tags - will work on most cases
        return str_replace(array('<br />', '<p>', '</p>'), array('', '', "\n"), $content);
    }

}