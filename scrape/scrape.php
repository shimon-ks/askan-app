<?php

require __DIR__ .'/../vendor/autoload.php';
require_once('crawler.php');
require_once('rss.php');
require_once('oneSignal.php');
require_once('scrapeKikar.php');
require_once('scrapeJdn.php');
require_once('scrape93.php');
require_once('scrapeh10.php');
require_once('generalHelper.php');



use Goutte\Client;

define('KIKAR', 'kikar');
define('JDN', 'jdn');
define('FM93', 'fm93');
define('H10', 'haredim10');



$kikarBase = "https://a.kikar.co.il/v1";
$kikarRss = "/rss/articles/latest/rss2";
$KikarSitemap = "/sitemap/articles/";


$jdnBase = "https://jdn.co.il";
$jdnRss = "/feed";

$h10Base = "https://bizzness.net/";
$h10Rss = "/feed";


$fm93Base = "https://www.93fm.co.il";
$fm93Rss = "/feed";



function getData($host, $hostBase, $rss) {



    $client = new Client();

    $feed = file_get_contents($hostBase . $rss);
    $xml = simplexml_load_string($feed);
    
    foreach ($xml->channel->item as $item) {
        if (!isset($item->title) || !isset($item->description) || !isset($item->pubDate)) {
            // Skip to the next item if title, description or date  are not set
            continue;
        }
        $postData;
        switch ($host) {
            case KIKAR:
                $postData = scrapeKikar($item, $client);
                $source = KIKAR;
                break;
            case JDN:
                $postData = scrapeJdn($item, $client);
                $source = JDN;
                break;
            case FM93:
                $postData = scrape93($item, $client);
                $source = FM93;
                break;
            case H10:
                $postData = scrapeh10($item, $client);
                $source = H10;
                break;
        }
        
        if (is_null($postData)){
            continue;
        }
        $post = $postData[0];
        $link = $postData[1];
        $tags = $postData[2];
        $images = $postData[3];
        $main_image = ( isset($postData[4]) ) ? $postData[4] : null;
        // echo "Linl: ". $link ."\n";
        // echo "Title: ". $post['post_title'] . "\n";
        // echo "Des: ". $post['post_excerpt'] . "\n";
        // echo "Content: ". $post['post_content'] . "\n";
        foreach($tags as $tag) {
            echo $tag."\n";
        }

        echo "before";
        echo  $main_image;
        echo "after";

  
        
        
        $post_id = wp_insert_post($post, $wp_error = false); //TODO: uncomment
        update_field('source_post', $source, $post_id);
        update_field('external', (string)$link, $post_id);
        update_field('main_image', (string)$main_image, $post_id);
        echo $post_id , "\n";
        // wp_set_post_tags($post_id, $tags, true);
        // sendPush($post_id);
        break;

    }
}

   



// getData(JDN, $jdnBase, $jdnRss);
// getData(KIKAR, $kikarBase, $kikarRss);
// getData(FM93, $fm93Base, $fm93Rss);
// getData(H10, $h10Base, $h10Rss);

die;