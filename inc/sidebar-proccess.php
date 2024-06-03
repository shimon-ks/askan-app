<?php

function sendExpoNotifications($tokens, $title, $message, $image, $url) {
    $messages = [];

    foreach ($tokens as $token) {
        $messages[] = [
            'to' => $token,
            'title' => $title,
            'body' => trim( $message ),
            'image' => [
                'uri' => $image
            ],
            'data' => [
				'url' => $url
			]
        ];
    }

    // print_r($messages );
    // die;

    $ch = curl_init('https://exp.host/--/api/v2/push/send');
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => [
            'accept: application/json',
            'Content-Type: application/json'
        ],
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($messages)
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    // echo $response;

    return $response;
}


function set_user_category() {
    $userId = isset($_POST['userId']) ? $_POST['userId'] : '';
    $categoryId = isset($_POST['categoryId']) ? $_POST['categoryId'] : '';
    $actionType = isset($_POST['actionType']) ? $_POST['actionType'] : '';
    // echo "<pre>";
    // echo $userId;
    // echo "</br>";
    // echo $categoryId;
    // echo "</br>";
    // echo $actionType;
    // echo "</pre>";
    // die;

    if (empty($userId) || empty($categoryId) || empty($actionType)) {
        wp_send_json_error('Missing required parameters');
        return;
    }

    $firebaseManager = new FirebaseManager();

    if ($actionType == 'add') {
        $result = $firebaseManager->addUserCategory($userId, $categoryId);
    } elseif ($actionType == 'remove') {
        $result = $firebaseManager->removeUserCategory($userId, $categoryId);
    } else {
        wp_send_json_error('Invalid action type');
        return;
    }

    if ($result) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error('Operation failed');
    }
}
add_action('wp_ajax_set_user_category', 'set_user_category');
add_action('wp_ajax_nopriv_set_user_category', 'set_user_category');


function set_user_site() {
    $userId = isset($_POST['userId']) ? $_POST['userId'] : '';
    $siteId = isset($_POST['siteId']) ? $_POST['siteId'] : '';
    $actionType = isset($_POST['actionType']) ? $_POST['actionType'] : '';

    if (empty($userId) || empty($siteId) || empty($actionType)) {
        wp_send_json_error('Missing required parameters');
        return;
    }

    $firebaseManager = new FirebaseManager();

    if ($actionType == 'add') {
        $result = $firebaseManager->addUserSite($userId, $siteId);
    } elseif ($actionType == 'remove') {
        $result = $firebaseManager->removeUserSite($userId, $siteId);
    } else {
        wp_send_json_error('Invalid action type');
        return;
    }

    if ($result) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error('Operation failed');
    }
}
add_action('wp_ajax_set_user_site', 'set_user_site');
add_action('wp_ajax_nopriv_set_user_site', 'set_user_site');


add_action('wp_ajax_get_user_category', 'get_user_category');
add_action('wp_ajax_nopriv_get_user_category', 'get_user_category');

function get_user_category() {
	$FirebaseManager = new FirebaseManager();
	$user_cagegories = $FirebaseManager->getUserCategories($_POST['userId']);
	echo  json_encode( $user_cagegories ) ;
	wp_die();
}


