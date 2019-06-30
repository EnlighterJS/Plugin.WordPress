/*! EnlighterJS Syntax Highlighter QuickTag Plugin 1.0.0 | Mozilla Public License 2.0 | https://enlighterjs.org */

// domready wrapper
jQuery(function(jq){

    // dependencies loaded ?
    if (typeof EnlighterJS_EditorConfig == 'undefined' || typeof QTags == 'undefined'){
        return;
    }

    // shortcut
    var _enlighter = EnlighterJS_EditorConfig;

    // start position
    var position = 200;

    // iterate over all available languages
    jq.each(_enlighter.languages, function(slug, name){
        // increment position
        position++;

        // switch between shortcode modes
        switch (_enlighter.text.quicktags){

            // language shortcode enabled ?
            case 'shortcode':
                    QTags.addButton('enlighter-' + slug, name, '[' + slug + ']', '[/'  + slug + ']', null, name, position);
                break;

            // legacy shortcode
            case 'legacy_shortcode':
                    QTags.addButton('enlighter-' + slug, name, '[enlighter lang="' + slug + '"]', '[/enlighter]', null, name, position);
                break;

            // HTML Mode - Visual Editor Compatible
            case 'html':
            default:
                QTags.addButton('enlighter-' + slug, name, '<pre class="EnlighterJSRAW" data-enlighter-language="' + slug + '">', '</pre>', null, name, position);
                break;
        }
    });
});
