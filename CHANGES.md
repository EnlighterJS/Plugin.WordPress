## Changelog ##

### 2.12-BETA ###
* Bugfix: Theme Customizer was not able to modify the special-line-highlighting-color of codeblocks **without** line-numbers - thanks to [CraigMcKenna on GitHub](https://github.com/AndiDittrich/WordPress.Enlighter/issues/24)
* Bugfix: MooTools < 1.5.1 [#2705](https://github.com/mootools/mootools-core/pull/2705) will throw the javascript error `The specified value "t" is not a valid email address` - [updated to v1.6.0](http://mootools.net/blog/2016/01/14/mootools-1-6-0-release) - thanks to [
lots0logs on GitHub](https://github.com/AndiDittrich/WordPress.Enlighter/issues/25)
* Replaced: the low-level PHP based ObjectCache by the [WordPress Transient API](https://codex.wordpress.org/Transients_API)
* Dependencies: Updated MooTools to [v1.6.0](http://mootools.net/blog/2016/01/14/mootools-1-6-0-release)

### 2.11 ###
* Bugfix: the default option of "Enlighter Config" is now set to "inline" - this may avoid highlighting when upgrading to 2.10 - I apologize for the inconvenience - thanks to [ciambellino on GitHub](https://github.com/AndiDittrich/WordPress.Enlighter/issues/21)

### 2.10 ###
* Added: [EnlighterJS v2.10.0](http://enlighterjs.org/)
* Added: [Cython](http://cython.org/) Language support - thanks to [DevynCJohnson on GitHub](https://github.com/AndiDittrich/EnlighterJS/pull/14)
* Added: [Squirrel](http://www.squirrel-lang.org/) Language support - thanks to [DevynCJohnson on GitHub](https://github.com/AndiDittrich/EnlighterJS/pull/16)
* Added: [General Assembly Language support](https://en.wikipedia.org/wiki/Assembly_language) - feature requested on [GitHub](https://github.com/AndiDittrich/EnlighterJS/issues/12)
* Added: [LUA](http://www.lua.org/) Language support
* Added: Minimal Theme (bright, high contrast)
* Added: Atomic Theme (dark, colorful)
* Added: Rowhammer Theme (light)
* Added: missing AVR Assembly features (used [AVR-1022](www.atmel.com/Images/doc1022.pdf) reference) 
* Added: Universal Google Webfonts loader: Droid Sans Mono, Inconsolata .. (all available monospace fonts, Nov 2015)
* Added: option to control the global script position (header/footer) of related javascript files - features requested on [GitHub](https://github.com/AndiDittrich/WordPress.Enlighter/issues/17)
* Added: link to the official [EnlighterJS Website](http://enlighterjs.org) to the plugin overview page
* Added: ENLIGHTER_VERSION string to all related js/css resources
* Changed: moved settins page link on the plugin overview page to the action links (left column)
* Changed: the editor font-size is set to **0.7em** and the font-family is changed to "Source Code Pro"
* Renamed: Webfonts style name changed to `enlighter-webfonts`
* Removed: option to control the initialization script position (replaced by an additional global script position option)
* Removed: calls to `wp_register_style` and `wp_register_script` - instead the `wp_enqueue_` methods are used directly
* Bugfix: removed some incorrect html attribute quotes within the settings page
* Bugfix: removed unused html table tag from the settings page
* Bugfix: removed `console.log` debugging output from tokenizer
* Bugfix: in some cases the ThemeCustomizer cannot load the base css files (theme name not transformed to lowercase)
* Bugfix: an empty paragraph is added after each codeblock in the VisualEditor-Mode (permits users to add content after the codeblock)
* Bugfix: copy&paste within a Enlighter codeblock had spilt the block into multiple parts (VisualEditor-Mode)

### 2.9 ###
* Added: [EnlighterJS v2.9](http://enlighterjs.org/)
* Bugfix: Under some special conditions the tokenizer repeats the last sequence of a codeblock - thanks to [Kalydon](https://github.com/AndiDittrich/EnlighterJS/issues/8) and [dan-j on GitHub](https://github.com/AndiDittrich/WordPress.Enlighter/issues/13)
* Bugfix: TinyMCE Editor plugin didn't work in some special cases (use of other editor plugin) - [Thanks to esumit on GitHub](https://github.com/AndiDittrich/WordPress.Enlighter/issues/12)
* Bugfix: the final character of highlighted code got removed by the tokenizer engine in case it's a text token - thanks to [dan-j on GitHub](https://github.com/AndiDittrich/WordPress.Enlighter/issues/15)
* Bugfix: Generic highlighting was accidentally removed from EnlighterJS 

### 2.8 ###
* Added: [EnlighterJS v2.7.0](http://enlighterjs.andidittrich.de/)
* Added: [Rust](http://www.rust-lang.org/) language support - feature requested on [GitHub](https://github.com/AndiDittrich/EnlighterJS/issues/7)
* Added: [VHDL](http://en.wikipedia.org/wiki/VHDL) language support
* Added: [Matlab](http://en.wikipedia.org/wiki/MATLAB) language support
* Added: New Shell/Bash language engine
* Added: New PHP language engine
* Added: New CSS language engine - some styles have changed!
* Added: Shell script example
* Added: "MooTwo" theme inspired by the mootools.net website
* Added: "Godzilla" theme inspired by the MDN
* Added: "Droide" theme
* Added: New EnlighterJS Info Button (Toolbar)
* Added: New Tokenizer Engine which increases the rendering performance by nearly **700%**
* Bugfix: Wrong highlighting class used for SQL comments
* Changed: Smart Tokenizer Engine is used instead of the old Lazy Bruteforce matching
* Changed: All Fonts of the modern Themes are replaced by "Source Code Pro" as default
* Changed: Classic Themes `kw3` color switched with `kw4`
* Changed: The *hover* css-class is now added to the outer `ol,ul` container instead of each `li` line - all themes have been adapted 
* Changed: Inline gif imaages are used for the button toolbar instead of png images (size optimization)
* Many performance improvements
* Reduced the CSS and JS file-size by massive sourcecode optimizations (43kB JS; 28KB CSS; including all Themes and Languages!)

### 2.7 ###
* Added: [EnlighterJS v2.6.0](http://enlighterjs.andidittrich.de/)
* Added: Native JSON highlighting support
* Added: Support for the [Cryptex Email Obfuscation](https://wordpress.org/plugins/cryptex/) plugin (>= v5.0) - email addresses within highlighted code can now protected too
* Added: Plugin Upgrade notifications for upcoming major releases to the admins plugin page
* Bugfix: The contextual help link was not "full" selectable (covered by the tab nav)
* Bugfix: ObjectCache file existent check failed (triggers a php warning  `unlink(...) No such file or directory ..`
* The `readme.txt` (WordPress plugin repository) is generated from the markdown file `README.md`, `FAQ.md` and `CHANGES.md` (GitHub style)

### 2.6 ###
* Added: Settings page link to the plugin page (metadata row)
* Added: Link to author's Twitter Channel (latest Enlighter updates/news)
* Added: [EnlighterJS v2.5](http://enlighterjs.andidittrich.de/)
* Added: Language support for ini files
* Added: Language support for AVR-Assembler
* Added: XML Namespace highlighting
* Added: Links to the Language Examples to the `README.txt` file
* Bugfix: Highlighting of multi-line XML/HTML tags failed - thanks to [Suleiman19 on GitHub](https://github.com/AndiDittrich/WordPress.Enlighter/issues/8)
* Renamed the EnlighterJS files to `EnlighterJS.min.css` and `EnlighterJS.min.js`

### 2.5 ###
* Added LIVE Preview-Mode to the Theme-Customizer (requires a browser with enabled pop-up windows)
* Added Preview-Mode screenshot
* Renamed: MooTools js file to `mootools-core-yc.js` (removed the version string)
* Updated: the pot/language files

### 2.4 ###
* Added: Compatibility to the [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) Plugin
* Added: Frontend Visual Editor Integration using the [wp_editor](http://codex.wordpress.org/Function_Reference/wp_editor) feature - requested on [WordPress Forums](https://wordpress.org/support/topic/inserting-button-to-frontend-tinymce)
* Added: Additional check to the ObjectCache to ensure that it's writeable whe
* Removed: WordPress 3.8 Visual Editor compatibility - Enlighter now requires WordPress >= 3.9 (TinyMCE 4)
* Hardened the Enlighter TinyMCE Plugin 
* Bugfix: With disabled option "Show Linenumbers" the Visual Editor Plugin will crash the TinyMCE Editor - [Thanks to ryansnowden on GitHub](https://github.com/AndiDittrich/WordPress.Enlighter/issues/7)
* Bugifx: In case of a missconfigured WordPress installation (disabling the `admin_print_scripts` hook), the Visual-Editor-Plugin will crash the TinyMCE editor - [Thanks to Nikodemsky on WordPress Forums](https://wordpress.org/support/topic/switching-between-visualtext-editor-is-broken-loading-code)
* Bugfix: Closed possible XSS vector within the HTML generator (authenticated users who **can edit** content were able to inject html code) - this is not a security issue because such users can insert HTML code by default.

### 2.3 ###
* Added insert-option for "Align-Left-Indentation" - all leading tabs got replaced by spaces and the minimum indent is removed from each line - this is a usefull feature when pasting code-snippets (the "Code-Indent" option has to be set to n-Spaces!)
* Added insert-option "block/inline" to easily insert inline code - feature requested on [WordPress Forums](http://wordpress.org/support/topic/code-indent-removed-by-wordpress-editor?replies=9#post-5652635)
* Added cache-directory check to ensure that it's writeable as well as a `Autofix` function which automatically set's the permissions of the cache-directory on user request (+w for user + group).
* Added Language-Type "generic" to selection menu
* Added EnlighterJS 2.4
* Added Theme "Classic"
* Added Theme "Eclipse"
* Added Theme "Beyond"
* Added Language "Diff" for changelogs
* Added: License Informations to settings-page footer
* Added: Info of available CDN locations (full url)
* Added: Additional user-role check (administrator + `manage_options` required)
* Added: [Contextual Help](http://codex.wordpress.org/Adding_Contextual_Help_to_Administration_Menus) based help/usage/informations
* Added: Checks the availability of the EnlighterJS library before initializing - this will avoid errors caused by missing scripts
* Added: Option to include the required javscript config as external file, within wp_footer or wp_head
* Added: Support for external/custom EnlighterJS Themes - feature requested on [WordPress Forums](https://wordpress.org/support/topic/install-new-theme-2)
* Updated MooTools (local+CDN) to v1.5.1
* Removed Setting "Config-Type" - Javascript based initialization is now used
* Changed the `wpAutoP` filter priority back to 10 as default (no changes) - this will [avoid conflicts with other plugins](https://wordpress.org/support/view/plugin-reviews/enlighter?filter=2) - in case you are using shortcodes, you should set it to 12
* Changed: some setting keys got renamed, especially the toolbar buttons - please check your settings
* Bugfix: Theme-Customizers CSS cache got removed on plugin upgrade - added automatical CSS recreation/cache check
* Bugfix: Entities didn't got escaped by using the "Code Insert Dialog" - thank's to [nextchi on GitHub](https://github.com/AndiDittrich/WordPress.Enlighter/issues/6) and [Mathias on WordPress Forums](https://wordpress.org/support/topic/html-indention-not-working-as-it-should)
* New settings page - now matches WordPress corporate UI style
* Removed WordPress <= 3.7 compatibility mode/legacy UI style
* Bugfix: Added some missing I18n namespaces
* Many internal changes/improvements

### 2.2 ###
* Added "Code Insert Dialog" to avoid copy-auto-formatting issues - feature requested on [WordPress Forums](http://wordpress.org/support/topic/code-indent-removed-by-wordpress-editor?replies=9#post-5652635)
* Added "Enlighter Settings Button" to control the Enlighter Settings (highlight, show-linenumbers, ..) directly from the Visual-Editor - just click into a codeblock and the button will appear (requires WordPress >=3.9)
* Added Enlighter Toolbar Menu-Buttons
* New Visual-Editor integration style
* Bugfix: Added missing codeblock-name for "C#"

### 2.1 ###
* Added EnlighterJS 2.2
* Added language support for C# (csharp) [provided by Joshua Maag](https://github.com/joshmaag)
* Bugfix: Indentation of first line got lost - thanks to [cdonts](http://wordpress.org/support/topic/no-indentation-in-the-first-line?replies=2)

### 2.0 ###
* Added EnlighterJS 2.1
* Added Inline-Syntax-Highlighting
* Added new Theme "Enlighter"
* Added Inline-Highlighting support to the Visual-Editor
* Added setting "Show Linenumbers"
* Added shortcode attribute "linenumbers" the force the visibility for each codeblock - feature requested on [GitHub](https://github.com/AndiDittrich/WordPress.Enlighter/issues/1)
* Added shortcode attribute "offset" to set the start-index of line-number-counting - feature requested on [WordPress Forums](http://wordpress.org/support/topic/feature-request-initial-start-line-number?replies=2)
* Added Inline-CSS-Selector setting
* Added an optional "raw-code-button" as well as customization options for the appearing Raw-Code-Panel
* Added build-script to generate Theme-Templates required by the ThemeCustomizer directly from the CSS files
* Added seperate token settings for "font-style" and "font-weight"
* Improved Theme-Generator: only one CSS file is included instead of two
* Moved option "Language Shortcodes" to "Advanced Options"
* Removed setting "Output-Style" (replaced by Show-Linenumbers)
* Removed waste Theme-Customizer setting "Line Number Styles -> Line height"
* Bugfix: "Loading Theme Style" doesn't set "text-decoration" corretly

### 1.8 ###
* Added: Visual-Editor (TinyMCE) Integration (**optionally** - you can turn it off on the settings page)
* Added: Serbo-Croatian Translation sr_RS (Thank`s to Borisa Djuraskovic from webhostinghub.com)
* Bugfix: Visual-Editor integration will avoid auto-whitespace-removing issues
* Improved: Added new Screenshots

### 1.7 ###
* Added: Environment Pre-Check (PHP 5.3 requirement!)

### 1.6 ###
* Added: Support for new WordPress 3.8 UI design
* Added: CDNJS Service (Cloudflare) as CDN provider for MooTools @see http://cdnjs.com/
* Added: **I18n** (Internationalization) support (settings page)
* Added: I18n generation tools
* Added: POT file for additional translations
* Added: German translation (de_DE)
* PHP Namespaces used to isolate plugin (PHP >= 5.3 required!)
* Improved Plugin backend structure
* Changed: Admin CSS+JS files are moved to ``resources/admin/``
* Changed: Replaced table layout of settings page
* Bugfix: "Load Theme styles" selects wrong items as default style
* Bugfix: ColorPicker elements doesn't get initialized

### 1.5 ###
* Bugfix: The plugin now modifies the priotiry of ``wpautop`` filter to avoid unrequested linebreaks (**optionally** - you can turn it off on the settings page) @see https://github.com/AndiDittrich/WordPress.Enlighter/issues/2 - thanks to **ankitpokhrel**
* Added EnlighterJS 1.8
* Added line based marking to point special lines - just add the attribute ``highlight="1,2-5,9"`` to the shortcode to mark line 1,2,3,4,5,9. The line-color is configurable within the ThemeCustomizer - feature requested on WordPress.org Forum
* Added the ability to set custom hover colors within the ThemeCustomizer as well as custom line highlighting colors
* Improved settings page, new design

### 1.4 ###
* Added EnlighterJS 1.7
* Added Language-Aliases for use with generic shortcode
* Fix: CSS Hotfix for bad linenumbers in Chrome @see http://wordpress.org/support/topic/bad-line-numbers-in-chrome?replies=3 - thanks to **cdonts**

### 1.3 ###
* Bugfix: CSS Selector got ignored when using metadata-based initialization (all "pre"-tags are highlighted)
* Added EnlighterJS 1.6
* Added "RAW" language - code is not highlighted/parsed

### 1.2 ###
* Added EnlighterJS 1.5.1
* Added language support for NSIS (Nullsoft Scriptable Install System)

### 1.1 ###
* First public release
* Includes EnlighterJS 1.4