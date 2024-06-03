<?php

// require('generalHelper.php');



// $jdnAuthorClass = '.elementor-icon-list-text';
// $jdnImage = '.elementor-element.elementor-element-1bc3489.elementor-widget.elementor-widget-theme-post-content > div > p> img';
// $jdnGallery = '.elementor-gallery__container > a';

function scrapeh10 ($item, $client) {

    print_r( $item );
    die;
 
    
    $title = $item->title;
    $des = $item->description;
    $date = $item->pubDatee;
    $link = $item->link;
    echo "link: ". $link . "\n";

    
    // check if post uploaded already
    if (checkPostExists($link)) {
        // echo 'Article already uploaded';
        return null;
    }
    else {
        echo 'UPload now';
    }
    
    $crawler = $client->request('GET', $link);

    $text = $crawler->text();

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

    echo "Num of Tags: ". count($tags). "\n";

    // $content = getRssContent($item);
    $content = $crawler->filter('div.elementor-widget-theme-post-content')->html();


    
    // Get article images
    $images = array();
    // TODO: get main image
    getImages($images, JDN_IMAGE, $crawler, 'src'); // Content images
    getImages($images, JDN_GALLERY, $crawler, 'href'); // gallery
    // echo "Num of Images: " .count($images). "\n";



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