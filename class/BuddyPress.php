<?php
/**
	BuddyPress Shortcode Plugin
	Version: 1.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://andidittrich.de/go/enlighterjs
	License: MIT X11-License
	
	Copyright (c) 2016, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace Enlighter;

class BuddyPress{

    public static function enableShortcodeFilter(){
        // add filter to hook into the section post-processing
        // user defined filters will have prio 10 by default
        add_filter('enlighter_shortcode_filters', function($sections){
            // add BuddyPress Sections

            // last update message of current user (on top)
            // override the stripping!
            //$sections[] = array('bp_get_activity_latest_update_excerpt', 'bp_get_activity_latest_update');

            // all other activities in list
            //$sections[] =  array('bp_get_activity_content_body', 'bp_get_the_topic_post_content');


            // activity comments - bp_get_activity_comment_content()
            // problem: 2 different filters are invoked !
            //$sections[] = array('bp_get_activity_content', 'bp_activity_comment_content');

            //$sections[] = 'bp_get_activity_content';

            // user comments on activities
            //$sections[] = 'bp_activity_comment_content';

            //$sections[] = 'bp_get_the_topic_post_content';
            //$sections[] = 'bp_get_the_thread_message_content';

            return $sections;
        }, 5);
    }
}