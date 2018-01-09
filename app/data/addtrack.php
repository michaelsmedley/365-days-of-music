<?php
/**
 * This should automate adding tracks to the system
 * TODO: Need to generate access token
 */

/**
 * Do we have req posts?
 */
if (!isset($_POST['password']) || !isset($_POST['track'])) {
    exit("not all post params passed.  need track and password");
}

/**
 * First of all, check password
 */
if (sha1($_POST['password']) !== "676d536e07497438ec711800ae5e6093ba2c0829") {
    exit("wrong password, sorry");
}

/**
 * Valid url?
 */
if (!strstr($_POST['track'], "https://open.spotify.com/album/")) {
    exit("invalid spotify url");
}

//so, now we need to get the list of ids already added, check if this one exists, then append and rewrite
//do check to spotfiy api, add in new album info, add shoutout if necessary

$new_album = substr($_POST['track'], strrpos($_POST['track'], "/")+1);
$ids = file_get_contents("albums.json");
$ids = json_decode($ids,1);
$year = date('Y');

if (in_array($new_album, $ids[$year])) {
	exit("The album has already been added");
}

//So, here we can do a spotify call, get the album, and append to a file
require_once dirname(__FILE__) . '/../../spotify/src/SpotifyWebAPI.php';
require_once dirname(__FILE__) . '/../../spotify/src/Request.php';
require_once dirname(__FILE__) . '/../../spotify/src/Session.php';

define('SPOTIFY_ID', 'id-here');
define('SPOTIFY_SECRET', 'secre-here');
define('REDIRECT_URI', 'http://' . $_SERVER['HTTP_HOST'] . '/callback.php');

$spotify_session = new SpotifyWebAPI\Session(
    SPOTIFY_ID,
    SPOTIFY_SECRET,
    REDIRECT_URI . '?service=spotify'
);
$spotify_api = new SpotifyWebAPI\SpotifyWebAPI();
$spotify_api->setAccessToken($token);
$target = 'target.json';

if(!file_exists($target)) {
    file_put_contents($target, json_encode([]));
}

$target_data = json_decode(file_get_contents($target),1);
try {
    $album = $spotify_api->getAlbum($new_album);
    $target_data[$new_album] = [
        'url'=>$album->external_urls->spotify,
        'image' => $album->images[0]->url,
        'title' => $album->name,
        'artist' => $album->artists[0]->name
    ];

    //if we have a shoutout, add it
    if (isset($_POST['recommend'])) {
        $target_data[$new_album]['shoutout'] = $_POST['recommend'];
    }
    file_put_contents($target, json_encode($target_data));

//once that's been done, add the album id to the original list of albums
array_push($ids[$year], $new_album);
file_put_contents("albums.json", $ids);
echo 'Album and target files should be updated now.  <a href="addtrack.html">Click here to add another album</a>';
exit();
} catch (Exception $e) {
echo $e->getMessage();
exit;
}
