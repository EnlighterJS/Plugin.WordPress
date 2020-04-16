<?php

namespace Enlighter\customizer;

class Toolbar{

    // toolbar styles
    public static function customize($config, $css){

        // toolbar visibility
        switch ($config['toolbar-visibility']){
            case 'show':
                $css->add('.enlighter-default .enlighter-toolbar', array(
                    'display' => 'block !important'
                ));
                break;

            case 'hide':
                $css->add('.enlighter-default .enlighter-toolbar', array(
                    'display' => 'none !important'
                ));
                break;
        }

        // button visibility (default => all visible)

        // RAW code button
        if (!$config['toolbar-button-raw']){
            $css->add('.enlighter-default .enlighter-btn-raw', array(
                'display' => 'none'
            ));
        }

        // new window button
        if (!$config['toolbar-button-window']){
            $css->add('.enlighter-default .enlighter-btn-window', array(
                'display' => 'none'
            ));
        }

        // copy to clipboard button
        if (!$config['toolbar-button-copy']){
            $css->add('.enlighter-default .enlighter-btn-copy', array(
                'display' => 'none'
            ));
        }

        // enlighterjs webseite button
        if (!$config['toolbar-button-enlighterjs']){
            $css->add('.enlighter-default .enlighter-btn-website', array(
                'display' => 'none'
            ));
        }
    }
}