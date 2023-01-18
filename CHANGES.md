## Changelog ##

### 4.6.1 ###

* Bugfix: `pre` wrapper introduced in `v4.6.0` caused wpautop to handle inline code as block element which breaks the layout. Affected inline codeblocks of compatibility mode filters, markdown and shortcodes. Gutenberg/TinyMCE editors were not affected - thanks to [the community on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/363)

### 4.6.0 ###

* Added: EnlighterJS `v3.6.0`
* Added: css tooltips to the toolbar
* Added: copy-to-clipboard success/error message via tooltip
* Added: language identifier as css class to the outer wrapper `enlighter-l-<resolvedLanguage>`
* Added: `htaccess` language alias to "Apache httpd"
* Changed: WordPress `6.0` required
* Changed: DOM structure has changed: each raw+highlighted code elements are wrapper into `enlighter-code` container
* Changed: DOM structure has changed: removed `enlighter-codegroup-wrapper`
* Changed: toolbar position is now persistent and not affected by horizontal/vertical scrolling
* Bugfix: compatibility filters may cause malformed markup caused by `wpauto` filter. To avoid such issues the placeholders are wrapped into `<pre>` tags - thanks to [dmadison  on GitHub](https://github.com/EnlighterJS/EnlighterJS/issues/351)
* Bugfix: `bash` pound style comment regex also matched variable substituions and special cases - thanks to [maidentaiwan on GitHub](https://github.com/EnlighterJS/EnlighterJS/issues/200)
* Bugfix: `bash` single quoted strings not highlighted
* Bugfix: language alias `js` was processed as `typescript` due to inheritance issue (missing override)

### 4.5.0 ###

* Added: EnlighterJS `v3.5.0`
* Added: EnlighterJS Gutenberg editor plugin `v1.2.0`
* Added: Gutenberg left-align-indentation formatting button
* Added: block transformation from SyntaxHighlighter Evolved
* Added: selected language as button text to the block toolbar
* Added: basic support for windows `batch` / `bat` file syntax
* Added: `dart` language support - thanks to [whiplashoo on GitHub](https://github.com/EnlighterJS/EnlighterJS/pull/185)
* Added: dedicated MariaDB/MySQL language support `mariadb`
* Added: dedicated Oracle Database language support `oracledb` (no special rules yet)
* Added: dedicated MSSQL language support `mssql` (no special rules yet)
* Added: dedicated PostgreSQL language support `postgresql` (no special rules yet)
* Added: `constraints` to `sql`
* Changed: WordPress version `>=5.8` required!
* Changed: splitted sql language into several types/dialects for further extension
* Changed: removed pound style comments from generic `sql` support
* Changed: removed keywords `bigint` and `unsigned` from generic `sql` support
* Changed: `droide` theme color `#009999` to `#007f7f` for higher contrast (WCAG) - thanks to [aphelionz on GitHub](https://github.com/EnlighterJS/EnlighterJS/pull/117)
* Changed: in case of a tokenizer error, the tokenizer will silently fail (output to console) instead of throwing an error - code will still be displayed but related tokens are missing
* Bugfix: Gutenberg inline editing not working on Safari due to required `rangeCount` call - thanks to [phruse on GitHub](https://github.com/EnlighterJS/Plugin.Gutenberg/issues/36)
* Bugfix: Gutenberg inline popover window content is collapsed in recent Gutenberg releases
* Bugfix: Gutenberg editor deprecation warnings related to Toolbar elements
* Bugfix: fixed Gutenberg editor deprecation warning related to widget - thanks to [ Jolle775 on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/348)
* Bugfix: Enlighter low-level shortcode handler regex failure caused invalid content matching for language shortcodes - thanks to [michael8888 on WordPress.org Forums](https://wordpress.org/support/topic/regex-bug/)
* Bugfix: `yaml` integer numbers not highlighted
* Bugfix: `php` variable and function names followed by single/double quotes were interpreted as name - thanks to [oleg-dk on GitHub](https://github.com/EnlighterJS/EnlighterJS/issues/178)
* Bugfix: `php` method names which equals a keyword were highlighted as keyword - thanks to [oleg-dk on GitHub](https://github.com/EnlighterJS/EnlighterJS/issues/181)
* Bugfix: `php` method calls on variables were interpreted as variable name - thanks to [oleg-dk on GitHub](https://github.com/EnlighterJS/EnlighterJS/issues/182)
* Bugfix: `latex` command on first line not recognized - thanks to [MamounImadRajab on GitHub](https://github.com/EnlighterJS/EnlighterJS/issues/169)
* Bugfix: `javascript` regular expressions are match over multiple lines - thanks to [mrdexters1 on Wordpress.org Forums](https://wordpress.org/support/topic/problem-with-the-division-symbol/#post-15257942)
* Bugfix: tokenizer loop limit was calculated in total instead of per-rule
* Bugfix: keywords of `generic` language requires a word-boundary before+after instead of a non word character - thanks to [Irwanda04 on GitHub](https://github.com/EnlighterJS/EnlighterJS/issues/129)
* Bugfix: allow any non-whitespace chars in sql column name literals
* Bugfix: added missing `string` keyword to `c#` type list
* Bugfix: pound and double-slash style comments also matched the last character before the comment

### 4.4.2 ###

* Bugfix: Gutenberg `escapeEditableHTML` throws an error in case the block is empty (attributes.content returns `null`)

### 4.4.1 ###

* Bugfix: a `comma` caused the plugin/wordpress to crash in legacy php `7.2` (lint checks of php 7.3 worked without any issues)

### 4.4.0 ###

* Added: Gutenberg inline highlighting support (RichText format)
* Added: border around Enlighter Gutenberg codeblock (default theme)
* Added: resource urls of the theme-customizer and DRI are resolved by `ResourceManager::getResourceUrl` to allow optional filtering via `enlighter_resource_url`
* Added: support for Jetpack markdown (codeblocks will be excluded from jetpack gfm processing)
* Added: multiversion [travis-ci](https://travis-ci.org) lint checks
* Changed: trailing slash from global constant `ENLIGHTER_PLUGIN_URL` has been removed
* Changed: wp-skeleton updated to `v0.27.0`
* Changed: WordPress `readme.txt` is generated by `wp-skeleton` to avoid format issues (merged readme+changelog)
* Changed: updated Gutenberg plugin to `v1.1.0`
* Changed: removed contextual help content if favor of the [official documentation](https://github.com/EnlighterJS/documentation)
* Bugfix: double slashes are used in the paths of `DRI` and theme customizer resources
* Bugfix: escape sequence were transformed into their characters in recent Gutenberg releases

### 4.3.1 ###

* Bugfix: due to changes in the initialization code in `4.3.0` the global config couldn't be retrieved by the editing plugins

### 4.3.0 ###

* Added: EnlighterJS `v3.4.0`
* Added: EnlighterJS Theme-Customizer `v2.3.0`
* Added: compatibility for `async/defer` script loading (configuration code is merged with the library) (optional)- feature requested [on WordPress.org Forums](https://wordpress.org/support/topic/enlighterjs-resources-not-loaded-yet/)
* Added: javascript based dynamic-resource-invocation to load the EnlighterJS assets dynamically when needed (client side)
* Added: dri compatibility for bbpress and dynamic content (jquery load, jetpack infinity scroll)
* Added: keyword `k11` for annotations
* Added: keyword `x16` for css element selectors
* Added: contextual keywords to csharp - thanks to [mabako on GitHub](https://github.com/EnlighterJS/EnlighterJS/pull/112)
* Added: kotlin string template support
* Added: `r` language support (covered by generic ruleset)
* Added: MikroTik `RouterOS` language support (ros/mikrotik/switchos/routeros/mt)
* Added: pound style comment support to `php`
* Changed: renamed the "raw code" language label to "Plain text"
* Changed: EnlighterJS library (js) is loaded via cache file `cache/enlighterjs.min.js`
* Changed: js/css cache file generation is bound to `enqueue` hook (performance)
* Changed: `wp-skltn` library updated to **0.24.0**
* Changed: for compatibility reasons, the language-shortcodes are disabled by default (this only affects new installations)
* Changed: full semantic versioning tag is used to display the plugin version (may used by additional tools) - thanks to [roy-bongers on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/262)
* Changed: theme cache is only reloaded on Enlighter settings pages (performance) - thanks to [gdragon on Wordpress forums](https://wordpress.org/support/topic/problem-with-_transient_enlighter_userthemes-option/)
* Changed: EnlighterJS::getConfig has been changed to multidimension object to allow additional payloads (this also affects the initialization code)
* Changed: css selector fragment highlighting is limited to the selector itself
* Changed: enhanced css unit parsing
* Changed: `java`, `scala`, `cpp` annotation token changed to `k11`
* Changed: added token `k11` to themes
* Changed: moved generic highlighting rules to `lang/rulesets/generic`
* Changed: `ampersandCleanup` is performed after html escape sequences to eliminate issues related to double unquoting #109
* Changed: toggle raw code label to "Plain text"
* Changed: allowed utf8 characters in `php` variable and function names (side effect of the php parser)
* Removed: `EnlighterJS::dequeue` function (deprecated due to new DRI implementation)
* Bugfix: vhdl single bit highlighting collided with attribute syntax - thanks to [tyriun on GitHub](https://github.com/EnlighterJS/EnlighterJS/issues/106)
* Bugfix: css classnames/ids with hyphens were not recognized
* Bugfix: XML mixins of single/double quotes in attributes failed #108
* Bugfix: dot char within XML attribute names not recognized
* Bugfix: kotlin raw string where not correctly parsed (wrong rule priority)
* Bugfix: highlighting color of `classic` theme not applied due to invalid selectors - thanks to [woolseyj on GitHub](https://github.com/EnlighterJS/EnlighterJS/issues/117)
* Bugfix: EnlighterJS codeblock css selectors cannot contain quotes due to initialization code sanitizing (replace with serialized object)


### 4.2.0 ###

* Added: EnlighterJS `v3.3.0`
* Added: EnlighterJS Theme-Customizer `v2.2.0`
* Added: compatbility mode for [CodeColorer](https://wordpress.org/support/plugin/codecolorer/) - features requested [by ajtruckle on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/222)
* Added: filters to alter the editor config (Gutenberg+TinyMCE) - features requested [by ajtruckle on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/251)
* Added: documentation how to use external themes (linked within contextual help)
* Changed: external themes requires `enlighterjs` as css dependency to avoid styling issues
* Changed: removed Enlighter version string from external themes
* Bugfix: added missing languages to the list (abap,scala,qml,verilog)
* Added: [apachehttpd/htaccess](https://httpd.apache.org/docs/2.4/configuring.html) support
* Added: [lighttpd](https://redmine.lighttpd.net/projects/lighttpd/wiki/Docs_Configuration) support
* Added: [nginx](https://www.nginx.com/resources/wiki/start/topics/examples/full/) support
* Added: [purebasic](https://www.purebasic.com/) support - thanks to [gphilippot on GitHub](https://github.com/EnlighterJS/EnlighterJS/pull/96)
* Added: minimal latex/tex support
* Changed: theme font sizes + line-height are based on relative `em` values - only the font size of the outer wrapper is set explicitly
* Changed: removed "function" highlighting regex from `sql`
* Added: option to customize the raw code pane
* Added: option to set the token base styles inlcuding font-size - feature requested [on GitHub](https://github.com/EnlighterJS/Plugin.ThemeCustomizer/issues/6)
* Added: option to set button background-color and line-height
* Added: tab `defaults` to change global settings
* Changed: moved button related settings to tab `buttons`
* Changed: moved line-number settings to tab `lines`
* Changed: removed tab "fonts"
* Bugfix: highlighted-line hover selector was invalid -> selection of `background-color:hover` was not possible - thanks to [ajtruckle on GitHub](https://github.com/EnlighterJS/Plugin.ThemeCustomizer/issues/4) #4
* Bugfix: removed typos in code edit dialog - thanks to [ajtruckle on GitHub](https://github.com/EnlighterJS/Plugin.TinyMCE/issues/10)


### 4.1.0 ###

* Added: EnlighterJS `v3.2.0`
* Added: EnlighterJS Theme-Customizer `v2.1.0`
* Added: verilog support - thanks to [t123yh on GitHub](https://github.com/EnlighterJS/EnlighterJS/pull/86)
* Added: integer highlighting for sql
* Added: additional usage notes to the theme customizer
* Added: added option to hide EnlighterJS website link/button
* Added: browser based tooltips to the buttons (title attribute)
* Added: fallback language option for backward compatibility (`Enlighter -> Options -> Advanced -> EnlighterJS`)
* Added: additional description to the base theme settings
* Added: methods to retrieve theme+language list from singleton instance
* Changed: removed the `use_smilies` environment check - it has been fixed in WordPress core
* Changed: empty values within customizer take presendence over base theme (empty values cause the css rule to be deleted if exist)
* Bugfix: powershell commands can also expressed lowercase (camel-case not required) - thanks to [krysiekBP on GitHub](https://github.com/EnlighterJS/EnlighterJS/issues/88)
* Bugfix: sql `--` styles comments ignored due to invalid regex - thanks to [petr-hybler on GitHub](https://github.com/EnlighterJS/EnlighterJS/issues/97)

### 4.0.0 ###

**Note: This release is NOT BACKWARD COMPATIBLE. Custom themes will be lost**

* Added: Gutenberg editor plugin v1.0.0
* Added: [EnlighterJS v3](https://enlighterjs.org/)
* Replaced MooTools Framework by native code - requires IE >= 10
* **NEW Highlighting Engines** - every language support file has been rewritten
* New Tokenizer Engine including a two stage analyzer
* New Theme Customizer to allow much more changes
* Excessive Performance Optimizations
* Added: ECMA6 Support to Javascript Engine
* Added: Copy to clipboard button
* Added: horizontal scroll option
* Added: [GO](https://golang.org/) Support
* Added: [RUST](https://www.rust-lang.org/) Support
* Added: [YAML](http://docs.ansible.com/ansible/YAMLSyntax.html) Support
* Added: [Kotlin](https://kotlinlang.org) support
* Added: [TypeScript](https://www.typescriptlang.org/) support
* Added: [Groovy](http://groovy-lang.org) support
* Added: [LESS](http://lesscss.org/) Support
* Added: [SASS/SCSS](http://sass-lang.com/) Support
* Added: [Dockerfile](https://docs.docker.com/engine/reference/builder/) Support
* Added: [CSS Level3](http://www.w3schools.com/css/css3_intro.asp) Support
* Added: [Powershell](https://msdn.microsoft.com/en-us/powershell/mt173057.aspx) Support
* Added: [VisualBasic NET/Classic](https://msdn.microsoft.com/de-de/library/2x7h1hfk.aspx) Support
* Added: [Swift](https://developer.apple.com/library/prerelease/content/documentation/Swift/Conceptual/Swift_Programming_Language/index.html) Support
* Added: [QML](https://doc.qt.io/qt-5/qtqml-syntax-basics.html) Support
* Added: VHDL Support
* Added: ABAP Support (simple)
* Added: Prolog Support (simple)
* Added: Cordpro Support (simple)
* Added: Bootstrap4 Theme
* Added: Dracula Theme (dark, following [draculatheme](https://draculatheme.com/) colors)
* Added: Monokai Theme (dark)
* Added: Crayon compatibility/migration mode
* Added: docker based development mode
* Changed: [WP-Skeleton](https://github.com/AndiDittrich/WP-Skeleton) is used as Plugin Backend Framework 
* Changed: All settings are stored in serialized form in `enlighter-options` instead of single options
* Changed: moved settings page to top-level menu
* Changed: settings are stored as serialized object in the database (single row) instead if row-per-option - major performance enhancement
* Changed: new settings page
* Changed: sessionStorage is used to store the current active tab instead of cookies
* Changed: updated the UI components
* Changed: `wp-skltn` library updated to **0.23.0** - MPL 2.0 License
* Bugfix: colorpicker sets wrong foreground color which caused unreadable input fields (color lightness calculation)
* Cleaned up the internal Plugin Structure
* Removed: `jquery-cookie` dependency
* Removed: MooTools framework
* Removed: Lighter.js legacy themes (Git, Mocha, MooTools, Panic, Tutti, Twilight)
* Removed: most keyword lists from language files (direct regular expressions are used)

### 3.11.0 ###

* Added: notification of next major release ( Enlighter v4)
* Changed: the documentation links are now pointing to the global documentation repository

### 3.10.0 ###

* Added: french translation - thanks to [regisenguehard on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/pull/144)
* Added: simplified chinese translation - thanks to [XFY9326 on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/pull/145)
* Changed: jsdelivr mootools version pointed to `1.6.0`
* Changed: the DRI detection is now limited to the condition `in_the_loop() && is_main_query()`
* Bugfix: the experimental DRI feature failed in case `the_content` filter is called multiple times within a theme - thanks to [willstocks-tech on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/pull/153)

### 3.9.0 ###

* Added: Visual Editor Plugin ([EnlighterJS.TinyMCE](https://github.com/EnlighterJS/Plugin.TinyMCE)) **v3.5.1**
* Added: Gutenberg Editor Plugin - [EnlighterJS/Plugin.Gutenberg on GitHub] **v0.4.0**
* Added: [EnlighterJS v2.13.0](https://enlighterjs.org/)
* Added: automatically transforms legacy Enlighter codeblocks (Classic Editor) to Gutenberg blocks in case the "Convert to Blocks" function is triggered
* Added: Support for jQuery Ajax content (post load) - requested by [wassereimer86 on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/126)
* Added: Description of possible [Plugin Notficiations](https://github.com/EnlighterJS/Plugin.WordPress/tree/master/docs/PluginNotifications.md)
* Changed: moved TinyMCE editor resources (editor plugin) from `enlighter/resources/editor` to `enlighter/resources/tinymce`
* Changed: moved text editor resources (quicktags from `enlighter/resources/editor` to `enlighter/resources/texteditor`
* Changed: moved Gutenberg Editor settings to "Visual Editor" Tab
* Changed: `data-enlighter-linenumbers` attribute is ignored in case it is not set (empty string) to be compatible with the new [Gutenberg Editor Plugin](https://github.com/EnlighterJS/Plugin.Gutenberg) - explicit true/false required
* Changed: Gutenberg Plugin is enabled by default
* Bugfix: TinyMCE footer label may collide with editor content - added additional padding - thanks to [JavierSegoviaCordoba on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/120)
* Bugfix: On Windows platforms, the environment check throws the error message `plugin is located within an invalid path` - thanks to [ginaf40 on WordPress.org Forums](https://wordpress.org/support/topic/enlighter-plugin-error-the-plugin-is-located-within-an-invalid-path)

### 3.8.1 ###

* Changed: release scheme to [Semantic Versioning](https://semver.org/)
* Bugfix: some development files (EnlighterJS v3 prelease) got into the release branch (lowercase filenames) this may cause some issues during the plugin upgrade - I apologize for the inconvenience - thanks to [aguidrevitch on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/119)

### 3.8 ###

* Added: experimental Gutenberg editor integration - [EnlighterJS/Plugin.Gutenberg on GitHub](https://github.com/EnlighterJS/Plugin.Gutenberg)
* Added: keyboard shortcut `+x` to highlight inline code - requested by [dahnark on WordPress.org Forums](https://wordpress.org/support/topic/override-theme-code-style/)
* Added: Visual Editor Plugin ([EnlighterJS.TinyMCE](https://github.com/EnlighterJS/Plugin.TinyMCE)) **v3.5.0**
* Added: Environment check to ensure the plugin is uploaded into `wp-content/plugins/enlighter/`
* Changed: moved experimental settings into panel "Beta"
* Removed: outdated translations
* Bugfix: styles of the "plugin upgrade notification" were broken

### 3.7 ###

* Added: msdos language support (EnlighterJS v2.12.0) - thanks to [audioscavenger on GitHub](https://github.com/EnlighterJS/EnlighterJS/pull/33/commits)
* Added: Visual Editor Plugin ([EnlighterJS.TinyMCE](https://github.com/EnlighterJS/Plugin.TinyMCE)) **v3.4.0**
* Added: EnlighterJS label to codeblocks (bottom-right)
* Added: EnlighterJS.TinyMCE version to the settings page
* Added: filter `enlighter_codeblock_title` to set custom codeblock titles - requested by [w3215 on WordPress.org Forums](https://wordpress.org/support/topic/remove-branding-on-toolbar/)
* Added: environment warning in case smileys are enabled (wordpress core option `use_smilies`) - thanks to [barmen on WordPress.org Forums](https://wordpress.org/support/topic/problem-add-smile-and-url/#post-10341049)
* Changed: Renamed the toolbar-button labels (`Code Insert`, `Code Settings`) - requested by [w3215 on WordPress.org Forums](https://wordpress.org/support/topic/remove-branding-on-toolbar/)
* Changed: Renamed the dialog window titles - requested by [w3215 on WordPress.org Forums](https://wordpress.org/support/topic/remove-branding-on-toolbar/)
* Bugfix: code edit button on codeblocks was broken (settings not saved) - thanks to [Sarah1101 on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/101)

### 3.6 ###
* Added: Dynamics-Resource-Invocation (exprimental option) - Enlighter javascript dependencies are only loaded in case they are needed - features [requested on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/80)
* Added: Compatibility Mode to convert legacy codeblocks (e.g. Jetpack Markdown) to Enlighter recognizable code
* Added: [EnlighterJS v2.12.0](http://enlighterjs.org/)
* Added: language domain path to plugin entry file (used by the WordPress plugin registry)
* Bugfix: **About-Page** redirect on plugin upgrade/activation may be cached by browsers - added nocache header; changed status code to `http-307`
* Changed: splitted the settings page (editing section) into visual-editor and text-editor

### 3.5 ###
* Added: Tab-Indentation Mode to the Visual Editor to align content with the `tab` key
* Added: Kotlin Language Support
* Added: GFM Markdown Inline language identifier syntax
* Added: [EnlighterJS v2.11.1](https://enlighterjs.org/)
* Added: Visual Editor Plugin ([EnlighterJS.TinyMCE](https://github.com/AndiDittrich/EnlighterJS.TinyMCE)) **v3.3.1**
* Added: [Tutorials](https://github.com/EnlighterJS/Plugin.WordPress/blob/master/docs) and Developer docs
* Bugfix: Underscore not allowed in xml tags - thanks to [higginbg on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/72)
* Bugfix: Fixed invalid external links of the help section

### 3.4 ###
* Added: Visual Editor Plugin ([EnlighterJS.TinyMCE](https://github.com/AndiDittrich/EnlighterJS.TinyMCE)) **v3.2.0**
* Added: Fault tolerant initialization code including debug messages (`console.log` output) - this will also avoid javascript initialization errors
* Added: Option to the Code-Edit-Dialog to switch between Inline and Block mode - feature requested [on WordPress.org Forums](https://wordpress.org/support/topic/no-way-to-switch-from-codeblock-to-inline/#post-8539755)
* Changed: The Code-Insert-Dialog size is changed to the current editor viewport size (will cover the whole editor area)
* Changed: Removed the "Sourcecode" Label from Code-Insert-Dialog Textarea to provide a larger input area
* Changed: Added Settings-Update hash to external themes as URL parameter instead of current plugin version (invalidates browser cache by updating the settings)
* Improved: WP Version information is removed from MooTools CDN Sources to avoid additional downloads - thanks to [sixer on WordPress.org Forums](https://wordpress.org/support/topic/query-string-for-jsdelivr/#post-8517461)
* Bugfix: Copy+Paste within a codeblock (Visual Editor) caused a seperation of the selected block
* Bugfix: External JS Components (jetpack-plugin, Startup Code) are not loaded in case EnlighterJS javascript file is excluded
* Bugfix: External Themes won't work because of invalid URLs - thanks to [eliottrobson on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/61)

### 3.3 ###
* Added: [WordPress Multisite](https://codex.wordpress.org/Create_A_Network) support 
* Added: [JSDELIVR](https://www.jsdelivr.com/?query=mootools) as MooTools CDN Source - feature requested on [WordPress.org Forums](https://wordpress.org/support/topic/request-enable-cdn-jsdelivr/)
* Added: Additional check to the About-Page redirection (triggered on plugin activation) to avoid infinite redirects in case of a broken 3rd party options-caching plugin
* Added: Brazilian Portuguese localization (pt_BR) - thanks to [rafajaques on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/pull/50) #50 
* Bugfix: The new cache accessibility check (v3.2) did not work on WIN platform
* Bugfix: PHP 5.3 Compatibility within the LowLevel Shortcode Filter - thanks to [crislv90 on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/52)

### 3.2 ###
* Added: [GFM](https://help.github.com/articles/creating-and-highlighting-code-blocks/) style Markdown support for fenced code blocks
* Added: bbPress support for Markdown fenced code blocks 
* Added: Environment Check to ensure Enlighter is working in a well configured environment
* Added: Filter hook `enlighter_startup` to disable Enlighter on selected pages - feature requested on [WordPress.org Forums](https://wordpress.org/support/topic/best-way-to-dequeue-enlighter-plugin?replies=2) #43
* Added: Filter `enlighter_inline_javascript` - applied to inline javascript which is injected into the page
* Added: Filter `enlighter_frontend_editing`- forced enabling/disabling of the frontend editing functions
* Replaced: PHP-Version-Errorpage by global admin_notice - ensure that **PHP 5.3 or greater** is used to avoid weird errors
* Changed: The autofix permission helper will set the cache directory permissions to **0774**
* Bugfix: PHP Error message was thrown in case a the cache was not writable and a file operation failed
* Bugfix: The cache check did not checked if the directory was accessible
* Bugfix: The autoset permission link was broken since v3.0
* Bugfix: Backtick style code elements of bbPress will break the highlighting

### 3.1 ###
* Added: [EnlighterJS v2.10.1](http://enlighterjs.org/)
* Added: About/News Page which is shown on plugin activation/upgrade
* Added: New Options Page `Extensions` for Enlighter related third-party plugin integration
* Added: Experimental Support for [Jetpack Infinite Scroll](https://jetpack.me/support/infinite-scroll/) - feature requested on [WordPress.org Forums](https://wordpress.org/support/topic/not-working-when-infinite-scroll-is-enabled)
* Added: Experimental [bbPress](https://bbpress.org/) Shortcode support - feature requested by [DevynCJohnson on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/33)
* Added: global constant `ENLIGHTER_PLUGIN_URL` - pre-processed version of `plugins_url('/enlighter/')`
* Added: local enqueue wrappers to the `ResourceLoader.php`
* Added: Enlighter Shortcode support for Text-Widgets
* Added: Enlighter Shortcode support for User Comments
* Added: Options to enable/disable the Editor Quicktags on the Frontend as well as Backend
* Added: HTML Tag restrictions to Visual Editor: disallows any kind of formatting elements (strong, span, em, ..) within code-blocks
* Added: Event `enlighter_init` which is triggered on plugin initialization complete
* Added: Filter `enlighter_themes` to modify the internal theme list - ability to **add** and/or **remove** themes
* Added: Filter `enlighter_languages` to modify the internal language list - ability to **add** and/or **remove** languages
* Added: Filter `enlighter_resource_url` to modify the domain/protocol of related Enlighter resources
* Added: Filter `enlighter_shortcode_filters` to enable shortcodes in specific sections by hooking into 3rd party filters
* Added: Minified Versions of the TinyMCE Plugin
* Changed: The EnlighterJS Config object is now populated as `EnlighterJS_Config` to enable third-party integrations/plugins
* Changed: Moved [Cryptex](https://wordpress.org/plugins/cryptex/) Settings from `Options` to `Extensions`
* Changed: External Plugins (colorpicker, jquery.cookie) are moved from `extern/` to `resources/extern`
* Changed: toolbar button link to http://enlighterjs.org
* Changed: The Plugin is now initialized [on init](https://codex.wordpress.org/Plugin_API/Action_Reference/init) to enable users to hook-in
* Changed: Renamed the Visual Editor configuration object to `EnlighterJS_EditorConfig`
* Changed: Renamed the TinyMCE plugin from `enlighter` to `enlighterjs`
* Changed: Renamed the TinyMCE plugin files to `EnlighterJS.TinyMCE.min.js`, `EnlighterJS.TinyMCE.min.css`
* Changed: Removed the "Advanced" page - settings are moved to "Options"
* Bugfix: The special-line color of the Atomic theme was too dark. changed to 0x392d3b - thanks to [CraigMcKenna on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/24)
* Bugfix: Users with role `author` and `contributor` were not able to set language, theme or other options in Editor Mode (html attributes were stripped by the [KSES filter](http://codex.wordpress.org/Function_Reference/wp_kses_allowed_html))
* Bugfix: Codegroup title cannot be set manually caused by wrong attribute name - thanks to [PixelT on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/34)
* Bugfix: Codeblock edit button does not work in WP 4.5 caused by cross-plugin event-propagation
* Bugfix: Users with role `author` and `contributor` were not able to use the frontend-editor-extension because of missing privileges to edit pages. Condition is changed to `IS_LOGGED_IN AND (CAN_EDIT_POSTS OR CAN_EDIT_PAGES)` - thanks to [Petr on WordPress Forums](https://wordpress.org/support/topic/tinymce-btn-on-frontend-for-non-admin?replies=4#post-8374924)
* Bugfix: HTML Code Fragment within the generated `cache/TinyMCE.css` file caused CSS validation error
* Cleaned up the internal Plugin Structure
* Visual Editor (TinyMCE) Plugin is outsourced to [AndiDittrich/EnlighterJS.TinyMCE](https://github.com/AndiDittrich/EnlighterJS.TinyMCE)

### 3.0 ###
* Added: New robust and fault-tolerant `LowLevel Shortcode Handler` to avoid issues with wpautop filter and unescaped html characters (text mode)
* Added: Visual Editor Customization
* Added: Option to disable Enlighter shortcodes
* Added: Option to use the old/legacy Shortcode handler 
* Added: Shortcode Processor info to the SystemInformation sidebar
* Added: Unique Hash to all cached resources to force cache-update on file-change/settings-update
* Added: Option to cancel WordPress Editor width limit (set to auto)
* Added: [QuickTags](https://codex.wordpress.org/Quicktags_API) to the Text/HTML Editor
* Bugfix: Theme Customizer was not able to modify the special-line-highlighting-color of codeblocks **without** line-numbers - thanks to [CraigMcKenna on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/24)
* Bugfix: MooTools <= 1.5.1 [#2705](https://github.com/mootools/mootools-core/pull/2705) will throw the javascript error `The specified value "t" is not a valid email address` - [updated to v1.6.0](http://mootools.net/blog/2016/01/14/mootools-1-6-0-release) - thanks to [lots0logs on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/25)
* Bugfix: Removed TinyMCE debugging output (written to console)
* Bugfix: The Edit Icon (Visual Editor) is now dynamically positioned based on editor width
* Bugfix: Foreground Color of Theme-Customizers color elements is changed dynamically based on the background color brightness
* Changed: Moved the Enlighter Settings Page to the Top-Level of WordPress Administration Menu
* Changed: Moved Language Shortcode options from advanced settings to editing section
* Changed: Moved TinyMCE Editor options from advanced settings to editing options
* Changed: The Visual Editor Code-block appearance (modernized)
* Changed: Language Titles in the Visual Editor Box are dynamically generated
* Changed: Internal file structure (editor resources)
* Changed: The Menu Slug/URL from `options-general.php?page=enlighter/class/Enlighter.php` to `admin.php?page=Enlighter` - direct, custom links to the settings page **require an update** !
* Changed: Editor Config object is renamed to `Enlighter_EditorConfig`
* Changed: New Resource Manager structure is used
* Changed: Cached files are observed and re-generated if missing
* Replaced: the low-level PHP based ObjectCache by the [WordPress Transient API](https://codex.wordpress.org/Transients_API)
* Dependencies: Updated MooTools to [v1.6.0](http://mootools.net/blog/2016/01/14/mootools-1-6-0-release)
* Deprecated: The "WpAutoP" Filter Priority setting will be removed in the future - the new LowLevel Shortcode Handler will avoid wpautop issues!

### 2.11 ###
* Bugfix: the default option of "Enlighter Config" is now set to "inline" - this may avoid highlighting when upgrading to 2.10 - I apologize for the inconvenience - thanks to [ciambellino on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/21)

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
* Added: option to control the global script position (header/footer) of related javascript files - features requested on [GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/17)
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
* Bugfix: Under some special conditions the tokenizer repeats the last sequence of a codeblock - thanks to [Kalydon](https://github.com/AndiDittrich/EnlighterJS/issues/8) and [dan-j on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/13)
* Bugfix: TinyMCE Editor plugin didn't work in some special cases (use of other editor plugin) - [Thanks to esumit on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/12)
* Bugfix: the final character of highlighted code got removed by the tokenizer engine in case it's a text token - thanks to [dan-j on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/15)
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
* Bugfix: Highlighting of multi-line XML/HTML tags failed - thanks to [Suleiman19 on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/8)
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
* Bugfix: With disabled option "Show Linenumbers" the Visual Editor Plugin will crash the TinyMCE Editor - [Thanks to ryansnowden on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/7)
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
* Bugfix: Entities didn't got escaped by using the "Code Insert Dialog" - thank's to [nextchi on GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/6) and [Mathias on WordPress Forums](https://wordpress.org/support/topic/html-indention-not-working-as-it-should)
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
* Added shortcode attribute "linenumbers" the force the visibility for each codeblock - feature requested on [GitHub](https://github.com/EnlighterJS/Plugin.WordPress/issues/1)
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
* Bugfix: The plugin now modifies the priotiry of ``wpautop`` filter to avoid unrequested linebreaks (**optionally** - you can turn it off on the settings page) @see https://github.com/EnlighterJS/Plugin.WordPress/issues/2 - thanks to **ankitpokhrel**
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
