// ---------------------------------------------------------------------------------------------------------------
// -- WP-SKELETON AUTO GENERATED FILE - DO NOT EDIT !!!
// --
// -- Copyright (c) 2016-2018 Andi Dittrich
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
    const storage = window.sessionStorage || null;

    // Tabs/Sections
    // --------------------------------------------------------
    if (typeof storage === 'object'){
        // hide containers
        jq('#post-body-content > div').hide();

        // try to restore last tab
        var recentSelectedTabID = storage.getItem('enlighter-tabnav');

        // get currently active tab
        var currentTab = (recentSelectedTabID ? jq("#enlighter-tabnav a[data-tab='" + recentSelectedTabID + "']") : jq('#enlighter-tabnav a:first-child'));

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
                    storage.setItem('enlighter-tabnav', currentTab.attr('data-tab'));
                // storage operation may fail (private browser mode; non free space)
                }catch(e){
                }
            });
        });

        // show first container
        currentTab.click();
    }

    // hide update message
    // --------------------------------------------------------
    var msg = jq('#setting-error-settings_updated');
    if (msg) {
        msg.delay(1500).fadeOut(500);
    }

    // colorpicker
    // --------------------------------------------------------
    if (typeof jQuery.fn.ColorPicker === 'function'){

        // utility function to set foreground
        function setHighContrastForeground(el, backgroundColor){
            // get color as HSV
            var hsv = jq.Color(backgroundColor);

            // foreground color based on background (best contrast)
            if (hsv.lightness() > 0.5){
                el.css('color', '#000000');
            } else {
                el.css('color', '#f0f0f0');
            }
        }

        // initialize colorpicker
        jq('.enlighter-colorpicker').ColorPicker({
            onSubmit: function (hsb, hex, rgb, element){
                // get current element
                var el = jq(element);

                // set input value
                el.val('#' + hex);

                // set input background color
                el.css('background-color', '#' + hex);
                
                // hide color picker window
                el.ColorPickerHide();

                setHighContrastForeground(el, '#' + hex);
            },
            onBeforeShow: function () {
                jq(this).ColorPickerSetColor(this.value);
            }
        });

        // initialize colors
        jq('.enlighter-colorpicker').each(function(){
            // get current element
            var el = jq(this);

            // get element color value
            var color = el.val();

            // color available ?
            if (color.length > 0){
                // set element background color
                el.css('background-color', color);

                setHighContrastForeground(el, color);
            }
        });
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