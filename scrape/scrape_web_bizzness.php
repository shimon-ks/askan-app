<?php

use Goutte\Client;

$client = new Client();

$crawler = $client->request('GET', 'https://bizzness.net/');

// print_r( $crawler->html());
// die;

$regexPattern = '#^https://bizzness\.net/([^/]+/)$#u';

$links = $crawler->filter('a')->links();

$matchingUrls = [];
$excludedUrls = [
    'https://bizzness.net/%d7%a4%d7%a8%d7%95%d7%99%d7%a7%d7%98%d7%99%d7%9d/',
    'https://bizzness.net/%d7%9c%d7%95%d7%97-%d7%a0%d7%93%d7%9c%d7%9f/',
    'https://bizzness.net/%d7%94%d7%9e%d7%99%d7%99%d7%9c-%d7%94%d7%90%d7%93%d7%95%d7%9d/',
    'https://bizzness.net/%D7%94%D7%9E%D7%99%D7%99%D7%9C-%D7%94%D7%90%D7%93%D7%95%D7%9D/',
    'https://bizzness.net/המייל-האדום/'
    
];

foreach ($links as $link) {
    $url = $link->getUri();
    
    if (!in_array($url, $excludedUrls) && preg_match($regexPattern, $url)) {
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

    $client_page = new Client();
    $crawler_page = $client_page->request('GET', $link);




    
    if (checkPostExists($link)) {
        echo "Exists";
        continue;
    } else {
        echo 'Upload now';
    }
    $title = $crawler_page->filter('h1')->text();
    echo $title;



    $des_obj = $crawler_page->filter('.excerpt p');
    if ($des_obj->count() > 0) {
        $des = $crawler_page->filter('.excerpt p')->text();

    } else {
        $des = "";
    }
    echo $des;

    $main_image_node = $crawler_page->filter('.main_img img');
    if ($main_image_node->count() > 0) {
        if ($main_image_node->attr('data-src')) {
            $main_image = $main_image_node->attr('data-src');
        } elseif ($main_image_node->attr('data-srcset')) {
            $srcset = $main_image_node->attr('data-srcset');
            $main_image = explode(' ', $srcset)[0];
        } elseif ($main_image_node->attr('srcset')) {
            $srcset = $main_image_node->attr('srcset');
            $main_image = explode(' ', $srcset)[0];
        } elseif ($main_image_node->attr('src')) {
            $main_image = $main_image_node->attr('src');
        }
    } else {
        $main_image = "";
    }
    
    

    $p_content = $crawler_page->filter('.entry-content p')->each(function ($p) {
        return $p->html(); // או text() אם ברצונך לקבל את הטקסט בלבד
    });


    $content_data = '';
    foreach ($p_content as $key => $value) {
        $content_data .= '<p>';
        $content_data .= $value;
        $content_data .= '</p>';
    }
    print_r( $content_data );
    if (checkWordsInText($content_data) || checkWordsInText($title)) {
        echo "סונן הפוסט";
        continue;
     }


    $post = array(
        'post_title' => $title,
        'post_type' => 'post',
        'post_content' => $content_data,
        'post_status' => 'publish',
        'post_excerpt' => $des,
    );

    $post_id = wp_insert_post($post, $wp_error = false); // TODO: uncomment
    $source = 'bizzness';

    update_field('source_post', $source, $post_id);
    update_field('external', (string)$link, $post_id);
    update_field('main_image', (string)$main_image, $post_id);

    $morale = false;
    if (checkWordsInTextMorale($content) || checkWordsInTextMorale($title)) {
        update_field('morale_post', 1, $post_id);
        $morale = true;
        echo "פוסט מורל";
        continue;
    }

    $items = $crawler_page->filter('#breadcrumbs span a')->each(function ($node, $i) {
        return $node->text();
    });
    
    // הסרת האייטם הראשון והאחרון מהמערך
    array_shift($items); // מסיר את הראשון
    array_pop($items);   // מסיר את האחרון
    
    $categories_list = [];

    foreach ($items as $item) {

            $existing_category = get_term_by('name', $item, 'category');

            $categories_list[] = $item;
    
            if (empty($existing_category)) {
    
                $category_id = wp_insert_term(
                    $item,  
                    'category'           
                );
                wp_set_post_terms($post_id, $category_id, 'category', true);
            } else {
                wp_set_post_terms($post_id, $existing_category->term_id, 'category', true);
            }
        echo $item . "\n";
    }

    // wp_set_post_tags($post_id, $tags, true);
    $firebaseManager = new FirebaseManager();
    $expoTokens = $firebaseManager->getExpoTokensForSiteAndCategories($categories_list, $source);
    if ($morale) {
        $ExpoTokensNoMorales = $firebaseManager->getExpoTokensNoMorales();
        $expoTokens = array_diff($expoTokens, $ExpoTokensNoMorales);
    }

    sendExpoNotifications($expoTokens, $title, $des, $image_src, get_permalink($post_id));
}

echo "bizzness END";
