<?php

require 'vendor/autoload.php';
use Goutte\Client;

$kikarBase = "https://a.kikar.co.il/v1";
$kikarRss = "/rss/articles/latest/rss2";
$KikarSitemap = "/sitemap/articles/";
$kikarTagClass = '.MuiChip-label.MuiChip-labelMedium.css-9iedg7';
$kikarAuthorClass = '.MuiButtonBase-root.MuiButton-root.MuiButton-text.MuiButton-textPrimary.MuiButton-sizeMedium.MuiButton-textSizeMedium.MuiButton-root.MuiButton-text.MuiButton-textPrimary.MuiButton-sizeMedium.MuiButton-textSizeMedium.css-5y3g3q';

$jdnBase = "https://jdn.co.il";
$jdnRss = "/feed";
$jdnAuthorClass = '.elementor-icon-list-text';
$jdnImage = '.elementor-element.elementor-element-1bc3489.elementor-widget.elementor-widget-theme-post-content > div > p> img';
$jdnGallery = '.elementor-gallery__container > a';

$fm93Base = "https://www.93fm.co.il/radio/941860/";
$fm93Rss = "/feed";
$fm93AuthorClass = 'author';


function getData($hostBase, $rss, $tagClass, $authorClass, $imageClass, $galleryClass) {
    $client = new Client();

    $feed = file_get_contents($hostBase . $rss);
    $xml = simplexml_load_string($feed);
    
    foreach ($xml->channel->item as $item) {
        if (!isset($item->title) || !isset($item->description) || !isset($item->pubDate)) {
            // Skip to the next item if title, description or date  are not set
            continue;
        }
        
        $title = $item->title;
        $des = $item->description;
        $date = $item->pubDatee;
        $content = isset($item->children('content', true)->encoded) ? (string)$item->children('content', true)->encoded : '';
        $link = $item->link;
        // $link = 'https://www.jdn.co.il/news/2036997/';
        echo $link . "\n";
        
        
        //check if post uploaded already
        // if (checkPostExists($link)) {
        //     echo 'Article already uploaded';
        //     break;
        // }
        
        $crawler = $client->request('GET', $link);
        $tags = array();
        // Get article tags
        if ($tagClass != '') {
            getCrawlerTags($tags, $tagClass, $crawler);
        }
        else {
            getRssTags($tags, $item);
        }
        // Add author as tag
        getCrawlerAuthor($tags, $authorClass, $crawler);
        echo count($tags). "\n";

        // Get article images
        $images = array();
        // TODO: get main image
        getImages($images, $imageClass, $crawler, 'src');
        getImages($images, $galleryClass, $crawler, 'href');
        echo count($images). "\n";

        // $postdate = date("Y-m-d H:i:s", $date);
        // echo '<pre>';
        // echo $date;
        // echo '</pre>';
        //   echo $tempKeywords;
        
        $post = array(
            'post_title' => $title,
            'post_type' => 'post',
            'post_content' => $content,
            'post_status' => 'publish',
            'post_excerpt' => $des,
            // 'post_date' => $postdate,
        );
        break;
        
        
        //   $post_id = wp_insert_post($post, $wp_error = false); //TODO: uncomment
        //   update_field('source_post', 'kikar', $post_id);
        //   update_field('external_link', $link, $post_id);
        //   wp_set_post_tags($post_id, $tags);

    }
}
    

function checkPostExists($link) {

    echo "lol";
    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'any',
        'meta_query'     => array(
            array(
                'key'     => 'external_link',
                'value'   => $link,
                'compare' => '='
                )
            )
        );
        $existing_post = get_posts($args);

        print_r( $existing_post  );
        die;
        if (!empty($existing_post)) {
            return true;
        }
        return false;
}

function getCrawlerTags (&$tags, $tagClass, $crawler) {
    $crawler->filter($tagClass)->each(function ($node) use (&$tags) {
        $tags[] = $node->text();
    });
}

function getRssTags(&$tags, $item) {
    foreach ($item->category as $category) {
        $tags[] = $category;
    }

}

function getCrawlerAuthor(&$tags, $authorClass, $crawler) {
    $tags[] = $crawler->filter($authorClass)->first()->text();
}

function getImages(&$images, $imageClass, $crawler, $attr) {
    $crawler->filter($imageClass)->each(function ($node) use (&$images, $attr) {
        $images[] = $node->attr($attr);
    });
}

getData($jdnBase, $jdnRss, '', $jdnAuthorClass, $jdnImage, $jdnGallery);

