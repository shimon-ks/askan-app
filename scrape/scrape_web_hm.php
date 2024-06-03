<?php

use Goutte\Client;

$client = new Client();

$crawler = $client->request('GET', 'https://hm-news.co.il/');

$regexPattern = '/https:\/\/hm-news\.co\.il\/\d+\//';

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
    $cat_list = [];
    $tag_list = [];


    $link = 'https://hm-news.co.il/440520/';


    $client_page = new Client();
    $crawler_page = $client_page->request('GET', $link);
    


    // check if post uploaded already
    if (checkPostExists($link)) {
        echo "Exists";
        continue;
    } else {
        echo 'Upload now';
    }


    echo $crawler_page->html();
    echo $crawler_page->filter('.wp-block-gallery')->html();

    $title = $crawler_page->filter('h1')->text();


    $des_obg =  $crawler_page->filter('div.elementor-element-d089fdb  .elementor-widget-container') ;
    if ($des_obg->count() > 0) {
        $des = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $crawler_page->filter('div.elementor-element-d089fdb  .elementor-widget-container')->html());

    } else {
        $des = "";
    }
    echo "des: " . $des . PHP_EOL;

    // Check if the image exists before trying to retrieve its attribute
    $main_image_node = $crawler_page->filter('.elementor-widget-theme-post-featured-image img');
    if ($main_image_node->count() > 0) {
        $main_image = $main_image_node->attr('data-src');
        // echo "main_image: " . $main_image;
    } else {
        continue;
        echo "No main image available";
    }

    $post_content = $crawler_page->filter('.post_content')->html();






    if (strpos($post_content, 'iframe') !== false) {
        echo "ה-HTML מכיל תג iframe";
        continue;
    } 
    
    $p_content = ''; 

    $post_content = $crawler_page->filter('.post_content')->children()->filter('p:not([class])')->each(function ($p) use (&$p_content) {
        $p_content .= "<p>";
        
        $p_content .= $p->text();
        
        $p_content .= "</p>";
    });

  

 

    // if (checkWordsInText($p_content) || checkWordsInText($title)) {
    //    continue;
    // }


    $galleryHtml = $crawler_page->filter('figure.wp-block-gallery.has-nested-images.columns-1.is-cropped.wp-block-gallery-1.is-layout-flex.wp-block-gallery-is-layout-flex')->each(function ($node) {
        return $node->html();
    });
    
    // בדוק אם נמצאו אלמנטים והדפס את ה-HTML של הראשון
    if (!empty($galleryHtml)) {
        echo $galleryHtml[0];
    } else {
        echo "Gallery not found.";
    }
    $gallery = $crawler_page->filter('.post_content')->html();
    // print_r(  $gallery );
    // echo "ffffffffcvcvcvcvcvcvcvcvcvcvcvcvcvcvcv";
    // die;
    $count_gallery = $crawler_page->filter('.post_content .wp-block-gallery')->count();


    if ($count_gallery > 0 ) {
        $p_content .= $crawler_page->filter('.wp-block-gallery')->html();

    }






    $post = array(
        'post_title' => $title,
        'post_type' => 'post',
        'post_content' => $p_content,
        'post_status' => 'publish',
        'post_excerpt' => $des,
    );

    $post_id = wp_insert_post($post, $wp_error = false); // TODO: uncomment
    $source = 'hm-news';
    update_field('source_post', $source, $post_id);
    update_field('external', (string)$link, $post_id);
    update_field('main_image', (string)$main_image, $post_id);

    $elements = $crawler_page->filter('[data-elementor-type="single-post"][data-elementor-id]');
    $categoryIds = [];
    $tagIds = [];

    $elements->each(function ($element) use (&$categoryIds, &$tagIds) {
            $classAttributeValue = $element->attr('class');

            preg_match_all('/category-(\d+)/', $classAttributeValue, $categoryMatches);

            preg_match_all('/tag-(\d+)/', $classAttributeValue, $tagMatches);

            if (!empty($categoryMatches[1])) {
                $categoryIds = array_merge($categoryIds, $categoryMatches[1]);
            }

            if (!empty($tagMatches[1])) {
                $tagIds = array_merge($tagIds, $tagMatches[1]);
            }
    });


    $currentRepeaterData = get_field('hm_categories_list', 'option');

    $matchedCategories = array();

    foreach ($categoryIds as $categoryId) {
        foreach ($currentRepeaterData as $category) {
            if ($category['hm_cat_id'] == $categoryId) {
                $matchedCategories[] = $category;
                break; 
            }
        }
    }


    $categories_list = [];

    foreach ($matchedCategories as $category_i) {
        $existing_category = get_term_by('name', $category_i['hm_cat_name'], 'category');

        if (empty($existing_category)) {

            $categories_list[] = $category_i['hm_cat_name'];

            $category_id = wp_insert_term(
                $category_i['hm_cat_name'],  
                'category'           
            );
            wp_set_post_terms($post_id, $category_id, 'category', true);
        } else {
            wp_set_post_terms($post_id, $existing_category->term_id, 'category', true);
        }
    }

    // foreach ($tagIds as $tagId) {
    //     foreach ($currentRepeaterData as $category) {
    //         if ($category['hm_cat_id'] == $categoryId) {
    //             $matchedCategories[] = $category;
    //             break; 
    //         }
    //     }
    // }



    // wp_set_post_tags($post_id, $tags, true);
    $firebaseManager = new FirebaseManager();
	$expoTokens = $firebaseManager->getExpoTokensForSiteAndCategories($categories_list, $source);

    sendExpoNotifications($expoTokens, $title, $des, $main_image, get_permalink($post_id));
}

echo "hm-news END";
