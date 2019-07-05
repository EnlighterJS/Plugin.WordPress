// scope
(function(_window){
    // enlighterJS available ?
    if (typeof EnlighterJSINIT === "undefined" || typeof jQuery === "undefined"){
        return;
    };

    // content update event
    jQuery(document.body).on('post-load', function(){
        // delay action to allow event handlers to be added
        _window.setTimeout(function(){
            EnlighterJSINIT.apply(_window);
        }, 180);
    });
})(window);