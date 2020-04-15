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

// Filters the rewrite rules to allow an easier manipulation of them especially on plugin activation/deactivation

namespace Enlighter\skltn;

class RewriteRuleHelper{

    // list of rules registerd by this plugin
    private $_rules = array();

    // list of registered tags by this plugin
    private $_tags = array();

    // list of registered rewrite rule filters
    private $_filters = array();

    public function __construct(){
    }

    public function init(){
        // additional rulesets
        do_action('enlighter_rewriterules_init');
    }

    // proxy
    public function addRuleFilter($name, $cb){
        // passthrough
        add_filter($name, $cb, 100);

        // store filter
        $this->_filters[$name] = $cb;
    }

    // proxy
    public function addRewriteTag($tag, $regex, $query=''){
        // passthrough
        add_rewrite_tag($tag, $regex, $query);

        // store tag
        $this->_tags[] = $tag;
    }

    // proxy
    public function addRewriteRule($regex, $query, $after = 'top'){
        // passthrough
        add_rewrite_rule($regex, $query, $after);

        // store rule regex (should be unique!)
        $this->_rules[] = $regex;
    }

    // remove rewrite rules and flush them
    public function cleanup(){
        // remove custom filters
        foreach ($this->_filters as $name => $cb){
            remove_filter($name, $cb, 100);
        }

        // remove tags
        foreach ($this->_tags as $tag){
            remove_rewrite_tag($tag);
        }

        // filter the rewrite rules and drop custom rules
        add_filter('rewrite_rules_array', array($this, 'purgeRewriteRules'), 999);

        // flush (regenerate rules)
        flush_rewrite_rules();
    }

    // filter callback to remove all registered custom rules (use unique regex to match them)
    public function purgeRewriteRules($originRules){
        // apply filter function to each element
        return $this->applyRewriteFilter(function($rule){

            // rule registered by this plugin ?
            if (in_array($rule[0], $this->_rules)){
                return null;
            }else{
                return $rule;
            }

        }, $originRules);
    }

    // allow much easier rule filtering by converting the assoc style original rules
    // to a multidimension array: entry[] = array(regex, rewrite)
    // each of the rewrite rules is passed to the callback function
    protected function applyRewriteFilter($cb, $originRules){
        // temporary rules
        $alteredRules = array();

        // convert origin rules to temporary structure
        foreach ($originRules as $regex => $rewrite){
            $alteredRules[] = array($regex, $rewrite);
        }

        // apply filter callback
        $alteredRules = array_map($cb, $alteredRules);

        // convert back
        $output = array();

        // convert to assoc array
        foreach ($alteredRules as $rule){
            if ($rule != null){
                $output[$rule[0]] = $rule[1];
            }
        }

        return $output;
    }


}