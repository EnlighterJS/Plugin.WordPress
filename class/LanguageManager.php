<?php

namespace Enlighter;

class LanguageManager{

    // list of build-in languages
    // Format: Description => Name
    private static $_languages = array(
        'Generic Highlighting' => 'generic',
        'CSS (Cascading Style Sheets)' => 'css',
        'HTML (Hypertext Markup Language)' => 'html',
        'Java' => 'java',
        'Javascript' => 'js',
        'JSON' => 'json',
        'Markdown' => 'md',
        'PHP' => 'php',
        'Python' => 'python',
        'Ruby' => 'ruby',
        'Batch MS-DOS' => 'msdos',
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
        'Kotlin' => 'kotlin',
        'Squirrel' => 'squirrel',
        'Ini/Conf Syntax' => 'ini',
        'RAW Code' => 'raw',
        'No Highlighting' => 'no-highlight'
    );

    // fetch the language list
    public static function getLanguages(){
        return apply_filters('enlighter_languages', self::$_languages);
    }
}