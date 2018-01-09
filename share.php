<?php

/**
 * Purpose of this file is to find the album and share it for OG/META info
 * Any links is good links yeah?
 */
if (!isset($_GET['album']) || $_GET['album'] < 1 || $_GET['album'] > 365) {
    header('Location: /');
}
$data = file_get_contents(dirname(__FILE__) . "/app/data/target.json");
$data = json_decode($data,1);

if ($key > count($data)) {
    header('Location: /');
}
$keys = array_keys($data);
$key = $_GET['album'] - 1; //0-index

$album = $data[$keys[$key]];
if (empty($album)) {
    header("Location: /");
}

$title = $key+1 . "/365 - " . $album['artist'] . " - " . $album['title'];
$img = $album['image'];
$desc = "For every day this year, I am listening to a different music album, just like this one.  Click here to see what else I am listening to.";
$url = "https://www.365daysofmusic.com/#day-" . ($key+1);
?>
<html>
<head>
<title><?php echo $title; ?></title>
<meta name="description" content="<?php echo $desc; ?>" />
<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:type" content="website" />
<meta property="og:description" content="<?php echo $desc; ?>" />
<meta property="og:url" content="https://www.365daysofmusic.com/album/<?php echo $key+1; ?>" />
<meta property="og:image" content="<?php echo $img; ?>" />
<meta property="og:image:width" content="640" />
<meta property="og:image:height" content="640" />
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KWG4PCB');</script>
<!-- End Google Tag Manager -->

</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KWG4PCB"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<script type="text/javascript">
window.location.href = "<?php echo $url; ?>";
</script>
</body>
</html>
