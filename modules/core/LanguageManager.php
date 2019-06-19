<?php

namespace Enlighter;

class LanguageManager{

    // list of build-in languages
    // Format: Description => Name
    private static $_languages = array(
        'Generic Highlighting' => 'generic',
        'Avr Assembly' => 'avrasm',
        'Generic Assembly' => 'asm',
        'C' => 'c',
        'C++' => 'cpp',
        'C#' => 'csharp',
        'CSS' => 'css',
        'Cython' => 'cython',
        'CordPro' => 'cordpro',
        'Diff' => 'diff',
        'Dockerfile' => 'dockerfile',
        'Groovy' => 'groovy',
        'Go' => 'golang',
        'HTML' => 'html',
        'Ini/Conf Syntax' => 'ini',
        'Java' => 'java',
        'Javascript' => 'js',
        'JSON' => 'json',
        'Kotlin' => 'kotlin',
        'LESS' => 'less',
        'LUA' => 'lua',
        'Markdown' => 'md',
        'Matlab/Octave' => 'matlab',
        'NSIS' => 'nsis',
        'PHP' => 'php',
        'PowerShell' => 'powershell',
        'Prolog' => 'prolog',
        'Python' => 'python',
        'Ruby' => 'ruby',
        'Rust' => 'rust',
        'SCSS' => 'scss',
        'Shellscript' => 'shell',
        'SQL' => 'sql',
        'Squirrel' => 'squirrel',
        'Swift' => 'swift',
        'TypeScript' => 'typescript',
        'VHDL' => 'vhdl',
        'VisualBasic' => 'visualbasic',
        'XML' => 'xml',
        'YAML' => 'yaml',
        'RAW Code' => 'raw'
    );

    // fetch the language list
    public static function getLanguages(){
        return apply_filters('enlighter_languages', self::$_languages);
    }
}