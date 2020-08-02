// ----------------------------------------------------------------------
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this
// file, You can obtain one at http://mozilla.org/MPL/2.0/.
// --
// Copyright 2020 Andi Dittrich <https://enlighterjs.org>
// ----------------------------------------------------------------------

// the following code is inlined via DynamicResourceInvocation.php
(function(_window, _console){
    // plugin generated config object (replaced with inline code)
    var config = ENLIGHTERJS_CONFIG;

    // reference to html head
    var head = document.getElementsByTagName('head')[0];

    // logging
    var log = (_console && (_console.error || _console.log)) || function(){};

    // flag
    var isEnqueued = false;

    // load js+css via element injection
    function enqueueResources(resources, cb){
        var numElementsLoaded = 0;
        var lastError = null;

        function onCompleteHandler(err){
            lastError = err;
            numElementsLoaded++;

            // all callbacks returned ?
            if (numElementsLoaded == resources.length){
                // set flag
                isEnqueued = true;

                // run final callback to trigger initialization
                cb(lastError);
            }
        }

        // load all resources
        resources.forEach(function(src){
            // extract extension
            var match = src.match(/\.([a-z]+)(?:[#?].*)?$/);

            switch (match[1]){
                // dynamically load script
                case 'js':
                    var script = document.createElement('script');
                    script.onload = function(){onCompleteHandler(null)};
                    script.onerror = onCompleteHandler;
                    script.src = src;
                    script.async = true;
                    head.appendChild(script);
                    break;

                 // dynamically load style
                case 'css':
                    var link = document.createElement('link');
                    link.onload = function(){onCompleteHandler(null)};
                    link.onerror = onCompleteHandler;
                    link.rel = 'stylesheet';
                    link.type = 'text/css';
                    link.href = src;
                    link.media = 'all';
                    head.appendChild(link);
                    break;
                default:
                    log('Error: invalid file extension', src);
            };
        });
    }

    // add initialization wrapper
    // triggered by dynamic ajax extensions!
    _window.EnlighterJSINIT = function(){

        // enqueue resources
        enqueueResources(config.resources, function(err){
            // success ?
            if (err){
                log('Error: failed to dynamically load EnlighterJS resources!', err);
                return;
            }

            // EnlighterJS library loaded ?
            if (typeof EnlighterJS == 'undefined'){
                log('Error: EnlighterJS resources not loaded yet!');
                return;
            }

            // initialize highlighting
            EnlighterJS.init(config.selectors.block, config.selectors.inline, config.options);
        });
    };

    // enlighterjs elements available on current page ?
    if (document.querySelector(config.selectors.block) || document.querySelector(config.selectors.inline)){
        _window.EnlighterJSINIT();
    }

})(window,console);