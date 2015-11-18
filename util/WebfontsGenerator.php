<?php

// Google Webfonts Generator API
// Generate a Font-List to include webfonts via <link /> (https://developers.google.com/fonts/docs/getting_started#Overview)

// switch working directory
chdir(dirname(__DIR__));

// get api key
$apiKey = file_get_contents('.credentials/webfonts.key');

// send api request
$responseData = file_get_contents('https://www.googleapis.com/webfonts/v1/webfonts?key='.$apiKey);

// decode json data
$webfontsData = json_decode($responseData, true);

// json output data: font-name => identifier
$monospaceList = array();

// generate font resource list
foreach ($webfontsData['items'] as $font) {
    // filter monospace
    if ($font['category'] != 'monospace'){
        continue;
    }

    // filter variants - only regular, italic, bold, bold-italic variants are required!
    $variants = array_filter($font['variants'], function($variant){
        $allowed = array('regular', 'italic', '700', '700italic');

        return in_array($variant, $allowed);
    });

    // extract data
    $name = preg_replace('/\s+/', '+', $font['family']);
    $variants = implode(',', $variants);

    $monospaceList[$font['family']] = $name . ':' . $variants;

    echo 'Webfont [', $font['family'], ']', PHP_EOL;
}

// generate code
$code = '<?php' . PHP_EOL . '// AUTO GENERATED FILE - DO NOT EDIT' . PHP_EOL;
$code .= 'namespace Enlighter;' . PHP_EOL . 'class GoogleWebfontResources{' . PHP_EOL;
$code .= 'public static function getMonospaceFonts(){' . PHP_EOL;
$code .= 'return ' . var_export($monospaceList, true). ';' . PHP_EOL;
$code .= '}' . PHP_EOL;
$code .= '}' . PHP_EOL;

file_put_contents('class/GoogleWebfontResources.php', $code);