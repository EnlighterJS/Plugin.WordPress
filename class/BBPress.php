<?php

namespace Enlighter;

class BBPress{

    public static function enableShortcodeFilter(){
        // add filter to hook into the section post-processing
        // user defined filters will have prio 10 by default
        add_filter('enlighter_shortcode_filters', function($sections){
            // add BuddyPress Sections
            $sections[] = 'bbp_get_reply_content';
            $sections[] = 'bbp_get_topic_content';

            return $sections;
        }, 5);
    }

    public static function disableCodeFilter(){
        // disable bbp_trick filters to preserve backticks permanently (content within the database is changed!)
        /*
        remove_filter('bbp_new_reply_pre_content', 'bbp_code_trick', 20);
        remove_filter('bbp_new_topic_pre_content', 'bbp_code_trick', 20);
        remove_filter('bbp_edit_reply_pre_content', 'bbp_code_trick', 20);
        remove_filter('bbp_edit_topic_pre_content', 'bbp_code_trick', 20);
        remove_filter('bbp_get_form_reply_content', 'bbp_code_trick_reverse');
        remove_filter('bbp_get_form_reply_content', 'bbp_code_trick_reverse');
        */

        // revert the code filter changes temporary - allows the use of triple backticks
        add_filter('bbp_get_reply_content', 'bbp_code_trick_reverse', 0);
        add_filter('bbp_get_topic_content', 'bbp_code_trick_reverse', 0);
    }

    public static function enableMarkdownFilter(){
        // add filter to hook into the section post-processing
        // user defined filters will have prio 10 by default
        add_filter('enlighter_gfm_filters', function($sections){
            // add BuddyPress Sections
            $sections[] = 'bbp_get_reply_content';
            $sections[] = 'bbp_get_topic_content';

            return $sections;
        }, 5);
    }
}