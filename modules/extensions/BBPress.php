<?php

namespace Enlighter\extensions;

class BBPress{

    public static function init($markdown=false, $shortcode=false){

        // markdown enabled ?
        if ($markdown){
            // add filter to hook into the section post-processing
            // user defined filters will have prio 10 by default
            add_filter('enlighter_gfm_filters', function($sections){
                // add BuddyPress Sections
                $sections[] = 'bbp_get_reply_content';
                $sections[] = 'bbp_get_topic_content';

                return $sections;
            }, 5);
        }

        // shortcodes enabled ?
        if ($shortcode){
            // add filter to hook into the section post-processing
            // user defined filters will have prio 10 by default
            add_filter('enlighter_shortcode_filters', function($sections){
                // add BuddyPress Sections
                $sections[] = 'bbp_get_reply_content';
                $sections[] = 'bbp_get_topic_content';

                return $sections;
            }, 5);
        }

        // markdown or shortcodes enabled ? -> disable bbpress code filters
        if ($shortcode || $markdown){
            // revert the code filter changes temporary - allows the use of triple backticks
            add_filter('bbp_get_reply_content', 'bbp_code_trick_reverse', 0);
            add_filter('bbp_get_topic_content', 'bbp_code_trick_reverse', 0);
        }
    }
}