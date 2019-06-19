<?php

namespace Enlighter\Admin;

class ContextualHelp{

    // Setup Help Screen
    public static function settings(){
        // load screen
        $screen = get_current_screen();

        $screen->add_help_tab(array(
            'id' => 'enlighter_ch_tutorials',
            'title'    => __('Tutorials'),
            'callback' => function(){
                include(ENLIGHTER_PLUGIN_PATH.'/views/contextualhelp/'.'tutorials.en_EN.phtml');
            }
        ));

        $screen->add_help_tab(array(
            'id' => 'enlighter_ch_usage',
            'title'    => __('General Usage'),
            'callback' => function(){
                include(ENLIGHTER_PLUGIN_PATH.'/views/contextualhelp/'.'usage.en_EN.phtml');
            }
        ));

        $screen->add_help_tab(array(
            'id' => 'enlighter_ch_markdown',
            'title'    => __('Markdown'),
            'callback' => function(){
                include(ENLIGHTER_PLUGIN_PATH.'/views/contextualhelp/'.'markdown.en_EN.phtml');
            }
        ));
    
        // shortcode help
        $screen->add_help_tab(array(
            'id' => 'enlighter_ch_shortcode',
            'title'    => __('Shortcodes'),
            'callback' => function(){
                include(ENLIGHTER_PLUGIN_PATH.'/views/contextualhelp/'.'shortcodes.en_EN.phtml');
            }
        ));
      
        $screen->add_help_tab(array(
            'id' => 'enlighter_ch_codegroups',
            'title'    => __('Codegroups'),
            'callback' => function(){
                include(ENLIGHTER_PLUGIN_PATH.'/views/contextualhelp/'.'codegroups.en_EN.phtml');
            }
        ));
        $screen->add_help_tab(array(
            'id' => 'enlighter_ch_externalthemes',
            'title'    => __('External Themes'),
            'callback' => function(){
                include(ENLIGHTER_PLUGIN_PATH.'/views/contextualhelp/'.'externalthemes.en_EN.phtml');
            }
        ));
        
        // sidebar
        $screen->set_help_sidebar(file_get_contents(ENLIGHTER_PLUGIN_PATH.'/views/contextualhelp/'.'sidebar.en_EN.html'));
    }
}