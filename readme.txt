=== Enlighter - Customizable Syntax Highlighter ===
Contributors: Andi Dittrich
Tags: syntax highlighting, javascript, code, coding, mootools, customizable, themes, css, html, php, js, xml, c, cpp, ruby, shell, java, python, sql
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: 1.2
License: MIT X11-License
License URI: http://opensource.org/licenses/MIT

Simple post syntax-highlighted code using the EnlighterJS MooTools Plugin.

== Description ==

Enlighter is a free, easy-to-use, syntax highlighting tool for WordPress. It's build in PHP and uses the MooTools based [EnlighterJS](http://www.a3non.org/go/enlighterjs) to provide a beautiful code-appearance.
Using it can be as simple as adding a shortcode around your scripts which you want to highlight and Enlighter takes care of the rest. An easy to use Theme-Customizer is included to modify the build-in themes without any css knowlegde!
It also supports the automatic creation of tab-panes to display code-groups together (useful for multi-language examples - e.g. html+css+js)
A theme demo can be found [here](http://static.andidittrich.de/EnlighterJS/Demo.html "EnligherJS Theme Demo")

= Plugin Features =
* Support for all common used languages
* Theme Customizer
* Easy to use through the use of Shortcodes
* Advanced configuration options (e.g. CDN usage) are available within the options page.
* Supports code-groups (displays multiple code-blocks within a tab-pane)
* Outputs in various formats like ordered lists or inline. Choose the method that works best for you.
* Extensible language and theme engines - add your own one.
* Simple CSS based themes
* Integrated CSS file caching (suitable for high traffic sites)
* EnlighterJS is written in MooTools. Requires version 1.4+

= Quickstart Example =
Highlight javascript code (theme defined on your settings page)

	[js]
	window.addEvent('domready', function(){
		console.info('Hello Enlighter');
	});	
	[/js]

= Codegroup Example =
Display multiple codes within a tab-pane. You can define a custom tab-pane title for each snippet if you want.

	[codegroup]
	 	[js tab="Javascript Message"]
		window.addEvent('domready', function(){
			// display string on console
			console.info('Hello Enlighter');
			
			// show element
			$('#myelement').show();
		});	
		[/js]
		
		[html]
		<div id="myelement">
		INITIALIZATION START
		</div>		
		[/html]
		
		[css tab="Styling"]
		#myelement{
			color: #cc2222;
			padding: 15px;
			font-size: 20px;
			text-align: center;		
		}		
		[/css]	
	[/codegroup]

= Legacy Example =
It's also possible to use the plugin with legacy shortcode

	[enlighter lang="js"]
	window.addEvent('domready', function(){
		// display string on console
		console.info('Hello Enlighter');
		
		// show element
		$('#myelement').show();
	});		
	[/enlighter]


= Supported Languages (build-in) =
* HTML
* CSS (Level 1, 2, 3)
* XML
* Javascript
* Java
* Markdown
* PHP
* Python
* Ruby
* Shellscript
* C
* C++
* SQL
* NSIS
 
=== Compatibility ===

All browsers supported by MooTools and with HTML5 capabilities for "data-" attributes are compatible with Enlighter. It's possible that it may work with earlier/other browsers.
Generally Enlighter (which javascript part [EnlighterJS](http://www.a3non.org/go/enlighterjs) is based on [MooTools Javascript Framework](http://mootools.net/)) should work together with jQuery in [noConflict Mode](http://docs.jquery.com/Using_jQuery_with_Other_Libraries) - when you are using jQuery within your Wordpress Theme/Page you have to take care of it!

* Chrome 10+
* Safari 5+
* Internet Explorer 6+
* Firefox 2+
* Opera 9+
    
== Installation ==

= System requirements =
* PHP 5
* Webbrowser with enabled Javscript (required for highlighting)

= Installation =
1. Download the .zip file of the plugin and extract the content
2. Upload the complete `enlighter` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Goto to the Enlighter settings page and select the default theme which should be used.
5. That's it! You're done. You can now enter the following code into a post or page to highlight code (e.g. javascript): `[js]var enlighter = new EnlighterJS({});[/js]`

== Frequently Asked Questions ==

= How can i enable the Theme-Customizer ? =
To enable the Theme-Customizer you have to select the theme named *Custom* as default theme. The Theme-Customizer will appear immediately.

= Are the uncompressed EnlighterJS Javasscript and CSS sources available ? =
The complete EnlighterJS project can be found on [GitHub](https://github.com/AndiDittrich/EnlighterJS "EnligherJS Project")

= Can i add custom Themes ? =
Yes you can! - The simplest way is to download the [EnlighterJS CSS sources](https://github.com/AndiDittrich/EnlighterJS/tree/master/Source/Themes "EnligherJS Project") and modify the standard theme (*Sources/Themes/Standard.css*). On the Enlighter options page you have to select the theme *standard* and disable the automatic inclusion of the enlighter css files. Finally you have to include the modified css files on you own.

= I'am already using MooTools and my page throws Javascript-Errors =
If you are already using MooTools on your page, you have to disable the automatic inclusion of MooTools by Enlighter. Goto the Enlighter options page -> Advanced and select "Not include" as MooTools source. 
**Note:** EnlighterJS requires MooTools > 1.4

= My page will not pass the W3C Validator when using Enligher =
The W3C Validator will throw an warning when using *metadata based config* as initialization method, because of the unknown metatag-name *EnlighterJS*. To avoid this warning you can select **Script based config** on the `settings page -> advanced -> initialization method` - instead of a metatag EnlighterJS will be initialized with some javascript code injected into your page.

= I miss some features / I found a bug =
Well..write a email to Andi Dittrich (andi DOT dittrich AT a3non DOT org) or open a [New Issue on GitHub](https://github.com/AndiDittrich/WordPress.Enlighter/issues)

== Screenshots ==

1. CSS highlighting Example (GIT Theme)
2. Tab-Pane Example (multiple languages)
3. Options Page - Appearance Settings
4. Options Page - Advanced Settings
5. Theme Customizer - General styles
6. Theme Customizer - Language Token styling

== Changelog ==

= 1.2 =
* Added EnlighterJS 1.5.1
* Added language support for NSIS (Nullsoft Scriptable Install System)

= 1.1 =
* First public release
* Includes EnligherJS 1.4