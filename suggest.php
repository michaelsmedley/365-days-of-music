<?php

$contents = json_decode(file_get_contents('php://input'));

if(json_last_error() !== JSON_ERROR_NONE) {
    header('X-PHP-Response-Code: 400', true, 400);
    exit("Was that really JSON?");
}

if (empty($contents->album)) {
    header('X-PHP-Response-Code: 400', true, 400);
    exit("No album found");
}

//otherwise just send an email
$body = "An album was suggested on 365.\r\nAlbum: " . $contents->album . "\r\nSuggested by: " . $contents->twitter;

@mail("mike@michael-smedley.co.uk", "A new suggestion for 365 albums", $body);

echo "Thanks for your suggestion!";