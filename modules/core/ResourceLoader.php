<?php

namespace Enlighter;

use \Enlighter\skltn\ResourceManager as ResourceManager;
use \Enlighter\editor\EditorConfig;
use \Enlighter\editor\Gutenberg as GutenbergEditor;
use \Enlighter\editor\TinyMCE as TinyMCEEditor;
use \Enlighter\editor\QuickTags as QuickTagsEditor;
use \Enlighter\extensions\Jetpack as JetpackExtension;
use \Enlighter\extensions\JQuery as JQueryExtension;
use \Enlighter\DynamicResourceInvocation;

class ResourceLoader{
    
    // stores the plugin config
    private $_config;
    
    // tinymce editor
    private $_tinymce;

    // gutenberg editor
    private $_gutenbergEditor;

    // quicktag editor support
    private $_quicktagsEditor;

    // enlighterjs resource manager
    private $_enlighterjs;

    // editor config
    private $_editorConfig;

    // theme manager (build in + user themes)
    protected $_themeManager;

    // language manager (build-in)
    protected $_languageManager;

    // theme customizer
    private $_themeCustomizer;

    public function __construct($settingsUtil, $cacheManager, $languageManager, $themeManager, $fontManager, $themeCustomizer){
        // store local plugin config
        $this->_config = $settingsUtil->getOptions();

        // store language keys
        $this->_languageManager = $languageManager;

        // local theme manager instance (required for external themes)
        $this->_themeManager = $themeManager;

        // local theme customizer instance
        $this->_themeCustomizer = $themeCustomizer;

        // visual editor integration
        $this->_tinymce = new TinyMCEEditor($this->_config, $cacheManager, $languageManager, $fontManager);

        // gutenberg editor integration
        $this->_gutenbergEditor = new GutenbergEditor();

        // quicktags integration
        $this->_quicktagsEditor = new QuickTagsEditor();

        // enlighterjs resource initializtion
        $this->_enlighterjs = new EnlighterJS($this->_config, $cacheManager, $themeCustomizer);

        // editor config generator
        $this->_editorConfig = new EditorConfig($this->_config, $this->_enlighterjs, $languageManager, $themeManager);
    }

    // Load the Frontend Editor Resources
    public function frontendEditor(){
        // flag
        $editorLoaded = false;

        // apply editor modifications
        if ($this->_config['tinymce-frontend']){
            $this->_tinymce->integrate();
            $editorLoaded = true;
        }

        // load text editor ?
        if ($this->_config['quicktag-frontend']){
            add_action('wp_enqueue_scripts', array($this->_quicktagsEditor, 'integrate'));
            $editorLoaded = true;
        }

        if ($editorLoaded){
            // add config object to header to avoid missing dependencies
            ResourceManager::enqueueDynamicScript($this->_editorConfig->getEditorConfigCode(), null, 'header');
        }
    }

    // Load the Backend Editor Resources
    public function backendEditor(){
        // flag
        $editorLoaded = false;

        // apply editor modifications
        if ($this->_config['tinymce-backend']){
            $this->_tinymce->integrate();
            $editorLoaded = true;
        }

        // load text editor ?
        if ($this->_config['quicktag-backend']){
            add_action('admin_enqueue_scripts', array($this->_quicktagsEditor, 'integrate'));
            $editorLoaded = true;
        }

        // load Gutenberg editor plugin ?
        if ($this->_config['gutenberg-backend']){
            add_action('enqueue_block_editor_assets', array($this->_gutenbergEditor, 'integrate'));
            $editorLoaded = true;
        }

        if ($editorLoaded){
            // add config object to header to avoid missing dependencies
            ResourceManager::enqueueDynamicScript($this->_editorConfig->getEditorConfigCode(), null, 'header');
        }
    }

    // Load the Backend Settings Resources
    public function backendSettings(){
        add_action('admin_enqueue_scripts', function(){
            // new UI styles
            ResourceManager::enqueueStyle('enlighter-settings', 'admin/skltn.css');
            
            // settings init script
            ResourceManager::enqueueScript('enlighter-settings', 'admin/skltn.js', array('jquery', 'wp-color-picker'));
        });
    }

    // Load the Backend Theme Customizer
    public function backendSettingsCustomizer(){
        // trigger regular dependencies
        $this->backendSettings();

        // load customizer library
        add_action('admin_enqueue_scripts', array($this->_themeCustomizer, 'enqueue'));
    }

    // initialize the frontend
    public function frontendEnlighter($contentProcessor){

        // dependency for extensions
        $extensionDependency = 'enlighterjs';

        // dynamic resource incovation active ?
        if ($this->_config['dynamic-resource-invocation']){

            // initialize DRI
            $dri = new DynamicResourceInvocation($this->_config, $this->_enlighterjs);

            // load enlighterjs resources loader
            add_action('wp_enqueue_scripts', array($dri, 'enqueue'), 50);

            // disable dependencies
            $extensionDependency = null;
            
        // standard wordpress assets loading via enqueue
        }else{
            // load enlighterjs resources
            add_action('wp_enqueue_scripts', array($this->_enlighterjs, 'enqueue'), 50);
        }

        // load user themes ?
        if ($this->_config['enlighterjs-assets-themes-external']){
            add_action('wp_enqueue_scripts', array($this->_themeManager, 'enqueue'));
        }

        add_action('wp_enqueue_scripts', function() use ($extensionDependency){
            // load infinite scroll extension ?
            if ($this->_config['ext-infinite-scroll']){
                ResourceManager::enqueueDynamicScript(JetpackExtension::getInfiniteScrollCode(), $extensionDependency);
            }

            // load infinite scroll extension ?
            if ($this->_config['ext-ajaxcomplete']){
                ResourceManager::enqueueDynamicScript(JQueryExtension::getAjaxcompleteCode(), $extensionDependency);
            }
        }, 51);

    }
}