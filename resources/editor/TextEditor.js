/*!
---
name: Enlighter.TextEditor
description: Text/HTML Editor Plugin for WordPress

license: MIT-Style X11 License
version: 1.0

authors:
 - Andi Dittrich

requires:
 - Enlighter/2.12
...
*/

// domready wrapper
jQuery(function(jq){

    // dependencies loaded ?
    if (typeof Enlighter == 'undefined' || typeof QTags == 'undefined'){
        return;
    }

    // add quicktags ?
    if (Enlighter.config.quicktagMode != 'disabled'){

        var position = 200;

        // iterate over all available languages
        jq.each(Enlighter.languages, function(name, slug){
            // increment position
            position++;

            // Shortcode Mode - Legacy
            if (Enlighter.config.quicktagMode == 'shortcode'){

                // language shortcode enabled ?
                if (Enlighter.config.languageShortcode){
                    QTags.addButton('enlighter-' + slug, slug, '[' + slug + ']', '[/'  + slug + ']', null, name, position);

                // legacy shortcode
                }else{
                    QTags.addButton('enlighter-' + slug, slug, '[enlighter lang="' + slug + '"]', '[/enlighter]', null, name, position);
                }

            // HTML Mode - Visual Editor Compatible
            }else{
                QTags.addButton('enlighter-' + slug, slug, '<pre class="EnlighterJSRAW" data-enlighter-language="' + slug + '">', '</pre>', null, name, position);
            }
        });
    }
});
