<?php

namespace Enlighter\editor;

use \Enlighter\skltn\ResourceManager as ResourceManager;

class QuickTags{

    public function integrate(){
        // text editor plugin
        ResourceManager::enqueueScript('enlighter-quicktags', 'texteditor/QuickTags.js', array('jquery'));
    }
}