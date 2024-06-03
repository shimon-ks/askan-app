<?php


function createkikarHtml($element, $existingLinks) {
    $html = '';

    if ($element->type === 'img') {
        if (is_array($existingLinks) && !in_array($element->src, $existingLinks)) {
            $html .= '<img src="' . htmlspecialchars($element->src) . '" alt="' . htmlspecialchars($element->alt) . '" width="' . intval($element->width) . '" height="' . intval($element->height) . '">';
        }
    } elseif ($element->type === 'html') {
        foreach ($element->content as $content) {
            $html .= createkikarHtml($content, $existingLinks); // Recursive call
        }
    } elseif ($element->type === 'video') {
        // נניח שמידות הווידאו נלקחות מה-poster, אם זמין
        $width = $element->posterWidth ?? $element->width;
        $height = $element->posterHeight ?? $element->height;
        $html .= '<video width="' . intval($width) . '" height="' . intval($height) . '" poster="' . htmlspecialchars($element->poster) . '" controls>';
        foreach ($element->urls as $url) {
            $videoType = 'video/' . pathinfo($url->url, PATHINFO_EXTENSION); // נקבע את סוג הווידאו לפי סיומת הקובץ
            $html .= '<source src="' . htmlspecialchars($url->url) . '" type="' . $videoType . '">';
        }
        $html .= '</video>';
    } elseif ($element->type === 'paragraph') {
        $html .= '<p>';
        foreach ($element->children as $child) {
            $html .= htmlspecialchars($child->text);
        }
        $html .= '</p>';
    } elseif ($element->type === 'heading-two') {
        $html .= '<h2>';
        foreach ($element->children as $child) {
            $html .= htmlspecialchars($child->text);
        }
        $html .= '</h2>';
    } elseif ($element->type === 'bulleted-list') {
        $html .= '<ul>';
        foreach ($element->children as $child) {
            $html .= createkikarHtml($child, $existingLinks); // Recursive call for list items
        }
        $html .= '</ul>';
    } elseif ($element->type === 'list-item') {
        $html .= '<li>';
        foreach ($element->children as $child) {
            if (isset($child->text)) {
                $html .= htmlspecialchars($child->text);
            } else {
                // If there is more complex content within the list item
                $html .= createkikarHtml($child, $existingLinks);
            }
        }
        $html .= '</li>';
    }

    return $html;
}




$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://a.kikar.co.il/v2/articles?page=0',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
$response = json_decode( $response );

foreach ($response as $item) {

    $title = "";
    $des = "";
    $main_image = "";
    $content = "";
    $link = "";

    $title = $item->title;
    $des = $item->subTitle;
    $main_image = $item->image->src;
    $slug = $item->slug;
    $prefix = 'https://a.kikar.co.il/v2/articles/';
    $slug = $item->slug;
    // $slug = 's7rfyt';
    $link = $prefix . $slug;

    $link_external = $link;


    $remove_strings = ['a.', 'v2/'];
    foreach ($remove_strings as $string) {
        $link_external = str_replace($string, '', $link_external);
    }

  

    

    if (checkPostExists($link_external)) {
        echo "Exists";
        continue;
    }
    else {
        echo 'UPload now';
    }
    curl_setopt_array($curl, array(
        CURLOPT_URL => $link,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
      
    $article = curl_exec($curl);

    
    curl_close($curl);
    $article = json_decode( $article );


    if ( isset( $article->isPromoted )  && $article->isPromoted == 1 ) {
        continue;
    }


    $obj = $article->content;
    // echo "<pre>";
    // print_r( $title ) ;
    // echo "</pre>";
    $content = '';
    foreach ($obj->content as $item) {
        $content .= createkikarHtml($item , $main_image);
    }
    // echo $content;

    if (checkWordsInText($content) || checkWordsInText($title)) {
        echo "</br>";
        echo "סונן הפוסט";
        echo "</br>";

        continue;
    }


    $post = array(
        'post_title' => $title,
        'post_type' => 'post',
        'post_content' => $content,
        'post_status' => 'publish',
        'post_excerpt' => $des,
    );

    $post_id = wp_insert_post($post, $wp_error = false); 
    $source = 'kikar';



    update_field('source_post', $source, $post_id);
    update_field('external', (string)$link_external, $post_id);
    update_field('main_image', (string)$main_image, $post_id);

    $morale = false;
    if (checkWordsInTextMorale($content) || checkWordsInTextMorale($title)) {
        update_field('morale_post', 1, $post_id);
        $morale = true;
        echo "פוסט מורל";
        continue;
    }



    $categories_list = [];
    
    foreach ($article->categories as $category) {

        $categories_list[] = $category->title;

        echo "Title: " . $category->title . "\n";
        $existing_category = get_term_by('name', $category->title, 'category');

        if (empty($existing_category)) {

            $category_id = wp_insert_term(
                $category->title,  
                'category'           
            );
            wp_set_post_terms($post_id, $category_id, 'category', true);
        } else {
            wp_set_post_terms($post_id, $existing_category->term_id, 'category', true);
        }
    
        // פרסום הקטגוריות המשניות אם יש
        if (!empty($category->parents)) {
            foreach ($category->parents as $parent) {

                $categories_list[] = $parent->title;

                $existing_category = get_term_by('name', $parent->title, 'category');

                if (empty($existing_category)) {
        
                    $category_id = wp_insert_term(
                        $parent->title,  
                        'category'           
                    );
                    wp_set_post_terms($post_id, $category_id, 'category', true);
                } else {
                    wp_set_post_terms($post_id, $existing_category->term_id, 'category', true);
                }
            }
        }
    
    }





    $firebaseManager = new FirebaseManager();
    $expoTokens = $firebaseManager->getExpoTokensForSiteAndCategories($categories_list, $source);
    if ($morale) {
        $ExpoTokensNoMorales = $firebaseManager->getExpoTokensNoMorales();
        $expoTokens = array_diff($expoTokens, $ExpoTokensNoMorales);
    }
    print_r($expoTokens);


   $push =  sendExpoNotifications($expoTokens, $title, $des, $image_src, get_permalink($post_id));
//    var_dump($push);
//    echo "</br>";
//    echo "<pre>";
//    var_dump($expoTokens);
//    echo "</pre>";
//    echo "ddddddddddd";
//     die;

}

echo "end kikar";