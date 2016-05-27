<?php

// Extract the latest changes from CHANGES.md and render it using the GitHUb Markdown API

// switch working directory
chdir(dirname(__DIR__));

// get changelog
$changelog = file_get_contents('CHANGES.md');

// find the first L2 Header
preg_match('/###\s+[\w\.]+\s+###([\S\s]+)###\s+[\w\.]+\s+###/U', $changelog, $matches);

// found ?
if (count($matches) != 2){
    echo "Cannot extract latest changes";
    exit(1);
}

// render html using the GitHub GFM API
$html = renderGFM($matches[1], 'AndiDittrich/WordPress.Enlighter');

// store content
file_put_contents('views/admin/Changes.html', $html);

/**
 * Render Markdown content using the GitHub v3 Markdown API
 * @see https://developer.github.com/v3/markdown/
 * @source https://andidittrich.de/2016/05/render-markdown-gfm-documents-online-using-the-github-v3-api
 * @license: MIT
 * @return string(html)
 */
function renderGFM($text, $repositoryContext = null){

    // create the payload
    // @see https://developer.github.com/v3/markdown/
    $postdata = json_encode(
        array(
            'text' => $text,
            'mode' => ($repositoryContext != null ? 'gfm' : 'markdown'),
            'context' => $repositoryContext
        )
    );

    // prepare the HTTP 1.1 POST Request
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'protocol_version' => '1.1',
            'user_agent' => $repositoryContext,
            'header'  => array(
                'Content-type: application/x-www-form-urlencoded;charset=UTF-8',
                'Connection: close',
                'Accept: application/vnd.github.v3+json'
            ),
            'content' => $postdata
        )
    );

    // send request
    return file_get_contents('https://api.github.com/markdown', false, stream_context_create($opts));
}

