<?php

namespace Enlighter\editor;

use \Enlighter\skltn\ResourceManager as ResourceManager;

class Gutenberg{

    public function integrate(){
        // gutenberg plugin - https://github.com/EnlighterJS/Plugin.Gutenberg
        ResourceManager::enqueueScript(
            'enlighter-gutenberg', 
            'gutenberg/enlighterjs.gutenberg.min.js', 
            array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components')
        );
        ResourceManager::enqueueStyle(
            'enlighter-gutenberg', 
            'gutenberg/enlighterjs.gutenberg.min.css', 
            array()
        );
    }
}


