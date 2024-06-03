<?php

// require('generalHelper.php');

define('MEK_IMAGE', ' > div > p > img'); // src
define('MEK_MAIN_IMAGE', '.wp-caption > img'); // src


define('MEK_MAIN_POST', '.elementor-element.elementor-element-3a5f8c1.elementor-widget.elementor-widget-theme-post-content');
define('MEK_CONTENT', ' > div > p');

define('FM93_IMAGE1', ' > div > img');
define('FM93_IMAGE2', ' > p > img');

define('FM93_MAIN_IMAGE', '.img-responsive');
define('FM93_GALLERY', '.elementor-icon-list-text');

define('FM93_MAIN_POST', '.main.post');
define('FM93_CONTENT', ' > p');

// $mekomiImage = ' > div > p > img'; // src
// $mekomiMainImage = '.wp-caption > img'; // src
// $mekomiMainPost = '.elementor-element.elementor-element-3a5f8c1.elementor-widget.elementor-widget-theme-post-content';
// $mekomiContent = ' > div > p';
// $fm93MainImage = '.img-responsive'; // src
// $fm93Image = ' > div > img'; // src
// $fm93Gallery = ''; // https://www.93fm.co.il/radio/941942/
// $fm93MainPost = '.main.post';
// $fm93Content = ' > p';

function scrape93($item, $client) {

    $title = $item->title;
    $des = $item->description;
    $des = strtok(substr($des, 3), '<');
    echo 'des1: '. $des. "\n";
    $date = $item->pubDatee;
    $link = $item->link;
    echo $link . "\n";

    // check if post uploaded already
    // if (checkPostExists($link)) {
    //     echo 'Article already uploaded';
    //     return null;
    // }
    
    $crawler = $client->request('GET', $link);
    $tags = array();
    // Get article tags
    getRssTags($tags, $item);

    // Add author as tag
    getRssAuthor($tags, $item);
    echo "Num of Tags: ". count($tags). "\n";

    // Get article images
    $images = array();
    if (str_contains($link, 'mekomi')) {
        $content = getCrawlerContent(MEK_MAIN_POST . MEK_CONTENT, $crawler);
        getImages($images, MEK_MAIN_IMAGE, $crawler, 'src'); // Main Image
        getImages($images, MEK_MAIN_POST . MEK_IMAGE, $crawler, 'src'); // Content Images
        
    }
    else {
        echo"her\n";
        $content = getCrawlerContent(FM93_MAIN_POST . FM93_CONTENT, $crawler);
        getImages($images, FM93_MAIN_IMAGE, $crawler, 'src'); // Main Image
        getImages($images, FM93_MAIN_POST . FM93_IMAGE1, $crawler, 'src'); // Content Images
        getImages($images, FM93_MAIN_POST . FM93_IMAGE2, $crawler, 'src'); // Content Images
        // getImages($images, $fm93Content, $crawler, 'href'); // Gallery
    }
    echo "Num of Images: " .count($images). "\n";
    $post = array(
        'post_title' => $title,
        'post_type' => 'post',
        'post_content' => $content,
        'post_status' => 'publish',
        'post_excerpt' => $des,
        // 'post_date' => $postdate,
    );
    return [$post, $link, $tags, $images];

}