<?php

use Goutte\Client;

$client = new Client();

$crawler = $client->request('GET', 'https://ch10.co.il/');

// print_r( $crawler->html());

$regexPattern = '/^https:\/\/ch10\.co\.il\/news\/\d+\/$/';


$links = $crawler->filter('a')->links();

$matchingUrls = [];

foreach ($links as $link) {
    $url = $link->getUri();
    if (preg_match($regexPattern, $url)) {
        $matchingUrls[] = $url;

        if (count($matchingUrls) >= 20) {
            break;
        }
    }
}


 
foreach ($matchingUrls as $link) {
    $title = "";
    $des = "";
    $main_image = "";
    $content = "";
    $client_page = null;

    $link = 'https://ch10.co.il/news/869403/';

    $client_page = new Client();
    $crawler_page = $client_page->request('GET', $link);
    


    // check if post uploaded already
    if (checkPostExists($link)) {
        echo "Exists";
        continue;
    } else {
        echo 'Upload now';
    }
    echo "link: " . $link;
    $title = $crawler_page->filter('h1 a')->text();
    echo $title;
    $des = $crawler_page->filter('.excerpt')->text();
    echo $des;

    $main_image_node = $crawler_page->filter('.main-post img');
    if ($main_image_node->count() > 0) {
        $main_image = $main_image_node->attr('src');
        echo "main_image: " . $main_image;
    } else {
        echo "No main image available";
        $main_image = "";
    }
    
    $main_post = $crawler_page->filter('.main-post')->html();
    if (strpos($main_post, 'vimeo') !== false) {
        echo "ה-HTML מכיל תג vimeo";
        continue;
    } 

    $post_content = $crawler_page->filter('.content_content')->html();
    echo $post_content;
    die;

    if (strpos($post_content, 'vimeo') !== false) {
        echo "ה-HTML מכיל תג vimeo";
        continue;
    } 



    $post = array(
        'post_title' => $title,
        'post_type' => 'post',
        'post_content' => $post_content,
        'post_status' => 'publish',
        'post_excerpt' => $des,
    );

    $post_id = wp_insert_post($post, $wp_error = false); // TODO: uncomment
    $source = 'haredim10';
    update_field('source_post', $source, $post_id);
    update_field('external', (string)$link, $post_id);
    update_field('main_image', (string)$main_image, $post_id);

    // wp_set_post_tags($post_id, $tags, true);
    // sendPush($post_id);
}

echo "haredim10 END";
