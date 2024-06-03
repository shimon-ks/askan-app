<?php

// require('generalHelper.php');


define('KIKAR_TAG', '.MuiChip-label.MuiChip-labelMedium.css-9iedg7');
define('KIKAR_AUTHOR', '.MuiButtonBase-root.MuiButton-root.MuiButton-text.MuiButton-textPrimary.MuiButton-sizeMedium.MuiButton-textSizeMedium.MuiButton-root.MuiButton-text.MuiButton-textPrimary.MuiButton-sizeMedium.MuiButton-textSizeMedium.css-5y3g3q');

// $kikarTagClass = '.MuiChip-label.MuiChip-labelMedium.css-9iedg7';
// $kikarAuthorClass = '.MuiButtonBase-root.MuiButton-root.MuiButton-text.MuiButton-textPrimary.MuiButton-sizeMedium.MuiButton-textSizeMedium.MuiButton-root.MuiButton-text.MuiButton-textPrimary.MuiButton-sizeMedium.MuiButton-textSizeMedium.css-5y3g3q';

function scrapeKikar ($item, $client) {
    
    echo $title = $item->title;
    echo  $des = $item->description;
    echo  $date = $item->pubDatee;
    $link = $item->link;
    echo $link . "\n";
    
    // check if post uploaded already
    if (checkPostExists($link)) {
        // echo 'Article already uploaded';
        return null;
    }
    else {
        echo 'UPload now';
    }
    
    $crawler = $client->request('GET', $link);
    $tags = array();
    // Get article tags
    getCrawlerTags($tags, KIKAR_TAG, $crawler);

    // Add author as tag
    getCrawlerAuthor($tags, KIKAR_AUTHOR, $crawler);

    echo "Num of Tags: ". count($tags). "\n";

    // $content = getRssContent($item);

    $content = $crawler->filter('div.article-content')->html();

    // // Get article images
    $images = array();
    // // get main image
    $main_image = $crawler->filter('div.article-content div.MuiBox-root img.MuiBox-root');
    // echo "<pre>";
    $main_image = $main_image->extract(['src']);
    // print_r( $main_image );

    // echo "</pre>";

    // die;
    // getImages($images, $imageClass, $crawler, 'src');
    // getImages($images, $galleryClass, $crawler, 'href');
    echo "Num of Images: " .count($images). "\n";

    $post = array(
        'post_title' => $title,
        'post_type' => 'post',
        'post_content' => $content,
        'post_status' => 'publish',
        'post_excerpt' => $des,
        // 'post_date' => $postdate,
    );
    return [$post, $link, $tags, $images, $main_image[0]];
}