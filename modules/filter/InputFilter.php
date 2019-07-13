<?php

namespace Enlighter\filter;

class InputFilter{


    public static function filterTheme($v){
        return preg_replace('/[^a-z0-9]/', '', strtolower($v));
    }

    public static function filterLanguage($v){
        return preg_replace('/[^a-z0-9]/', '', strtolower($v));
    }

    public static function filterInteger($v){
        return intval($v);
    }

}