# Enlighter - Customizable Syntax Highlighter #
Contributors: Andi Dittrich, aenondynamics
Tags: syntax highlighting, javascript, code, coding, sourcecode, mootools, jquery, customizable, visual editor, tinymce, themes, css, html, php, js, xml, c, cpp, c#, ruby, shell, java, python, sql, rust, matlab, json, ini, config, cython, lua, assembly, asm
Donate link: http://enlighterjs.org
Requires at least: 3.9
Tested up to: 4.8
Stable tag: 3.5
License: MIT X11-License
License URI: http://opensource.org/licenses/MIT

Simple post syntax-highlighted code using the EnlighterJS Javascript Plugin.

## Description ##

Enlighter is a free, easy-to-use, syntax highlighting tool for WordPress. It's build in PHP and uses the MooTools(Javascript) based [EnlighterJS](http://enlighterjs.org) to provide a beautiful code-appearance.
Using it can be as simple as selecting an editor style or adding shortcode around your scripts which you want to highlight and Enlighter takes care of the rest. An easy to use Theme-Customizer is included to modify the build-in themes **without any css knowlegde!**
It also supports the automatic creation of tab-panes to display code-groups together (useful for multi-language examples - e.g. html+css+js)
[Theme Demo](http://enlighterjs.org/Theme.Enlighter.html "EnlighterJS Theme Browser") - [Language Examples](http://enlighterjs.org/Language.Javascript.html "EnlighterJS Language Example")

### Plugin Features ###
* Support for all common used languages including powerful generic highlighting
* **Full** Visual-Editor (TinyMCE) Integration (Admin Panel + Frontend)
* Theme Customizer including **LIVE Preview Mode**
* Inline Syntax Highlighting
* Tab-Indentation mode to align code with the `tab` key (single line and block selection)
* Markdown fenced code blocks
* [bbPress](https://bbpress.org/) shortcode + markdown code blocks support
* Shortcodes within content, comments and widgets
* Easy to use Text-Editor mode through the use of Shortcodes and QuickTags
* Advanced configuration options (CDN usage, ..) are available within the options page.
* Supports code-groups (displays multiple code-blocks within a tab-pane)
* Extensible language and theme engines - add your own one.
* Simple CSS based themes
* Integrated CSS file caching (suitable for high traffic sites)
* Standalone Shortcode-Processor to avoid wpautop filter issues in Text-Editor Mode
* Webfont Loader to add missing Monospace Fonts to your website

### Tutorials and Examples ###

* [Enlighter Visual Editor Usage](https://github.com/AndiDittrich/WordPress.Enlighter/blob/master/docs/Usage_VisualEditor.md) - Howto add Code Snippets to your posts and pages

### Supported Languages (build-in) ###
Click to view Language/Theme Examples

* [AVR Assembly](http://enlighterjs.org/Language.AVR-Assembly.html)
* [Generic Assembly](http://enlighterjs.org/Language.Assembly.html)
* [C](http://enlighterjs.org/Language.C.html)
* [CSS](http://enlighterjs.org/Language.CSS.html)
* [C#](http://enlighterjs.org/Language.CSharp.html)
* [C++](http://enlighterjs.org/Language.Cpp.html)
* [Cython](http://enlighterjs.org/Language.Cython.html)
* [Diff](http://enlighterjs.org/Language.Diff.html)
* [Generic](http://enlighterjs.org/Language.Generic.html)
* [HTML](http://enlighterjs.org/Language.HTML.html)
* [Ini](http://enlighterjs.org/Language.Ini.html)
* [JSON](http://enlighterjs.org/Language.JSON.html)
* [Java](http://enlighterjs.org/Language.Java.html)
* [Javascript](http://enlighterjs.org/Language.Javascript.html)
* [Kotlin](https://enlighterjs.org/Language.Kotlin.html)
* [LUA](http://enlighterjs.org/Language.LUA.html)
* [MarkDown](http://enlighterjs.org/Language.MarkDown.html)
* [Matlab](http://enlighterjs.org/Language.Matlab.html)
* [NSIS](http://enlighterjs.org/Language.NSIS.html)
* [PHP](http://enlighterjs.org/Language.PHP.html)
* [Python](http://enlighterjs.org/Language.Python.html)
* [RAW](http://enlighterjs.org/Language.RAW.html)
* [Ruby](http://enlighterjs.org/Language.Ruby.html)
* [Rust](http://enlighterjs.org/Language.Rust.html)
* [SQL](http://enlighterjs.org/Language.SQL.html)
* [Squirrel](http://enlighterjs.org/Language.Squirrel.html)
* [Shell](http://enlighterjs.org/Language.Shell.html)
* [VHDL](http://enlighterjs.org/Language.VHDL.html)
* [XML](http://enlighterjs.org/Language.XML.html)

### Shortcode Quickstart Example ###
Highlight javascript code (theme defined on your settings page)

	[js]
	window.addEvent('domready', function(){
		console.info('Hello Enlighter');
	});	
	[/js]

### Inline Syntax Highlighting with Shortcode ###

	Lorem ipsum dolor sit amet, [js]window.alert('Hello World');[/js] consetetur sadipscing elitr,
	sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.

### Codegroup Example ###
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

### Legacy Example ###
It's also possible to use the plugin with legacy shortcode (disabled language shortcodes)

	[enlighter lang="js"]
	window.addEvent('domready', function(){
		// display string on console
		console.info('Hello Enlighter');
		
		// show element
		$('#myelement').show();
	});		
	[/enlighter]


### Translations (I18n) ###
Please keep in mind that not all translations are up to date. You are welcome to contribute!

* **English** (default)
* **German** (de_DE by Andi Dittrich)
* **Serbo-Croatian** (sr_RS by Borisa Djuraskovic from webhostinghub.com)
 
### Related Links ###
* [Enlighter Plugin Docs + Tutorials](https://github.com/AndiDittrich/WordPress.Enlighter/docs)
* [Enlighter Plugin on GitHub](https://github.com/AndiDittrich/WordPress.Enlighter)
* [EnlighterJS Documentation](http://enlighterjs.org)

## Compatibility ##

All browsers supported by MooTools (enabled Javascript required) and with HTML5 capabilities for "data-" attributes are compatible with Enlighter. It's possible that it may work with earlier/other browsers.
Generally Enlighter (which javascript part [EnlighterJS](http://enlighterjs.org) is based on [MooTools Javascript Framework](http://mootools.net/)) should work together with jQuery in [noConflict Mode](http://docs.jquery.com/Using_jQuery_with_Other_Libraries) - when you are using jQuery within your Wordpress Theme/Page you have to take care of it!

* Chrome 10+
* Safari 5+
* Internet Explorer 6+
* Firefox 2+
* Opera 9+
    
## Installation ##

### System requirements ###
* PHP 5.3, including `json` functions
* Webbrowser with enabled Javascript (required for highlighting)
* Accessable cache directory (`/wp-content/plugins/enlighter/cache/`)

### Installation ###
1. Download the .zip file of the plugin and extract the content
2. Upload the complete `enlighter` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Goto to the Enlighter settings page and select the default theme which should be used.
5. That's it! You're done. You can select an editor style for your codefragment or enter the following code into a post or page to highlight it (e.g. javascript): `[js]var enlighter = new EnlighterJS({});[/js]` 

## Screenshots ##

1. HTML highlighting Example (Enlighter Theme)
2. Visual Editor Integration
3. Visual Editor Code Settings
4. Visual Editor Inline/Block Formats
5. Options Page - Appearance Settings
6. Options Page - Advanced Settings
7. Theme Customizer - General styles
8. Theme Customizer - Language Token styling
9. Special options for use with a CDN (Content Delivery Network)
10. Tab-Pane Example (multiple languages)
11. Frontend Editing using wp_editor feature
12. Theme Customizer - Live Preview-Mode

## Upgrade Notice ##

### 3.3 ###
PHP 5.3 Backward Compatibility

### 3.0 ###
New fault-tolerant Shortcode-Processor is integrated. You can switch back to the old one on the Enlighter Settings Page -> Editing -> Shortcode

### 2.11 ###
Bugfix Release (initialization may fail when upgrading to 2.10)

### 2.9 ###
Bugfix Release (TinyMCE and EnlighterJS Core)

### 2.6 ###
Renamed the EnlighterJS files to `EnlighterJS.min.css` and `EnlighterJS.min.js`. In case you have applied custom modifications these changes may broke your setup and you need to change it!
Added [EnlighterJS v2.5](http://enlighterjs.org/) with some optimization.

### 2.4 ###
Removed WordPress 3.8 Visual Editor compatibility - Enlighter now requires WordPress >= 3.9 including TinyMCE 4

### 2.2 ###
Full Visual-Editor (TinyMCE4) Integration including codeblock-settings (WordPress >= 3.9 required)

### 2.0 ###
Added Inline-Syntax-Highlighting as well as some other cool feature - please go to the settings page and click "Apply Settings"

### 1.8 ###
Added Visual-Editor (TinyMCE) Integration (will avoid auto-whitespace-removing issues)

