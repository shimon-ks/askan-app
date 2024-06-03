<?php

use Goutte\Client;

$client = new Client();
$crawler = $client->request('GET', 'https://www.93fm.co.il');

$patternRegex = '/^https:\/\/www\.93fm\.co\.il\/radio\/\d+\/$/';

$links = $crawler->filter('a')->links();

$numberOfLinks = 20;

$filteredLinks = [];

foreach ($links as $link) {
    $url = $link->getUri();

    if (preg_match($patternRegex, $url)) {
        $filteredLinks[] = $link;
    }

    if (count($filteredLinks) >= $numberOfLinks) {
        break;
    }
}

foreach ($filteredLinks as $link) {
    echo $link->getUri() . PHP_EOL;
}