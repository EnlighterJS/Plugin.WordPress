// the following code is inlined via EnlighterJS.php
(function(_window, _console){
    // EnlighterJS library loaded ?
    if (typeof EnlighterJS == 'undefined'){
        var log = (_console && (_console.error || _console.log)) || function(){};
        log('Error: EnlighterJS resources not loaded yet!');
        return;
    }

    // code injection
    (_window.EnlighterJSINIT = function(){
        EJS_INIT();
    })();
})(window,console);