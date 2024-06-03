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

    $title = "";
    $des = "";
    $main_image = "";
    $content = "";
    $client_page = null;

    $link = $link->getUri();

    // $link = 'https://www.93fm.co.il/radio/959943/';
    // $link = 'https://www.93fm.co.il/radio/969630/';

    $client_page = new Client();
    $crawler_page = $client_page->request('GET', $link);





    // check if post uploaded already
    if (checkPostExists($link)) {
        echo "Exists";
        continue;
    }
    else {
        echo 'UPload now';
    }
    echo "title: " .  $title = $crawler_page->filter('article#primary h1')->text(). PHP_EOL;
    $count = $crawler_page->filter('div.excerpt > p')->count();
    if ($count > 0) {
            $des = $crawler_page->filter('div.excerpt > p')->text();
    } else {
        $des = "";
    }


    $count = $crawler_page->filter('#primary .thumbnail > figure > img')->count();

    if ($count > 0) {
        $image_src = 'https://www.93fm.co.il'. $crawler_page->filter('#primary .thumbnail > figure > img')->attr('src');
    } else {
        continue;

    $image_src = "";
    }



    $content = $crawler_page->filter('main.main.post')->html();



    if (strpos($content, '<audio') !== false) {
        echo "ה-HTML מכיל תג <audio>";
        continue;
    } 

    if (strpos($content, 'Please enable JavaScript') !== false) {
        echo "ה-HTML מכיל תג Please enable JavaScript";
        continue;
    } 

    // echo $content;
    // die;

    if (checkWordsInText($content) || checkWordsInText($title)) {
        echo "סונן הפוסט";
        continue;
     }


    $post = array(
        'post_title' => $title,
        'post_type' => 'post',
        'post_content' => $content,
        'post_status' => 'publish',
        'post_excerpt' => $des,
    );

    $post_id = wp_insert_post($post, $wp_error = false); //TODO: uncomment
    $source = 'kol-hay';
    update_field('source_post', $source, $post_id);
    update_field('external', (string)$link, $post_id);
    update_field('main_image', (string)$image_src, $post_id);

    $morale = false;
    if (checkWordsInTextMorale($content) || checkWordsInTextMorale($title)) {
        update_field('morale_post', 1, $post_id);
        $morale = true;
        echo "פוסט מורל";
        continue;
    }

    $breadcrumbs = $crawler_page->filter('#breadcrumbs');


    if ($breadcrumbs->count() > 0) {
        $breadcrumbs = $breadcrumbs->text();
    } else {
        $breadcrumbs = "";
    }

    // Extract the individual items from the breadcrumbs
    $items = explode(' » ', $breadcrumbs);
    
    // Exclude the first and last items
    $items = array_slice($items, 1, -1);
    
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

echo "93FM END";