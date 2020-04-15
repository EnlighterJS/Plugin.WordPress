
## Frequently Asked Questions ##

### Can i use Enlighter togehter with Crayon ? ###
No, you can't use Enlighter together with the Crayon Syntax highlighter because it may take over the Enlighter elements.

### Should i use Shortcode`s or the Visual-Editor Integration ? ###
If possible, use the VisualEditpr mode! The use of Shortcode is only recommended when working in Text-Mode. By switching to the Visual-Editor-Mode whitespaces (linebreaks, indents, ..) within the shortcode will get removed by the editor - using Visual-Editor mode will avoid such problems.

### I can't see any style options within the Visual-Editor-Toolbar ###
You have to enable the full toolbar by clicking on the **Show/Hide Kitchen Sink** button (last icon on the toolbar)

### I get an "file permission" php error in my blog ###
The directory `/wp-content/plugins/enlighter/cache/` must be writable - the generated css files as well as some cached content will be stored there for performance reasons. Try to set chmod to `0644` or `0770`

### When using the ThemeCustomizer the Code appears in plain-text ###
The cache-directory `wp-content/plugins/enlighter/cache` have to be writable, the generated stylesheet will be stored there. Set the directory permission (chmod) to `0644` or `0777`

### Inline Styles are missing within the Visual Editor ###
This feature requires WordPress 3.9 (new TinyMCE Version) - but you can still use shortcodes for inline highlighting! 

### How can i enable the Theme-Customizer ? ###
To enable the Theme-Customizer you have to select the theme named *Custom* as default theme. The Theme-Customizer will appear immediately.

### Is it possible to point out special lines of code ? ###
Yes! since version 1.5 all shortcodes support the attribute ``highlight``.
Shortcode Example: highlight the lines 2,3,4,8 of the codeblock `[js highlight="2-4,8"]....some code..[/js]`
	
### Are the uncompressed EnlighterJS Javasscript and CSS sources available ? ###
The complete EnlighterJS project can be found on [GitHub](https://github.com/EnlighterJS/EnlighterJS "EnligherJS Project")

### Can Enlighter by disabled on selected pages? ###
Of course, the filter hook [enlighter_startup](https://github.com/EnlighterJS/Plugin.WordPress/blob/master/docs/FilterHooks.md) can be used to terminate the plugin initialization

### Security Vulnerabilities ###
In case you found a security issue in this plugin - please write a message **directly** to [Andi Dittrich](http://andidittrich.com/contact) - __**DO NOT POST THIS ISSUE ON GITHUB OR WORDPRESS.ORG**__ - the issue will be public released if it is fixed!

### I miss some features / I found a bug ###
Please pen a [New Issue on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues)
