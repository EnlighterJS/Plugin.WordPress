<?php

namespace Enlighter\upgrade;

use Enlighter\skltn\SettingsManager;

class Upgrade_to_v4{

    private $_settings;

    public function __construct(){
        // initialize settings manager
        $this->_settings = new SettingsManager();
    }

    private function getBoolean($key, $default=false){
        $result = get_option('enlighter-' . $key, $default);
        return $result=='1';
    }

    private function getString($key, $default=''){
        $result = get_option('enlighter-' . $key, $default);
        return is_string($result) ? $result : $default;
    }

    private function getNumber($key, $default=0){
        $result = get_option('enlighter-' . $key, $default);
        return preg_match('/^\d+$/', $result) ? intval($result) : $default;
    }

    public function run($currentVersion, $newVersion){

        // assign legacy options
        $opt = array(
            'enlighterjs-assets-js'                 => $this->getBoolean('embedEnlighterJS', true),
            'enlighterjs-assets-themes'             => $this->getBoolean('embedEnlighterCSS', true),
            'enlighterjs-assets-themes-external'    => $this->getBoolean('embedExternalThemes', false),
            'enlighterjs-selector-block'            => $this->getString('selector', 'pre.EnlighterJSRAW'),
            'enlighterjs-selector-inline'           => $this->getString('selectorInline', 'code.EnlighterJSRAW'),
            'enlighterjs-rawcodedbclick'            => $this->getBoolean('rawcodeDoubleclick', false),
            'enlighterjs-linenumbers'               => $this->getBoolean('linenumbers', true),
            'enlighterjs-indent'                    => $this->getNumber('indent', 4),
            'enlighterjs-theme'                     => $this->getString('defaultTheme', 'enlighter'),
            'enlighterjs-language'                  => $this->getString('defaultLanguage', 'enlighter'),
            'dynamic-resource-invocation'           => $this->getBoolean('dynamicResourceInvocation', false),
            'ext-infinite-scroll'                   => $this->getBoolean('extJetpackInfiniteScroll', false),
            'ext-ajaxcomplete'                      => $this->getBoolean('extJQueryAjax', false),
            'bbpress-shortcode'                     => $this->getBoolean('bbpressShortcode', false),
            'bbpress-markdown'                      => $this->getBoolean('bbpressMarkdown', false)    
        );
        
        // merge+store options
        $this->_settings->setOptions($opt);
    }

}