<?php

namespace Enlighter;

class LanguageManager{

    private $_cachedData = null;

    // list of build-in languages
    const LANGUAGES = array(
        'generic' => 'Generic Highlighting',
        'avrasm' => 'Avr Assembly',
        'asm' => 'Generic Assembly',
        'c' => 'C',
        'cpp' => 'C++',
        'csharp' => 'C#',
        'css' => 'CSS',
        'cython' => 'Cython',
        'cordpro' => 'CordPro',
        'diff' => 'Diff',
        'dockerfile' => 'Dockerfile',
        'groovy' => 'Groovy',
        'golang' => 'Go',
        'html' => 'HTML',
        'ini' => 'Ini/Conf Syntax',
        'java' => 'Java',
        'js' => 'Javascript',
        'json' => 'JSON',
        'kotlin' => 'Kotlin',
        'less' => 'LESS',
        'lua' => 'LUA',
        'md' => 'Markdown',
        'matlab' => 'Matlab/Octave',
        'nsis' => 'NSIS',
        'php' => 'PHP',
        'powershell' => 'PowerShell',
        'prolog' => 'Prolog',
        'python' => 'Python',
        'ruby' => 'Ruby',
        'rust' => 'Rust',
        'scss' => 'SCSS',
        'shell' => 'Shellscript',
        'sql' => 'SQL',
        'squirrel' => 'Squirrel',
        'swift' => 'Swift',
        'typescript' => 'TypeScript',
        'vhdl' => 'VHDL',
        'visualbasic' => 'VisualBasic',
        'xml' => 'XML',
        'yaml' => 'YAML',
        'raw' => 'RAW Code'
    );

    // fetch the language list
    public function getLanguages(){
        // cached ?
        if ($this->_cachedData === null){
            $this->_cachedData = apply_filters('enlighter_languages', self::LANGUAGES);
        }

        return $this->_cachedData;
    }
}