<?php

namespace Enlighter;

use Enlighter\skltn\ResourceManager;

class ThemeManager{
    
    private $_cachedData = null;

    // list of build-in themes
    const THEMES = array(
        'enlighter' => 'Enlighter',
        'godzilla' => 'Godzilla',
        'beyond' => 'Beyond',
        'classic' => 'Classic',
        'mowtwo' => 'MowTwo',
        'eclipse' => 'Eclipse',
        'droide' => 'Droide',
        'minimal' => 'Minimal',
        'atomic' => 'Atomic',
        'rowhammer' => 'Rowhammer',
        'bootstrap4' => 'Bootstrap4',
        'dracula'=> 'Dracula',
        'monokai' => 'Monokai',
        'wpcustom' => 'Theme Customizer'
    );

    public function __construct(){
    }

    // enqueue user themes
    public function enqueue(){
        // embed available external themes
        foreach ($this->getUserThemes() as $theme => $sources) {
            ResourceManager::enqueueStyle('enlighter-theme-' . strtolower($theme), $sources[1], array('enlighterjs'), null);
        }
    }

    // get a list of all available themes (build-in + user)
    public function getThemes(){

        // cached ?
        if ($this->_cachedData !== null){
            return $this->_cachedData;
        }
        
        // generate empty themes list
        $themes = array();

        // add build-in themes
        foreach (self::THEMES as $slug => $name){
            $themes[$slug] = $name;
        }

        // add external user themes with prefix
        foreach ($this->getUserThemes() as $slug => $source){
            $themes[$slug] = $slug.' ('.$source[2].')';
        }

        // run filter to enable user specific themes
        $this->_cachedData = apply_filters('enlighter_themes', $themes);

        return $this->_cachedData;
    }

    // fetch user themes
    // Enlighter Themes which are stored a directory named `enlighter/` of the current active theme
    public function getUserThemes(){

        // user themes in cache ?
        $themeFiles = get_transient('enlighter_userthemes');

        // cached
        if ($themeFiles !== false){
            return $themeFiles;
        }

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

        return $themeFiles;
    }
    
    private function getCssFilesFromDirectory($dir, $uri){
        // enlighter subdirectory
        $dir .= '/enlighter';
        $uri .= '/enlighter';
        
        // available ?
        if (!is_dir($dir)){
            return array();
        }
        
        // get all files of the selected directory
        $files = scandir($dir);
        
        // theme buffers
        $themes = array();

        // source directory name
        $sourcedirName = basename(dirname($dir));
        
        // filter css files
        foreach($files as $file){
            if ($file !== '.' && $file !== '..'){
                if (substr($file, -3) === 'css'){
                    // absolute path + uri for external themes
                    $themes[basename($file, '.css')] = array(
                            $dir.'/'.$file,
                            $uri.'/'.$file,
                            $sourcedirName
                    );
                }
            }
        }
        
        return $themes;
    }

    // drop cache content and remove cache file
    public function clearCache(){
        $this->_cachedData = null;
        delete_transient('enlighter_userthemes');
    }
 
}