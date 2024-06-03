<?php

// require('generalHelper.php');

define('JDN_AUTHOR', '.elementor-icon-list-text');
define('JDN_IMAGE', '.elementor-element.elementor-element-1bc3489.elementor-widget.elementor-widget-theme-post-content > div > p> img');
define('JDN_GALLERY', '.elementor-gallery__container > a');


// $jdnAuthorClass = '.elementor-icon-list-text';
// $jdnImage = '.elementor-element.elementor-element-1bc3489.elementor-widget.elementor-widget-theme-post-content > div > p> img';
// $jdnGallery = '.elementor-gallery__container > a';

function scrapeJdn ($item, $client) {

 
    
    $title = $item->title;
    $des = $item->description;
    $date = $item->pubDatee;
    $link = $item->link;
    echo "link: ". $link . "\n";
    echo "title: ". $title . "\n";

    
    // check if post uploaded already
    if (checkPostExists($link)) {
        // echo 'Article already uploaded';
        return null;
    }
    else {
        echo 'UPload now';
    }

    if (strpos($link, 'video') !== false) {
        echo "The link contains the word 'video'.";
        return null;
    } 
    // $link = 'https://www.jdn.co.il/news/2107332/';
    // echo $link;
    
    $crawler = $client->request('GET', $link);

    $text = $crawler->text();

    // Get the HTML content of the page


    // die;
    $pattern = '/background-image:\s*url\((.*?)\);/';
    preg_match($pattern, $text, $matches);
    
    if (isset($matches[1])) {
        $backgroundImageURL = $matches[1];
    } 
    $backgroundImageURL = ( isset($matches[1]) ) ? $matches[1] : null;
    $backgroundImageURL = str_replace('"', '', $backgroundImageURL);
   

    $tags = array();
    // Get article tags
    getRssTags($tags, $item);

    // Add author as tag
    getCrawlerAuthor($tags, JDN_AUTHOR, $crawler); //TODO: can i change to rss?

    // echo "Num of Tags: ". count($tags). "\n";

    // $content = getRssContent($item);
    $content = $crawler->filter('div.elementor-widget-theme-post-content');
    // print_r( $content );

    if ($content->count() > 0) {
           $content = $crawler->filter('div.elementor-widget-theme-post-content')->html();

    } else {
        $content = "";
    }



    
    // Get article images
    $images = array();
    // TODO: get main image
    getImages($images, JDN_IMAGE, $crawler, 'src'); // Content images
    getImages($images, JDN_GALLERY, $crawler, 'href'); // gallery
    // echo "Num of Images: " .count($images). "\n";



    if (checkWordsInText($content) || checkWordsInText($title)) {
        echo "סונן הפוסט";
        return null;
     }

    // print_r( $images );
    // die;

    $post = array(
        'post_title' => $title,
        'post_type' => 'post',
        'post_content' => $content,
        'post_status' => 'publish',
        'post_excerpt' => $des,
        // 'post_date' => $postdate,
    );
    return [$post, $link, $tags, $images, $backgroundImageURL ];
}