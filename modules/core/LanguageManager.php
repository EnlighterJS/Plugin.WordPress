<?php

namespace Enlighter;

class LanguageManager{

    private $_cachedData = null;

    // list of build-in languages
    const LANGUAGES = array(
        'generic' => 'Generic Highlighting',
        'raw' => 'Plain text',
        'abap' => 'ABAP',
        'asm' => 'Generic Assembly',
        'apache' => 'Apache httpd',
        'avrasm' => 'Avr Assembly',
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
        'latex' => 'LaTeX',
        'less' => 'LESS',
        'lighttpd' => 'lighttpd',
        'lua' => 'LUA',
        'md' => 'Markdown',
        'matlab' => 'Matlab/Octave',
        'nginx' => 'NGINX',
        'nsis' => 'NSIS',
        'php' => 'PHP',
        'powershell' => 'PowerShell',
        'prolog' => 'Prolog',
        'python' => 'Python',
        'purebasic' => 'Purebasic',
        'qml' => 'QML',
        'r' => 'R',
        'routeros' => 'RouterOS',
        'ruby' => 'Ruby',
        'rust' => 'Rust',
        'scala' => 'SCALA',
        'scss' => 'SCSS',
        'shell' => 'Shellscript',
        'sql' => 'SQL',
        'squirrel' => 'Squirrel',
        'swift' => 'Swift',
        'typescript' => 'TypeScript',
        'vhdl' => 'VHDL',
        'visualbasic' => 'VisualBasic',
        'verilog' => 'Verilog',
        'xml' => 'XML',
        'yaml' => 'YAML'
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