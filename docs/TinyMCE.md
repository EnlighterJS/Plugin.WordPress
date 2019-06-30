TinyMCE Visual Editor Integration
=============================================

## Frontend ##

### Automatic Integration ###

The automatic integration requires the following conditions:

1. Enabled frontend integration flag (Settings -> Editing -> TinyMCE -> Frontend Integration)
2. The editor has to be added via the standard [wp_editor](https://codex.wordpress.org/Function_Reference/wp_editor) function
3. The first toolbar is not overridden with custom settings
4. Frontend editing is limited to users with the standard WordPress permissions `edit_post` or `edit_pages`. You can change this security condition by invoking the filter enlighter_frontend_editing

### Manual Integration ###

In case your plugins doesn't fulfill all previous mentioned requirements it is also possible to add the EnlighterJS editor plugin **manually** - this required advanced knowledge in php and javascript!

The editor plugin has its own [repository on GitHub](https://github.com/EnlighterJS/Plugin.TinyMCE)

#### Javascript initialization Examples ####

**Dependencies**

The plugin dependencies have to be available on your website. 
In case you're already using the Enlighter plugin they are located in `wp-content/plugins/enlighter/resources/tinymce`

**Direct initialization via tinymce**

```javascript
 // initialize TinyMCE
tinymce.init({
    // paste plugin is required to strip pasted wysiwyg content (drop formats!)
    plugins: 'paste',
    external_plugins: {
        'enlighterjs': '/wp-content/plugins/enlighter/resources/tinymce/enlighterjs.tinymce.min.js'
    },
    content_css: "/wp-content/plugins/enlighter/resources/tinymce/enlighterjs.tinymce.min.css",
    selector: '#editor1',
    menubar: false,
    height: 1200,
    toolbar: 'styleselect | bold italic | link image | pastetext | EnlighterInsert EnlighterEdit'
});
```

**Indirect initialization via wp.editor**

```javascript
 // initialize TinyMCE
wp.editor.initialize('editor1', {
    'tinymce': {
        // paste plugin is required to strip pasted wysiwyg content (drop formats!)
        plugins: 'paste',
        external_plugins: {
            'enlighterjs': '/wp-content/plugins/enlighter/resources/tinymce/enlighterjs.tinymce.min.js'
        },
        content_css: "/wp-content/plugins/enlighter/resources/tinymce/enlighterjs.tinymce.min.css",
        toolbar: 'styleselect | bold italic | link image | pastetext | EnlighterInsert EnlighterEdit'
    }
});
```