<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/environment.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/post.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/track.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/helpers/api.php');

$posts = get_api_content('posts');
usort($posts, function ($a, $b) {
    return -1 * strcmp($a->created_time_unix, $b->created_time_unix);
});

$tracks = get_api_content('tracks');
usort($tracks, function ($a, $b) {
    return -1 * strcmp($a->released_time_unix, $b->released_time_unix);
});


function asset_path($filename) {
    $manifest_path = $_SERVER['DOCUMENT_ROOT'] . '/rev-manifest.json';

    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), TRUE);
    } else {
        $manifest = array();
    }

    if (array_key_exists($filename, $manifest)) {
        return $manifest[$filename];
    }

    return $filename;
}



function sanitize_output($buffer)
{
    $search = array('/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/(\s)+/s'       // shorten multiple whitespace sequences
    );
    $replace = array('>', '<', '\\1');
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}

ob_start("sanitize_output");
?>

<!DOCTYPE html>
<html>
<head>
    <title>ApiTest</title>
    <link rel="stylesheet" type="text/css" href="/assets/css/<?php echo asset_path('main.css');?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container">
    <div class='row'>
        <?php
        foreach ($tracks as $track) {
            getTrack($track);
        }
        foreach ($posts as $post) {
            getPost($post);
        } ?>
    </div>
</div>
<script type="text/javascript" src="/assets/js/<?php echo asset_path('main.js');?>"></script>
</body>
</html>