// ----------------------------------------------------------------------
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this
// file, You can obtain one at http://mozilla.org/MPL/2.0/.
// --
// Copyright 2020 Andi Dittrich <https://enlighterjs.org>
// ----------------------------------------------------------------------

// the following code is inlined via EnlighterJS.php
(function(_window, _console){
    // EnlighterJS library loaded ?
    if (typeof EnlighterJS == 'undefined'){
        var log = (_console && (_console.error || _console.log)) || function(){};
        log('Error: EnlighterJS resources not loaded yet!');
        return;
    }

    // plugin generated config object (replaced with inline code)
    var config = ENLIGHTERJS_CONFIG;

    // code injection
    (_window.EnlighterJSINIT = function(){
        EnlighterJS.init(config.selectors.block, config.selectors.inline, config.options);
    })();
})(window,console);