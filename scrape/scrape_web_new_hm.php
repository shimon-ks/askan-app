<?php




use Goutte\Client;

$client = new Client();
$crawler = $client->request('GET', 'https://hm-news.co.il/');

$patternRegex = '/https:\/\/hm-news\.co\.il\/\d+\//';

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
$numberOfLinks = 20;

// print_r( $filteredLinks );
// die;


foreach ($filteredLinks as $link) {


    $title = "";
    $des = "";
    $main_image = "";
    $content = "";
    $client_page = null;

    $link = $link->getUri();



    $client_page = new Client();
    // $link = 'https://hm-news.co.il/441847/';


    $crawler_page = $client_page->request('GET', $link);

    // check if post uploaded already
    if (checkPostExists($link)) {
        echo "Exists";
        continue;
    }
    else {
        echo 'UPload now';
    }
    echo "title: " .  $title = $crawler_page->filter('h1')->text(). PHP_EOL;

    $des_obg =  $crawler_page->filter('div.elementor-element-d089fdb  .elementor-widget-container') ;
    if ($des_obg->count() > 0) {
        $des = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $crawler_page->filter('div.elementor-element-d089fdb  .elementor-widget-container')->html());

    } else {
        $des = "";
    }
    echo "des: " . $des . PHP_EOL;

    // echo $crawler_page->html();


    $main_image_node = $crawler_page->filter('.elementor-widget-theme-post-featured-image img');
    if ($main_image_node->count() > 0) {
        $main_image = $main_image_node->attr('data-src');
        echo "main_image: " . $main_image;
    } else {
        continue;
        echo "No main image available";
    }


    $parts = explode("/", $link);
    $parts = array_filter($parts);
    $hm_post_id = end($parts);
    
    $base_url = 'https://hm-news.co.il/';
    $post_url = $base_url . '/wp-json/wp/v2/posts/' . $hm_post_id;

    echo $post_url;
    $post_data = make_get_request($post_url);


    $content = $post_data['content']['rendered'];

    echo "<pre>";
    print_r($content);
    echo "</pre>";




    // $content = $crawler_page->filter('div.elementor-widget-theme-post-content');
    // if ($content->count() > 0) {
    //        $content = $crawler_page->filter('div.elementor-widget-theme-post-content')->html();
    // } else {
    //     $content = "";
    // }



    if (strpos($content, '<audio') !== false) {
        echo "ה-HTML מכיל תג <audio>";
        continue;
    } 

    if (strpos($content, 'Please enable JavaScript') !== false) {
        echo "ה-HTML מכיל תג Please enable JavaScript";
        continue;
    } 


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
    $source = 'hm-news';
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

        $categories_list = [];


        if (isset($post_data['categories'])) {
            $category_names = [];
            foreach ($post_data['categories'] as $category_id) {
                $category_url = $base_url . '/wp-json/wp/v2/categories/' . $category_id;
                $category_data = make_get_request($category_url);
                if (isset($category_data['name'])) {
                    $category_names[] = $category_data['name'];
                }
            }
            foreach ($category_names as $item) {

                $categories_list[] = $item;

                $existing_category = get_term_by('name', $item, 'category');
        
                if (empty($existing_category)) {
        
                    $category_id = wp_insert_term(
                        $item,  
                        'category'           
                    );
                    wp_set_post_terms($post_id, $category_id, 'category', true);

                } else {
                    wp_set_post_terms((int)$post_id, (string)$existing_category->term_id, 'category', true);
                }
        }
        } else {
            echo "לא נמצאו קטגוריות לפוסט זה.";
        }
        // wp_set_post_tags($post_id, $tags, true);
        $firebaseManager = new FirebaseManager();
        $expoTokens = $firebaseManager->getExpoTokensForSiteAndCategories($categories_list, $source);
        if ($morale) {
            $ExpoTokensNoMorales = $firebaseManager->getExpoTokensNoMorales();
            $expoTokens = array_diff($expoTokens, $ExpoTokensNoMorales);
        }
    
        print_r($expoTokens);
        var_dump($des);
       echo  sendExpoNotifications($expoTokens, $title, $des, $main_image, get_permalink($post_id));
}

echo "NEW HM END";