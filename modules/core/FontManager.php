<?php

namespace Enlighter;

class FontManager{

    private $_cachedData = null;

    // monospace font classes used by EnlighterJS v3 + additional
    const FONTS = array(
        'sourcecodepro'     => '"Source Code Pro", "Liberation Mono", "Courier New", Courier, monospace',
        'consolas'          => '"Consolas", "Liberation Mono", "Courier New", Courier, monospace',
        'inconsolata'       => '"Inconsolata", "Liberation Mono", "Courier New", Courier, monospace',
        'courier'           => '"Courier New", Courier, monospace',
        'lucida'            => '"Lucida Console", Monaco, monospace'
    );

    // fetch font list
    public function getFonts(){
        // cached ?
        if ($this->_cachedData === null){
            $this->_cachedData = apply_filters('enlighter_fonts', self::FONTS);
        }

        return $this->_cachedData;
    }

    // get font class by name
    public function getFontByName($name){
        $fontlist = $this->getFonts();

        // font found
        if (isset($fontlist[$name])){
            return $fontlist[$name];
        
        // get first font
        }else{
            return array_values($fontlist)[0];
        }
    }
}