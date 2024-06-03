<?php



use Goutte\Client;

$client = new Client();
$crawler = $client->request('GET', 'https://jdn.co.il');

$patternRegex = "/\/news\/\d+\//";

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
    // echo $link->getUri();
    // continue;

    $title = "";
    $des = "";
    $main_image = "";
    $content = "";
    $client_page = null;

    $link = $link->getUri();


    $client_page = new Client();
    // $link = 'https://www.jdn.co.il/news/2109815/';
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
    $count = $crawler_page->filter('div.elementor-element-293bf82 .elementor-widget-container')->count();
    if ($count > 0) {
            $des = $crawler_page->filter('div.elementor-element-293bf82 .elementor-widget-container')->html();
    } else {
        $des = "";
    }
    $des = preg_replace('/<style>.*?<\/style>/s', '', $des);


    $styleContent = $crawler_page->filter('style#elementor-frontend-inline-css')->first()->html();
    preg_match('/background-image:\s*url\("([^"]+)"\)/', $styleContent, $matches);

    if (!empty($matches[1])) {
        $image_src = $matches[1];
    } else {
        $image_src = "";

    }

    $content = $crawler_page->filter('div.elementor-widget-theme-post-content');
    if ($content->count() > 0) {
           $content = $crawler_page->filter('div.elementor-widget-theme-post-content')->html();
    } else {
        $content = "";
    }



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
    $source = 'jdn';
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


        /////get categories
        $parts = explode("/", $url);
        $parts = array_filter($parts);
        $jdn_post_id = end($parts);
        
        $base_url = 'https://www.jdn.co.il';
        $post_url = $base_url . '/wp-json/wp/v2/posts/' . $jdn_post_id;
        $post_data = make_get_request($post_url);
        

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
    
        $res = sendExpoNotifications($expoTokens, $title, $des, $image_src, get_permalink($post_id));

        // var_dump( $res );
        // var_dump( $expoTokens );
        // die;
        
}

echo "JDN END";