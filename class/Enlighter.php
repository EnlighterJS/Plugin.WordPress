<?php

/**
	Enlighter Class
	Version: 2.12
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://andidittrich.de/go/enlighterjs
	License: MIT X11-License
	
	Copyright (c) 2013-2016, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class Enlighter{
	// singleton instance
	private static $__instance;
	
	// shortcode handler instance
	private $_shortcodeHandler;
	
	// resource loader instamce
	private $_resourceLoader;
	
	// settings utility instance
	private $_settingsUtility;

	// cahce manager instance
	private $_cacheManager;
	
	// theme loader/manager
	private $_themeManager;

	// enlighter config keys with default values
	private $_defaultConfig = array(
		'embedEnlighterCSS' => true,
		'embedEnlighterJS' => true,
		'embedExternalThemes' => true,

		'mootoolsSource' => 'local',
		'jsType' => 'inline',
        'jsPosition' => 'header',
		'defaultTheme' => 'enlighter',
		'defaultLanguage' => 'generic',
		'languageShortcode' => true,
        'shortcodeMode' => 'modern',
		'indent' => 2,
		'linenumbers' => 'true',
		'hoverClass' => 'hoverEnabled',
		'selector' => 'pre.EnlighterJSRAW',
		'selectorInline' => 'code.EnlighterJSRAW',

        'editorFontFamily' => '"Source Code Pro", "Liberation Mono", "Courier New", Courier, monospace',
        'editorFontSize' => '0.7em',
        'editorLineHeight' => '0.9em',
        'editorFontColor' => '#000000',
        'editorBackgroundColor' => '#f9f9f9',
        'editorAutowidth' => false,
        'editorQuicktagMode' => 'html',
        'editorAddStyleFormats' => true,

		'customThemeBase' => 'standard',
		'customFontFamily' => 'Monaco, Courier, Monospace',
		'customFontSize' => '12px',
		'customLineHeight' => '16px',
		'customFontColor' => '#000000',

		'customLinenumberFontFamily' => 'Monaco, Courier, Monospace',
		'customLinenumberFontSize' => '10px',
		'customLinenumberFontColor' => '#000000',

		'customLineHighlightColor' => '#f0f0ff',
		'customLineHoverColor' => '#f0f0ff',

		'customRawFontFamily' => 'Monaco, Courier, Monospace',
		'customRawFontSize' => '12px',
		'customRawLineHeight' => '16px',
		'customRawFontColor' => '#000000',
		'customRawBackgroundColor' => '#000000',
			
		'wpAutoPFilterPriority' => 'default',
		'enableTranslation' => true,
		'enableTinyMceIntegration' => true,
		'enableFrontendTinyMceIntegration' => false,
		'rawButton' => true,
		'windowButton' => true,
		'infoButton' => true,
		'rawcodeDoubleclick' => false,
		'enableInlineHighlighting' => true,

        'cryptexEnabled' => false,
        'cryptexFallbackEmail' => 'mail@example.tld'
	);
	
	// list of micro shortcodes (supported languages)
	private $_supportedLanguageKeys = array(
		'CSS (Cascading Style Sheets)' => 'css',
		'HTML (Hypertext Markup Language)' => 'html',
		'Java' => 'java',
		'Javascript' => 'js',
        'JSON' => 'json',
		'Markdown' => 'md',
		'PHP' => 'php',
		'Python' => 'python',
		'Ruby' => 'ruby',
		'Shell Script' => 'shell',
		'SQL' => 'sql',
		'XML' => 'xml',
		'C' => 'c',
		'C++' => 'cpp',
		'C#' => 'csharp',
		'RUST' => 'rust',
        'LUA' => 'lua',
		'Matlab' => 'matlab',
		'NSIS' => 'nsis',
		'Diff' => 'diff',
		'VHDL' => 'vhdl',
		'Avr Assembly' => 'avrasm',
        'Generic Assembly' => 'asm',
        'Squirrel' => 'squirrel',
		'Ini/Conf Syntax' => 'ini',
        'RAW Code' => 'raw',
		'No Highlighting' => 'no-highlight',
		'Generic Highlighting' => 'generic'	
	);
	
	// list of supported themes
	// Enlighter Godzilla Beyond Classic MooTwo Eclipse Droide Git Mocha MooTools Panic Tutti Twilight
	private $_supportedThemes = array(
		'Enlighter' => true,
		'Godzilla' => true,
		'Beyond' => true,
		'Classic' => true,
		'MooTwo' => true,
		'Eclipse' => true,
		'Droide' => true,
        'Minimal' => true,
        'Atomic' => true,
        'Rowhammer' => true,
		'Git' => true,
		'Mocha' => true,
		'MooTools' => true,
		'Panic' => true,	
		'Tutti' => true,
		'Twilight' => true	
	);
	
	// list of user themes
	private $_userThemes = array();

    // list of all customizable styles
    private $_customStyleKeys = array(
        'kw1', //'Keyword(Type1)Color', 'enlighter'),
        'kw2', //'Keyword(Type2)Color', 'enlighter'),
        'kw3', //'Keyword(Type3)Color', 'enlighter'),
        'kw4', //'Keyword(Type4)Color', 'enlighter'),
        'co1', //'Slash-StyleCommentsColor', 'enlighter'),
        'co2', //'Multiline-StyleCommentsColor', 'enlighter'),
        'st0', //'Strings(Type1)Color', 'enlighter'),
        'st1', //'Strings(Type2)Color', 'enlighter'),
        'st2', //'Strings(Type3)Color', 'enlighter'),
        'nu0', //'NumberColor', 'enlighter'),
        'me0', //'Method(Type1)Color', 'enlighter'),
        'me1', //'Method(Type2)Color', 'enlighter'),
        'br0', //'BracketColor', 'enlighter'),
        'sy0', //'SymbolColor', 'enlighter'),
        'es0', //'EscapeSymbolColor', 'enlighter'),
        're0', //'RegexColor', 'enlighter'),
        'de1', //'StartDelimiterColor', 'enlighter'),
        'de2'  //'StopDelimiterColor', 'enlighter')
    );

    // static entry/initialize singleton instance
	public static function run(){
		Enlighter::getInstance();
	}
	
	// get singelton instance
	public static function getInstance(){
		// check if singelton instance is available
		if (self::$__instance==null){
			// create new instance if not
			self::$__instance = new self();
		}
		return self::$__instance;
	}
	
	// get available languages
	public static function getAvailableLanguages(){
		return self::getInstance()->_supportedLanguageKeys;
	}
	
	// get available themes
	public static function getAvailableThemes(){
		return array_merge(self::getInstance()->_supportedThemes, self::getInstance()->_themeManager->getUserThemes());
	}
	
	public function __construct(){
		
		// generate theme customizers config keys
		foreach ($this->_customStyleKeys as $key){
			$this->_defaultConfig['custom-color-'.$key] = '';
			$this->_defaultConfig['custom-bgcolor-'.$key] = '';
			$this->_defaultConfig['custom-fontstyle-'.$key] = '';
			$this->_defaultConfig['custom-decoration-'.$key] = '';
		}

        // generate webfont config keys
        $webfonts = \Enlighter\GoogleWebfontResources::getMonospaceFonts();
        foreach ($webfonts as $name => $font){
            $fid = preg_replace('/[^A-Za-z0-9]/', '', $name);
            $this->_defaultConfig['webfonts'.$fid] = false;
        }
		
		// create new settings utility class
		$this->_settingsUtility = new Enlighter\SettingsUtil('enlighter-', $this->_defaultConfig);
		
		// create new cache manager instance
		$this->_cacheManager = new Enlighter\CacheManager($this->_settingsUtility);
		
		// loader to fetch user themes
		$this->_themeManager = new Enlighter\ThemeManager($this->_cacheManager);

		// load language files
		if ($this->_settingsUtility->getOption('enableTranslation')){
			load_plugin_textdomain('enlighter', null, 'enlighter/lang/');
		}
		
		// create new resource loader
		$this->_resourceLoader = new Enlighter\ResourceLoader($this->_settingsUtility, $this->_cacheManager, $this->_themeManager, $this->_supportedLanguageKeys, $this->_customStyleKeys);

		// frontend or dashboard area ?
		if (is_admin()){
			// add admin menu handler
			add_action('admin_menu', array($this, 'setupBackend'));

            // add plugin upgrade notification
            add_action('in_plugin_update_message-enlighter/Enlighter.php', array($this, 'showUpgradeNotification'), 10, 2);

			// force theme cache reload
			$this->_themeManager->forceReload();

            // editor
            $this->_resourceLoader->backendEditor();
		}else{

            // legacy (WordPress based) shortcode handling ?
            if ($this->_settingsUtility->getOption('shortcodeMode') == 'legacy') {
                // create new shortcode handler, register all used shortcodes
                $this->_shortcodeHandler = new Enlighter\LegacyShortcodeHandler($this->_settingsUtility, $this->_supportedLanguageKeys);

            // shortcode handling disabled ?
            }else if ($this->_settingsUtility->getOption('shortcodeMode') == 'disabled'){

            // default - custom shortcode handling
            }else{
                $this->_shortcodeHandler =  new Enlighter\LowlLevelShortcodeProcessor($this->_settingsUtility, $this->_supportedLanguageKeys);
            }

            // frontend resources & extensions
            add_action('init', array($this, 'setupFrontend'));

			// change wpauto filter priority ?
			if ($this->_settingsUtility->getOption('wpAutoPFilterPriority')!='default'){
				remove_filter('the_content', 'wpautop');
				add_filter('the_content', 'wpautop' , 12);
			}
		}
	}

    public function setupFrontend(){
        // load frontend css+js resources
        $this->_resourceLoader->frontendEnlighter();

        // check frontend user privileges
        $canEdit = is_user_logged_in() && current_user_can('edit_posts') && current_user_can('edit_pages');
        if ($canEdit){
            $this->_resourceLoader->frontendEditor();
        }
    }
	
	public function setupBackend(){
		if (current_user_can('manage_options')){
			// add options page
			$optionsPage = add_menu_page(__('Enlighter - Customizable Syntax Highlighter', 'enlighter'), 'Enlighter', 'administrator', 'Enlighter', array($this, 'settingsPage'), 'dashicons-editor-code');

			// add links
            add_filter('plugin_action_links', array($this, 'addPluginPageSettingsLink'), 10, 2);
			add_filter('plugin_row_meta', array($this, 'addPluginMetaLinks'), 10, 2);

            // settings page resources
            add_filter('load-'.$optionsPage, array($this->_resourceLoader, 'backendSettings'));

			// call register settings function
			add_action('admin_init', array($this->_settingsUtility, 'registerSettings'));
			
			// contextual help
			$ch = new Enlighter\ContextualHelp($this->_settingsUtility);
			add_filter('load-'.$optionsPage, array($ch, 'contextualHelp'));
		}
	}

	// links to the plugin website & author's twitter channel ()
	public function addPluginMetaLinks($links, $file){
		// current plugin ?
		if ($file == 'enlighter/Enlighter.php'){
			$links[] = '<a target="_blank" href="https://twitter.com/andidittrich">'.__('News & Updates', 'enlighter').'</a>';
            $links[] = '<a target="_blank" href="http://enlighterjs.org">'.__('EnlighterJS Website', 'enlighter').'</a>';
		}
		
		return $links;
	}

    // links on the plugin page
    public function addPluginPageSettingsLink($links, $file){
        // current plugin ?
        if ($file == 'enlighter/Enlighter.php'){
            $links[] = '<a href="'.admin_url('admin.php?page=Enlighter').'">'.__('Settings', 'enlighter').'</a>';
        }

        return $links;
    }
	
	// options page
	public function settingsPage(){
		// well...is there no action hook for updating settings in wp ?
		if (isset($_GET['settings-updated'])){
			$this->_cacheManager->clearCache();
		}
		
		// permission fix request ?
		if (isset($_GET['cache-permission-fix'])){
			$this->_cacheManager->autosetPermissions();
		}
		
		// generate the theme list
		$themeList = array(
			'WPCustom' => 'wpcustom'
		);
		foreach ($this->_supportedThemes as $t => $v){
			$themeList[$t] = strtolower($t);
		}
		foreach ($this->_themeManager->getUserThemes() as $t => $source){
			$themeList[$t.'/ext'] = strtolower($t);
		}

        // get webfont list
        $webfonts = \Enlighter\GoogleWebfontResources::getMonospaceFonts();
				
		// include admin page
		include(ENLIGHTER_PLUGIN_PATH.'/views/admin/SettingsPage.phtml');
	}

    // gets the current EnlighterJS version from js file
    public static function getEnlighterJSVersion(){
        $content = file_get_contents(ENLIGHTER_PLUGIN_PATH.'/resources/EnlighterJS.min.js');

        // extract version
        $r = preg_match('#^[\S\s]+ (\d.\d+.\d+)#U', $content, $matches);

        // valid result ?
        if ($r!==1){
            return 'NaN';
        }else{
            return $matches[1];
        }
    }

    // plugin upgrade notification
    public function showUpgradeNotification($currentPluginMetadata, $newPluginMetadata){
        // check "upgrade_notice"
        if (isset($newPluginMetadata->upgrade_notice) && strlen(trim($newPluginMetadata->upgrade_notice)) > 0){
            echo '<p style="background-color: #d54e21; padding: 10px; color: #f9f9f9; margin-top: 10px"><strong>Important Upgrade Notice:</strong> ';
            echo esc_html($newPluginMetadata->upgrade_notice), '</p>';
       }
    }
}