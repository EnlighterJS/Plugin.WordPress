<?php

namespace Enlighter;

class Fonts{
    
    function enqueue(){
        // list of google webfonts to load
        $webfontList = array();

        // get all available webfonts
        $webfonts = GoogleWebfontResources::getMonospaceFonts();

        // load enabled fonts
        foreach ($webfonts as $name => $font){
            $fid = preg_replace('/[^A-Za-z0-9]/', '', $name);
            if ($this->_config['webfonts'.$fid]){
                $webfontList[] = $font;
            }
        }

        // load webfonts ?
        if (count($webfontList) > 0){
            $this->enqueueStyle('enlighter-webfonts', '//fonts.googleapis.com/css?family=' . implode('|', $webfontList));
        }
    }
}