function load_posts_by_preferences() {
    $page = !empty($_POST['page']) ? $_POST['page'] : 1;
    $is_first_page = $page == 1;

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 10,
        'paged' => $page,
        'post_status' => 'publish',
        'meta_query' => array()
    );

    $sticky_posts_ids = array();

    if ($is_first_page) {
        $sticky_posts = get_option('sticky_posts');
        if (!empty($sticky_posts)) {
            $args['post__in'] = $sticky_posts;
            $args['ignore_sticky_posts'] = 1; 
            
            $sticky_query = new WP_Query($args);
            if ($sticky_query->have_posts()) {
                while ($sticky_query->have_posts()) {
                    $sticky_query->the_post();
                    get_template_part('template-parts/content', 'post');
                }
            }
            
            // שמירת המזהים של הפוסטים ה"סטיקיים" כדי לא לשכפל אותם בשאילתה הבאה
            $sticky_posts_ids = wp_list_pluck($sticky_query->posts, 'ID');
            
            wp_reset_postdata();
        }
    }

    // עדכון הפרמטרים לשאילתה השנייה, בלי הסטיקיים שכבר הוצגו
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 10 - count($sticky_posts_ids), // כמות הפוסטים שנותרה להצגה
        'paged' => $page,
        'post_status' => 'publish',
        'post__not_in' => $sticky_posts_ids,
        'meta_query' => array()
    );

    if (isset($_POST['categories'])) {
        $category_ids = array_map(function($name) {
            $term = get_term_by('slug', $name, 'category');
            if (!$term) {
                return [];
            }
            $term_id = $term->term_id;
            // מקבל את כל תת-הקטגוריות של הקטגוריה הנוכחית
            $child_ids = get_term_children($term_id, 'category');
            // מוסיף את הקטגוריה הנוכחית למערך של תת-הקטגוריות
            return array_merge([$term_id], $child_ids);
        }, $_POST['categories']);
    
        // משטח את המערך של מערכים למערך אחד של IDs
        $category_ids = array_reduce($category_ids, function($carry, $item) {
            return array_merge($carry, $item);
        }, []);
    
        // מסנן את המערך כדי להסיר כל ערך שהוא NULL או כפילויות
        $args['category__in'] = array_unique(array_filter($category_ids));
    }

    if (isset($_POST['sites'])) {
        $args['meta_query'][] = array(
            'key' => 'source_post', 
            'value' => $_POST['sites'],
            'compare' => 'IN',
        );
    }

    if (isset($_POST['moraleChecked']) && $_POST['moraleChecked'] == 'moralechecked') {
        $args['meta_query'] = array(
            'relation' => 'AND',
            array(
                'relation' => 'OR',
                array(
                    'key' => 'morale_post',
                    'compare' => 'NOT EXISTS' // פוסטים שאין להם בכלל את המפתח 'morale_post'
                ),
                array(
                    'key' => 'morale_post',
                    'value' => '1',
                    'compare' => '!=' // פוסטים שיש להם את המפתח 'morale_post' וערכו שונה מ-'1'
                )
            )
        );
    }

    if (!empty($args['meta_query']) && !empty($args['category__in'])) {
        $args['meta_query']['relation'] = 'AND';
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) : 
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/content', 'post');
        endwhile;
        the_ad('6448');

    endif;

    wp_die();
}

add_action('wp_ajax_load_posts_by_preferences', 'load_posts_by_preferences');
add_action('wp_ajax_nopriv_load_posts_by_preferences', 'load_posts_by_preferences');



add_action('wp_ajax_load_posts_by_preferences', 'load_posts_by_preferences');
add_action('wp_ajax_nopriv_load_posts_by_preferences', 'load_posts_by_preferences');


function morale_chacked() {
    ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

    $FirebaseManager = new FirebaseManager();
    $set_ser_morale_chacked = $FirebaseManager->setUsermoraleChecked($_POST['userId'], $_POST['moraleChecked']);
    wp_die();

}

add_action('wp_ajax_morale_chacked', 'morale_chacked');
add_action('wp_ajax_nopriv_morale_chacked', 'morale_chacked');


function load_search_results() {
    $search_query = isset($_POST['s']) ? $_POST['s'] : '';
    $page = !empty($_POST['page']) ? $_POST['page'] : 1;

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 10,
        'paged' => $page,
        'post_status' => 'publish',
        's' => $search_query
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        if ($page == 1) {
            the_ad('6448');
        }
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', 'post');

        }
        the_ad('6448');

    } else {
        echo '<p>No posts found.</p>';
    }

    wp_die();
}

add_action('wp_ajax_load_search_results', 'load_search_results');
add_action('wp_ajax_nopriv_load_search_results', 'load_search_results');

function modify_search_query($query) {
    // בדוק שמדובר בשאילתת חיפוש ושהוא מתבצע בחזית (לא באדמין)
    if ($query->is_search() && !is_admin()) {
        // הגבל את מספר התוצאות ל-10
        $query->set('posts_per_page', 10);
        // הגדר שהשאילתה תתחיל מהעמוד הראשון אלא אם צוין אחרת
        $page = get_query_var('paged') ? get_query_var('paged') : 1;
        $query->set('paged', $page);
    }
}
add_action('pre_get_posts', 'modify_search_query');

function get_user_preferences() {
    $userId = isset($_POST['userId']) ? $_POST['userId'] : '';

    if (empty($userId)) {
        wp_send_json_error('Missing required parameter: userId');
        return;
    }

    $firebaseManager = new FirebaseManager();
    $userPreferences = [
        'categories' => $firebaseManager->getUserCategories($userId),
        'sites' => $firebaseManager->getUserSites($userId),
        'moraleChecked' => $firebaseManager->getUserMoraleChecked($userId)
    ];

    wp_send_json_success($userPreferences);
}
add_action('wp_ajax_get_user_preferences', 'get_user_preferences');
add_action('wp_ajax_nopriv_get_user_preferences', 'get_user_preferences');