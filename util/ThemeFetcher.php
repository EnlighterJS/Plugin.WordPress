#!/usr/bin/php
<?php
/**
 * Extracts CSS settings from themes and convert it into Javascript
 * @author Andi Dittrich
 * @version 1.0
 * @license MIT Style X11 License
 */

// change to current dir
chdir(__DIR__);

// yaml parser
require_once('spyc-yaml/Spyc.php');

// get output dir
$outputDir = '../views/themes/';

$themes = explode(' ', 'Enlighter Godzilla Beyond Classic MooTwo Eclipse Droide Minimal Atomic Git Mocha MooTools Panic Tutti Twilight');

// github fetching url
$gitHubURL = 'https://raw.githubusercontent.com/AndiDittrich/EnlighterJS/master/Source/Themes/';

// js Theme Template
$ThemeStyles = array();

// fetch & modify themes
foreach ($themes as $theme){
	echo 'Processing Theme [', $theme, "]\n";
	
	// fetch theme
	$themeContent = file_get_contents($gitHubURL.$theme.'.css');
	
	// theme data (yaml)
	$themeMetaData = array();
	
	// extract yaml header
	$themeContent = preg_replace_callback('#/\*\s*^---(.*?)^\.\.\.\s*^\*/#sm', function($matches) use (&$themeMetaData){
		$themeMetaData = Spyc::YAMLLoadString($matches[1]);		
		return '';
	}, $themeContent);

	// replace css classes with wordpress "wpcustom" class
	$themeContent = str_replace('.'.strtolower($theme), '.wpcustom', $themeContent);
	
	// token storage
	$tokens = array();
	
	// find symbol styles
	$themeContent = preg_replace_callback('/^\.wpcustomEnlighterJS\s*\.([a-z]{2}[0-9])\s*{(.*?)}\s*$/Um', function($matches) use (&$tokens){
		$cssClass = trim($matches[1]);
		$cssRules = parseCssRules($matches[2]);
		
		// format: foreground color, background color, text-decoration, font-weight, font-style 
		$tokens[$cssClass] = array(
			(isset($cssRules['color']) ? $cssRules['color'] : null),
			(isset($cssRules['background-color']) ? $cssRules['background-color'] : null),
			(isset($cssRules['text-decoration']) ? $cssRules['text-decoration'] : null),
			(isset($cssRules['font-weight']) ? $cssRules['font-weight'] : null),
			(isset($cssRules['font-style']) ? $cssRules['font-style'] : null)
		);
		
		// cleanup
		return '';
	}, $themeContent);
	
	// store theme
	$ThemeStyles[strtolower($theme)] = array(
			'tokens' => $tokens,
			'special' => array(
				'hoverColor' => $themeMetaData['styles']['hover']['background-color'],
				'highlightColor' => $themeMetaData['styles']['specialline']['background-color']
			),
			'basestyle' => $themeMetaData['styles']['base'],
			'linestyle' => $themeMetaData['styles']['line'],
			'rawstyle' => $themeMetaData['styles']['raw']
	);
	
	// remove empty lines
	$themeContent = preg_replace('/[\r\n]+/', "\r\n", $themeContent);
	
	// store modified content
	file_put_contents($outputDir.strtolower($theme).'.css', $themeContent);
}

// store ThemeStyles
file_put_contents('../resources/admin/ThemeStyles.js', '/* AUTO GENERATED FILE - DO NOT EDIT*/var Enlighter_ThemeStyles = '.json_encode($ThemeStyles).';');


function parseCssRules($cssData){
	$cssElements = explode(';', $cssData);
	$cssRules = array();

	// process css elements and extract rules
	foreach ($cssElements as $el){
		if (strlen(trim($el))>4){
			$parts = explode(':', trim($el));
			$cssRules[trim($parts[0])] = trim($parts[1]);
		}
	}

	return $cssRules;
}
?>