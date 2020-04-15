<?php
// ---------------------------------------------------------------------------------------------------------------
// -- WP-SKELETON AUTO GENERATED FILE - DO NOT EDIT !!!
// --
// -- Copyright (c) 2016-2020 Andi Dittrich
// -- https://github.com/AndiDittrich/WP-Skeleton
// --
// ---------------------------------------------------------------------------------------------------------------
// --
// -- This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
// -- If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
// --
// ---------------------------------------------------------------------------------------------------------------

// Registers virtual page/content endpoints using WP Rewrite API

namespace Enlighter\skltn;

class VirtualPageManager{

    // list of registered pages
    private $_pages = array();

    // rewrite helper instance to register rules+tags
    private $_rewriteHelper;

    public function __construct($rewriteHelper){
        // store rewrite helper
        $this->_rewriteHelper = $rewriteHelper;

        // hook into template redirects, priority 5
        add_action('template_redirect', array($this, 'handleRequest'), 5);

        // hool into rewrite action
        add_action('enlighter_rewriterules_init', array($this, 'registerRewriteRules'));
    }

    // register rewrite rules
    public function registerRewriteRules(){
        foreach ($this->_pages as $slug => $d){
            // add rewrite rules
            $this->_rewriteHelper->addRewriteRule($d[0], 'index.php?enlighter_' . $slug . '=$matches[1]', 'top');
            $this->_rewriteHelper->addRewriteTag('%enlighter_' . $slug . '%', '([\w_]+)');
        }
    }

    // add a new virtual page
    public function registerPage($slug, $regex, $cb){
        // filter slug
        $slug = trim(strtolower(preg_replace('/[^\w]/', '', $slug)));

        // valid slug ? length value taken from WPRewrite class
        if (strlen($slug) < 3){
            return false;
        }

        // store callback identified by slug
        $this->_pages[$slug] = array($regex, $cb);
    }

    // action disptaching
    public function handleRequest(){

        // find query var
        foreach ($this->_pages as $slug => $d){

            // query var set ?
            if (($match = get_query_var('enlighter_' . $slug, false)) !== false){

                // execute callback
                call_user_func($d[1], $match);

                // stop wp processing
                exit();
            }
        }
    }
    
}