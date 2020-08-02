// scope
(function(_window){
    // jquery available ?
    if (typeof jQuery === "undefined"){
        return;
    };

    // content update event
    jQuery(document.body).on('post-load', function(){
        // enlighterJS available ?
        if (typeof EnlighterJSINIT === "undefined"){
            return;
        }
        
        // delay action to allow event handlers to be added
        _window.setTimeout(function(){
            EnlighterJSINIT.apply(_window);
        }, 180);
    });
})(window);