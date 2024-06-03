<?php

function checkPostExists($link) {
    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'any',
        'meta_query'     => array(
            array(
                'key'     => 'external',
                'value'   => (string)$link,
                'compare' => '='
                )
            )
        );
        $existing_post = get_posts($args);
        if (!empty($existing_post)) {
            return true;
        }
        return false;
}

function checkWordsInText($text) {

    $wordArray = get_field('list_of_words_to_filter', 'option');

    foreach ($wordArray as $key => $wordInfo) {
        $word = $wordInfo['word'];

        
        if (stripos($text, $word) !== false) {
            echo $word;

            return true; 
        }
    }
    return false; 
}

function checkWordsInTextMorale($text) {

    $wordArray = get_field('list_of_words_to_filter_morale', 'option');


    foreach ($wordArray as $key => $wordInfo) {
        $word = $wordInfo['word_morale'];

        
        if (stripos($text, $word) !== false) {
            echo $word;

            return true; 
        }
    }
    return false; 
}


function make_get_request($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}



//////////// hm-import-category /////////////////////
function fetchCategories($page) {
    $apiUrl = "https://hm-news.co.il/wp-json/wp/v2/categories?page={$page}";

    // Make a GET request to the WordPress REST API endpoint for categories
    $response = file_get_contents($apiUrl);
    $categories = json_decode($response, true);

    return $categories;
}

function get_hm_cat(){

$repeaterFieldKey = 'hm_categories_list';

$currentRepeaterData = get_field($repeaterFieldKey, 'option');

$allCategoriesData = array();

$page = 1;
do {
    $categories = fetchCategories($page);

    if (!empty($categories)) {
        foreach ($categories as $category) {
            $categoryData = array(
                'hm_cat_id' => $category['id'],
                'hm_cat_name' => $category['name']
            );

            $allCategoriesData[] = $categoryData;
        }

        $page++;
    }
} while (!empty($categories));

foreach ($allCategoriesData as $categoryData) {
    $currentRepeaterData[] = $categoryData;
}

update_field($repeaterFieldKey, $currentRepeaterData, 'option');

        echo "<pre>";
        print_r($allCategoriesData);
        echo "</pre>";

}

//////////// end-hm-import-category /////////////////////




//////////// hm-import-tags /////////////////////
function fetchTags($page) {
    $apiUrl = "https://hm-news.co.il/wp-json/wp/v2/tags?page={$page}";

    // Make a GET request to the WordPress REST API endpoint for categories
    $response = file_get_contents($apiUrl);
    $tags = json_decode($response, true);

    return $tags;
}

function get_hm_tags(){
    $repeaterFieldKey = 'hm_tags_list';
    $currentRepeaterData = get_field($repeaterFieldKey, 'option');
    $allTagsData = array();
    $page = 1;
    $counter = 0; // משתנה סופר
    do {
        $tags = fetchTags($page);

        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $tagData = array(
                    'hm_cat_id' => $tag['id'],
                    'hm_cat_name' => $tag['name']
                );
                $allTagsData[] = $tagData;
            }

            $page++;
            $counter++; // עדכון סופר
        }

        // בדיקה האם יש לעצור את הלולאה
    } while (!empty($tags) && $counter < 2);

    foreach ($allTagsData as $tagData) {
        $currentRepeaterData[] = $tagData;
    }

    print_r($currentRepeaterData);
    update_field($repeaterFieldKey, $currentRepeaterData, 'option');

    echo "<pre>";
    print_r($allTagsData);
    echo "</pre>";
}

if (isset( $_GET['importTags'])) {
    update_field('hm_tags_list', "", 'option');
    die;

    get_hm_tags();
    die;
}

//////////// end-hm-import-tags /////////////////////