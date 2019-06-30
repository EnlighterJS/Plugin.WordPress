<?php

namespace Enlighter;

use \Enlighter\skltn\ResourceManager as ResourceManager;
use \Enlighter\editor\EditorConfig;
use \Enlighter\editor\Gutenberg as GutenbergEditor;
use \Enlighter\editor\TinyMCE as TinyMCEEditor;
use \Enlighter\editor\QuickTags as QuickTagsEditor;

class ResourceLoader{
    
    // stores the plugin config
    private $_config;
    
    // tinymce editor
    private $_tinymce;

    // gutenberg editor
    private $_gutenbergEditor;

    // quicktag editor support
    private $_quicktagsEditor;

    // update hash
    private $_uhash;

    // theme generator
    private $_themeGenerator;

    // enlighterjs resource manager
    private $_enlighterjs;

    // editor config
    private $_editorConfig;

    // theme manager (build in + user themes)
    protected $_themeManager;

    // language manager (build-in)
    protected $_languageManager;

    public function __construct($settingsUtil, $cacheManager, $languageManager, $themeManager, $fontManager){
        // store local plugin config
        $this->_config = $settingsUtil->getOptions();

        // store language keys
        $this->_languageManager = $languageManager;

        // local theme manager instance (required for external themes)
        $this->_themeManager = $themeManager;

        // visual editor integration
        $this->_tinymce = new TinyMCEEditor($this->_config, $cacheManager, $languageManager, $fontManager);

        // gutenberg editor integration
        $this->_gutenbergEditor = new GutenbergEditor();

        // quicktags integration
        $this->_quicktagsEditor = new QuickTagsEditor();

        // get last update hash
        //$this->_uhash = get_option('enlighter-settingsupdate-hash', '0A0B0C');

        // create new js config generator
        //$this->_jsConfigGenerator = new ConfigGenerator($settingsUtil, $cacheManager);

        // create new theme generator instance
        //$this->_themeGenerator = new ThemeGenerator($settingsUtil, $cacheManager);

        // enlighterjs resource initializtion
        $this->_enlighterjs = new EnlighterJS($this->_config);

        // editor config generator
        $this->_editorConfig = new EditorConfig($this->_config, $this->_enlighterjs, $languageManager, $themeManager);
    }

    // Load the Frontend Editor Resources
    public function frontendEditor(){
        // add config object to header to avoid missing dependencies
        ResourceManager::enqueueDynamicScript($this->_editorConfig->getEditorConfigCode(), null, 'header');

        // apply editor modifications
        if ($this->_config['tinymce-frontend']){
            $this->_tinymce->integrate();
        }

        // load text editor ?
        if ($this->_config['quicktag-frontend']){
            add_action('admin_enqueue_scripts', array($this->_quicktagsEditor, 'integrate'));
        }
    }

    // Load the Backend Editor Resources
    public function backendEditor(){
        // add config object to header to avoid missing dependencies
        ResourceManager::enqueueDynamicScript($this->_editorConfig->getEditorConfigCode(), null, 'header');

        // apply editor modifications
        if ($this->_config['tinymce-backend']){
            $this->_tinymce->integrate();
        }

        // load text editor ?
        if ($this->_config['quicktag-backend']){
            add_action('admin_enqueue_scripts', array($this->_quicktagsEditor, 'integrate'));
        }

        // load Gutenberg editor plugin ?
        if ($this->_config['gutenberg-backend']){
            add_action('enqueue_block_editor_assets', array($this->_gutenbergEditor, 'integrate'));
        }
    }

    // Load the Backend Settings Resources
    public function backendSettings(){
        add_action('admin_enqueue_scripts', function(){
            // new UI styles
            ResourceManager::enqueueStyle('enlighter-settings', 'admin/skltn.css');

            // theme data
            //$this->enqueueScript('enlighter-theme s', 'admin/ThemeStyles.js');

            // settings init script
            ResourceManager::enqueueScript('enlighter-settings', 'admin/skltn.js', array('jquery', 'wp-color-picker'));
        });
    }

    // initialzize the frontend
    public function frontendEnlighter(){

        // load enlighterjs resources
        add_action('wp_enqueue_scripts', array($this->_enlighterjs, 'enqueue'), 50);
    }
}