<?php
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	
/**
 * askan functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package askan
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function askan_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on askan, use a find and replace
		* to change 'askan' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'askan', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'askan' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'askan_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'askan_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function askan_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'askan_content_width', 640 );
}
add_action( 'after_setup_theme', 'askan_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function askan_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'askan' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'askan' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'askan_widgets_init' );

/**
 * Enqueue scripts and styles.
 */

function askan_scripts() {
	wp_enqueue_style( 'askan-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'askan-style', 'rtl', 'replace' );
	wp_enqueue_script( 'askan-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );    
	// slick
	wp_enqueue_style('slick-css', get_stylesheet_directory_uri(). '/assets/src/library/css/slick.css', time(), true );
	wp_enqueue_style('slick-theme-css', get_stylesheet_directory_uri(). '/assets/src/library/css/slick-theme.css', array('slick-css'), true, 'all' );	
	wp_enqueue_script( 'slick-script',  get_template_directory_uri().'/assets/src/library/js/slick.min.js', array('jquery'), false );	
	wp_enqueue_script( 'carousel-js', get_template_directory_uri() . '/assets/src/carousel/index.js', array('jquery', 'slick-script'), filemtime( untrailingslashit( get_template_directory() ) . '/assets/src/carousel/index.js' ), true );

	wp_enqueue_script('askan-js', get_template_directory_uri().'/js/scripts.js',array(), time(), true);
	wp_localize_script('askan-js', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

	wp_enqueue_script('user-data', get_template_directory_uri().'/js/user-data.js',array(), null);
	wp_localize_script('user-data', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'askan_scripts' );
add_action( 'wp_enqueue_scripts', 'askan_more_scripts' );
function askan_more_scripts() {

	
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

require_once(get_template_directory() . '/cpt.php');

require 'vendor/autoload.php';

require_once(get_template_directory() . '/inc/askan-classes/FirebaseManager.php');


require_once(get_template_directory() . '/inc/sidebar-proccess.php');


function add_active_class_to_category_menu_item($classes, $item, $args) {
    if (is_category() && $item->object_id == get_queried_object_id()) {
        $classes[] = 'active'; 
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'add_active_class_to_category_menu_item', 10, 3);



function scrape_posts(){

	require __DIR__ .'/vendor/autoload.php';
	require_once( get_template_directory() . '/scrape/crawler.php');
	require_once( get_template_directory() . '/scrape/rss.php');
	require_once( get_template_directory() . '/scrape/oneSignal.php');
	require_once( get_template_directory() . '/scrape/scrapeKikar.php');
	require_once( get_template_directory() . '/scrape/scrapeJdn.php');
	require_once( get_template_directory() . '/scrape/scrape93.php');
	require_once( get_template_directory() . '/scrape/generalHelper.php');
	include_once( get_template_directory() . '/scrape/scrape.php');
}
if (isset($_GET['import_posts'])) {
	scrape_posts();
}
// add_action('get_posts_event', 'scrape_posts');
// wp_schedule_event(time(), 'minute', 'get_posts_event');

function displayPostDateOrTime($postDate) {
    $currentDateTime = new DateTime();
    $currentDate = $currentDateTime->format('Y-m-d');

    $postDateTime = new DateTime($postDate);
    $postDateFormatted = $postDateTime->format('Y-m-d');

    if ($postDateFormatted === $currentDate) {
        return $postDateTime->format('H:i');
    } else {
        return $postDateTime->format('Y-m-d');
    }
}

function get_category_for_menu() {
	$acf_field_name = 'show_category_in_menu';

	// Get all categories
	$categories = get_terms('category', array('hide_empty' => false));

	// Initialize an array to hold categories where the ACF field is true
	$matching_categories = array();

	// Check each category
	foreach ($categories as $category) {
		// Check if the ACF field for this category is true
		if (get_field($acf_field_name, 'category_' . $category->term_id) == true) {
			// Add category to the list
			$matching_categories[] = $category;
		}
	}

	$args = array(
		'taxonomy' => 'category',
		'hide_empty' => false,
		'parent' => 0 ,
		'exclude' => array(get_cat_ID('Uncategorized'))
	);
	
	$parent_categories = get_terms($args);

	return $parent_categories;
}




if (isset( $_GET['import_posts_web']) ) {

	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
	require __DIR__ .'/vendor/autoload.php';
	require_once( get_template_directory() . '/scrape/generalHelper.php');
	include_once( get_template_directory() . '/scrape/scrape_web93.php');
	include_once( get_template_directory() . '/scrape/scrape_web_new_hm.php');
	include_once( get_template_directory() . '/scrape/scrape_web_jdn.php');
	include_once( get_template_directory() . '/scrape/scrape_web_bizzness.php');
	include_once( get_template_directory() . '/scrape/scrape_web_kikar.php');
	include_once( get_template_directory() . '/scrape/scrape_web_bhol.php');
	// include_once( get_template_directory() . '/scrape/scrape_web_h10.php');
	die;

}

function load_posts_by_categories() {



	$page = !empty($_POST['page']) ? $_POST['page'] : 1; // קבלת מספר העמוד מהבקשת AJAX
	$args = array(
        'post_type' => 'post',
        'posts_per_page' => 10,
		'paged' => $page,
		'post_status' => 'publish' 
    );
	if (isset( $_POST['categories'])) {
		$category_names = $_POST['categories'];
		$category_ids = array();
		foreach ($category_names as $category_name) {
			$category = get_term_by('name', $category_name, 'category');
			if ($category) {
				$category_ids[] = $category->term_id;

				$child_categories = get_term_children($category->term_id, 'category');
				foreach ($child_categories as $child_cat_id) {
					$category_ids[] = $child_cat_id;
				}
				
			}
		}
		$args['category__in'] = $category_ids;

	}


    $query = new WP_Query($args);

    if ($query->have_posts()) : 
        while ($query->have_posts()) : $query->the_post();
			get_template_part('template-parts/content','post');
        endwhile;
    endif;

    wp_die();
}

add_action('wp_ajax_load_posts_by_categories', 'load_posts_by_categories');
add_action('wp_ajax_nopriv_load_posts_by_categories', 'load_posts_by_categories');


$tokens[] = 'ExponentPushToken[IiUqNhOLdMmOnPeFl1UtBP]';
$title = 'test';
$message = 'test message';
$image = '';
$url = '';
if (isset( $_GET['fire'])) {
	$res = sendExpoNotifications($tokens, $title, $message, $image, $url) ;
	var_dump( $res );
	die;
} 




function post_report() {

	$post_id = $_POST['post_id'];
	$report_post_time = get_field('report_post_time' , $post_id);
	if (!$report_post_time) {
		update_field('report_post_time' ,1, $post_id);
	}else{
		$report_post_time = $report_post_time + 1 ;
		update_field('report_post_time' ,$report_post_time, $post_id);
	}
	if ($report_post_time > 4 ) {
		$post_data = array(
			'ID'          => $post_id,
			'post_status' => 'draft'
		);
		
		wp_update_post( $post_data );
	}
	die;
}

add_action('wp_ajax_post_report', 'post_report');
add_action('wp_ajax_nopriv_post_report', 'post_report');

add_filter( 'manage_posts_columns', 'add_custom_columns' );
function add_custom_columns( $columns ) {
    $columns['source_post'] = 'מפרסם'; // שם העמודה כפי שיתצג ברשימת הפוסטים
    return $columns;
}


function custom_columns_content($column, $post_id) {
    switch ($column) {
        case 'source_post':
            // הצגת ערך מתוך שדה מותאם אישית
            echo get_field('source_post',$post_id, true);
            break;
    }
}
add_action('manage_posts_custom_column', 'custom_columns_content', 10, 2);


function custom_allowed_urls_endpoint()
{
    register_rest_route('custom/v1', '/allowed-urls', array(
        'methods'  => 'GET',
        'callback' => 'get_allowed_urls',
    ));
}
add_action('rest_api_init', 'custom_allowed_urls_endpoint');

// Callback function to get allowed URLs
function get_allowed_urls()
{
    $urls_rep = get_field('allowed_urls', 'option');
    $allowed_urls = array();
    foreach ($urls_rep as $url) {
        $allowed_urls[] = $url['url'];
    }

    // Return the list of allowed URLs as a JSON response
    return rest_ensure_response($allowed_urls);
}


function my_custom_term_function( $term_id, $tt_id, $taxonomy ) {
    if ( 'category' === $taxonomy ) {
    $term = get_term_by( 'id', $term_id, 'category' );
	$term_slug = $term->slug;
	wp_mail( 'dudi.e@askan.co.il', 'נוספה קטגוריה חדשה באתר', 'שם הקטגוריה: '. $term_slug );
    }
}

add_action( 'created_term', 'my_custom_term_function', 10, 3 );


add_action('wp_ajax_registerUserForAllCategoriesAndSites', 'registerUserForAllCategoriesAndSites');
add_action('wp_ajax_nopriv_registerUserForAllCategoriesAndSites', 'registerUserForAllCategoriesAndSites');

function registerUserForAllCategoriesAndSites() {


    if (!isset($_POST['expoToken'])) {
        wp_send_json_error('Token is missing');
        return;
    }


    $userId = $_POST['expoToken'];


	// wp_send_json_error($userId);

    if (!$userId) {
        wp_send_json_error('User not found');
        return;
    }


    // המשך לפונקציה שהוזכרה קודם
    $FirebaseManager = new FirebaseManager();
    $response = $FirebaseManager->registerUserForAllCategoriesAndSites($userId);

    wp_send_json_success($response);
}

