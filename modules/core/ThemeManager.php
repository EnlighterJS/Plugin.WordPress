<?php

namespace Enlighter;

class ThemeManager{
    
    private $_cachedData = null;

    // list of build-in themes
    private static $_supportedThemes = array(
        'Enlighter' => 'enlighter',
        'Godzilla' => 'godzilla',
        'Beyond' => 'beyond',
        'Classic' => 'classic',
        'MooTwo' => 'mowtoo',
        'Eclipse' => 'eclipse',
        'Droide' => 'droide',
        'Minimal' => 'minimal',
        'Atomic' => 'atomic',
        'Rowhammer' => 'rowhammer',
        'Bootstrap4' => 'boootstrap4',
        'Dracula'=> 'dracula',
        'Monokai' => 'monokai'
    );

    public function __construct(){
        // try to load cached data
        $this->_cachedData = get_transient('enlighter_userthemes');
    }
    
    // drop cache content and remove cache file
    public function forceReload(){
        //$this->_cache->clear();
        delete_transient('enlighter_userthemes');
    }

    // get a list of all available themes
    public function getThemes(){
        return $this->getThemeList();
    }

    // fetch the build-in theme list (EnlighterJS)
    public function getBuildInThemes(){
        return self::$_supportedThemes;
    }

    // get a list of all available themes (build-in + user)
    public function getThemeList(){
        // generate the theme list
        $themes = array(
            'WPCustom' => 'wpcustom'
        );

        // add build-in themes
        foreach (self::$_supportedThemes as $t => $v){
            $themes[$t] = strtolower($t);
        }

        // add external user themes with prefix
        foreach ($this->getUserThemes() as $t => $source){
            $themes[$t.'/ext'] = strtolower($t);
        }

        // run filter to enable user specific themes
        return apply_filters('enlighter_themes', $themes);
    }

    // fetch user themes
    // Enlighter Themes which are stored a directory named `enlighter/` of the current active theme
    public function getUserThemes(){
        // cached data available ?
        if ($this->_cachedData === false){
            // get template directories
            $childDir = get_stylesheet_directory();
            $themeDir = get_template_directory();
            
            // load enlighter-themes from current theme
            $themeFiles = $this->getCssFilesFromDirectory($themeDir, get_template_directory_uri());
            
            // load enlighter-themes from current child-theme (if used)
            if ($childDir != $themeDir){
                $themeFiles = array_merge($themeFiles, $this->getCssFilesFromDirectory($childDir, get_stylesheet_directory_uri()));
            }
            
            // store data; 1day cache expire
            set_transient('enlighter_userthemes', $themeFiles, DAY_IN_SECONDS);
            $this->_cachedData = $themeFiles;
        }

        return $this->_cachedData;
    }
    
    private function getCssFilesFromDirectory($dir, $uri){
        // enlighter subdirectory
        $dir .= '/enlighter/';
        $uri .= '/enlighter/';
        
        // available ?
        if (!is_dir($dir)){
            return array();
        }
        
        // get all files of the selected directory
        $files = scandir($dir);
        
        // theme buffers
        $themes = array();
        
        // filter css files
        foreach($files as $file){
            if ($file != '.' && $file != '..'){
                if (substr($file, -3) == 'css'){
                    // absolute path + uri for external themes
                    $themes[basename($file, '.css')] = array(
                            $dir.$file,
                            $uri.$file
                    );
                }
            }
        }
        
        return $themes;
    }
    
}