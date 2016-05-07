Enlighter Filter Hooks
===============================================

The following filters enables an additional plugin customization by adding/removing languages and themes.
You can add the filter hooks within your Theme's [functions.php](https://codex.wordpress.org/Functions_File_Explained) file or a [custom plugin](https://codex.wordpress.org/Writing_a_Plugin).

**NOTICE** The filters only affects the UI components (dropdowns, ..) and **not** the resources!


FILTER::enlighter_themes
-----------------------------------------------

**Description:** Filter to modify the internal themes list

#### Example 1 - Remove a Single Theme ####

```php
function mm_ejs_themes($themes){
    unset $themes['Classic'];
    return $themes;
}

// add a custom filter to modify the theme list
add_filter('enlighter_themes', 'mm_ejs_themes');
```

#### Example 2 - Set a Explicit Theme List ####

```php
function mm_ejs_themes($themes){
    // just show the Classic + Enlighter Theme 
    // Add a new custom theme named my_c_theme - shown as 'MyCustom Lalala Themes' in select boxes
    // Note: Custom themes CSS has to be loaded separately
    return array(
         'Classic' => 'classic',
         'Enlighter' => 'enlighter'
         'MyCustom Lalala Themes' => 'my_c_theme'
     );
}

// add a custom filter to modify the theme list
add_filter('enlighter_themes', 'mm_ejs_themes');
```

FILTER::enlighter_languages
-----------------------------------------------

**Description:** Filter to modify the internal language list

#### Example 1 - Remove some Languages ####

```php
function mm_ejs_languages($langs){
    unset $langs['Java'];
    unset $langs['Javascript'];
    return $langs;
}

// add a custom filter to modify the language list
add_filter('enlighter_languages', 'mm_ejs_languages');
```

#### Example 2 - Add Custom language files ####

```php
function mm_ejs_languages($langs){
    // Add a new custom language named mylang - shown as 'MyCustom Lalala Lang' in select boxes
    // Note: Custom language JS has to be loaded separately
    return array(
        'MyCustom Lalala Lang' => 'mylang',
        
        // html, css, js
        'JS' => 'js',
        'HTML5' => 'html',
        'CSS' => 'css'
    );
}

// add a custom filter to modify the language list
add_filter('enlighter_languages', 'mm_ejs_languages');

// add external language file
function mm_add_custom_ejs_lang() {
    wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/js/custom_ejs_language.js');
}
add_action('wp_enqueue_scripts', 'mm_add_custom_ejs_lang');
```


FILTER::enlighter_resource_url
-----------------------------------------------

**Description:** Filter to modify the resource url

#### Example 1 - Move Resources to CDN ####

```php
function mm_ejs_resources($resourceName){
    return 'https://mycdn.mydomain.tld/wp-enlighter/' . $resourceName;
}

// add a custom filter to modify the resource url's
add_filter('enlighter_resource_url', 'mm_ejs_resources');
```