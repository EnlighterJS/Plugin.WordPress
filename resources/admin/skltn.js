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
// Plugin Settings Page Functions
jQuery(document).ready(function(jq){
    // try to fetch session storage
    var storage = window.sessionStorage || null;
    var hasSessionStorage = (typeof storage === 'object');

    // Tabs/Sections
    // --------------------------------------------------------

    // get query string as storage id
    // required in case multiple admin pages are used
    var pageName = jq('.wrap').attr('data-enlighter-page') || 'enlighter';

    // hide containers
    jq('.enlighter-tab').hide();

    // try to restore last tab
    var recentSelectedTabID = (hasSessionStorage ? storage.getItem('enlighter-' + pageName) : false);

    // get currently active tab
    var currentTab = (recentSelectedTabID && recentSelectedTabID !== 'undefined' ? jq("#enlighter-tabnav a[data-tab='" + recentSelectedTabID + "']") : jq('#enlighter-tabnav a:first-child'));

    // add actions to each tabnav button
    jq('#enlighter-tabnav a').each(function (){
        // get current element
        var el = jq(this);

        // click event
        el.click(function () {
            // remove highlight
            currentTab.removeClass('nav-tab-active');

            // hide active container
            jq('#' + currentTab.attr('data-tab')).hide();

            // store current active tab
            currentTab = el;
            currentTab.addClass('nav-tab-active');

            // show container
            jq('#' + currentTab.attr('data-tab')).show();

            // store current tab
            try{
                if (hasSessionStorage && currentTab){
                    storage.setItem('enlighter-' + pageName, currentTab.attr('data-tab'));
                }
            // storage operation may fail (private browser mode; non free space)
            }catch(e){
            }
        });
    });

    // show first container
    currentTab.click();

    // hide update message
    // --------------------------------------------------------
    var msg = jq('#setting-error-settings_updated');
    if (msg) {
        msg.delay(1500).fadeOut(500);
    }

    // iris colorpicker
    // --------------------------------------------------------

    if (typeof jQuery.fn.wpColorPicker === 'function'){
        jq('.enlighter-colorpicker').wpColorPicker();
    }

    // Selective Option Sections 
    // --------------------------------------------------------
    jq('.enlighter-selective-section').each(function(){
        // get current element
        var container = jq(this);

        // get attributes
        var trigger = container.attr('data-trigger');
        var condition = container.attr('data-condition');

        // attributes & condition set ?
        if (!trigger || !condition){
            return;
        }

        // get trigger element
        var triggerElement = jq(trigger);

        // valid element ?
        if (!triggerElement){
            return;
        }

        // action callback
        var onChangeHandler = null;

        // checkbox - "checked" -> show element
        if (condition == 'checked'){
            // onchange event
            onChangeHandler = function(){
                if (jq(this).is(":checked")){
                    container.show();
                }else{
                    container.hide();
                }
            };

        // checkbox - "checked" -> hide element
        }else if (condition == '!checked'){
            // onchange event
            tonChangeHandler = function(){
                if (jq(this).is(":checked")){
                    container.hide();
                }else{
                    container.show();
                }
            };

        // condition based on values
        }else{

            // extract operator
            var op = condition.substr(0,2);

            // extract compare value
            var compare = condition.substr(2);
            var compareInt = parseInt(compare);

            // onchange event
            onChangeHandler = function(){

                // get current element value
                var value = jq(this).val();

                // hidden by default
                var showElement = false;

                // different operators
                switch (op){
                    case '>':
                        showElement = (value>compareInt);
                        break;
                    case '>=':
                        showElement = (value>=compareInt);
                        break;
                    case '<':
                        showElement = (value<compareInt);
                        break;
                    case '<=':
                        showElement = (value<=compareInt);
                        break;
                    case '==':
                    default:
                        showElement = (value == compare);
                        break;
                }

                // change display state
                if (showElement){
                    container.show();
                }else{
                    container.hide();
                }
            };
        }
      
        // onchange event
        triggerElement.on('change', onChangeHandler).trigger('change');
    });
});