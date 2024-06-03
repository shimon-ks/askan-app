<?php /* Template Name: test-rss */ 
get_header(); 
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// JDN - true
// $args = array(
//   'post_type' => 'post',
//   'post_status' => 'publish',
//   'post_per_page' => -1,
//   'order' => 'DESC',
//   'meta_query' => array(
//     array(
//       'key' => 'source_post',
//       'value'   => 'jdn',
//       'compare' => 'LIKE'
//     ),
//   ),
// );

// $jdn_query = new WP_Query($args);
// $title_arr = array();

// if($jdn_query->have_posts()):
//   while($jdn_query->have_posts()){
//     $jdn_query->the_post();
//     $title_arr[] = get_the_title(get_the_ID());
//   }
// endif;

$feed = file_get_contents('https://www.jdn.co.il/feed/');
$xml = simplexml_load_string($feed);
// echo '<pre>';
// print_r($title_arr);
// echo '</pre>';

foreach ($xml->channel->item as $item) {
  $title = $item->title;
  echo '<pre>';
  print_r($item);
  echo '</pre>';
  // $flag = true;
  // echo '<br>';
  // foreach ($title_arr as $key => $ttl) {
  //   if($ttl == $title){
  //     echo $title.' - in array.';
  //     $flag = false;
  //     break;
  //   }
  // }
  // if(in_array($title, $title_arr)){
  //   echo $title.' - in array.';
  //   continue;
  // }else{
  // if($flag):
  //   echo $title.' - not in array.';
    
    $des = $item->description;
    $date = $item->pubDate;
    // $postdate = date("Y-m-d H:i:s", $date);
    
    $post = array(
      'post_title' => $title,
      'post_type' => 'post',
      'post_content' => $des,
      'post_status' => 'publish',
      // 'post_date' => $postdate,
    );
  
    // $post_id = wp_insert_post($post, $wp_error = false);
    // update_field('source_post', 'jdn', $post_id);
    break;
  // endif;
}
die;

// KIKAR - true
$feed = file_get_contents("https://a.kikar.co.il/v1/rss/articles/latest/rss2");
$xml = simplexml_load_string($feed);

foreach ($xml->channel->item as $item) {

  $title = $item->title;
  $des = $item->description;
  $date = $item->pubDate;
  // $postdate = date("Y-m-d H:i:s", $date);
  // echo '<pre>';
  // echo $date;
  // echo '</pre>';
  
  $post = array(
    'post_title' => $title,
    'post_type' => 'post',
    'post_content' => $des,
    'post_status' => 'publish',
    // 'post_date' => $postdate,
  );

  $post_id = wp_insert_post($post, $wp_error = false);
  update_field('source_post', 'kikar', $post_id);
  break;
}

//bhol - 403
// $feed = file_get_contents('https://www.bhol.co.il/rss/index.xml');
// $xml = simplexml_load_string($feed);

// kol-hai - 403
// $feed = file_get_contents("https://www.93fm.co.il/feed");
// $xml = simplexml_load_string($feed);

// bahazit - true
$feed = file_get_contents("https://www.bahazit.co.il/feed");
$xml = simplexml_load_string($feed);
foreach ($xml->channel->item as $item) {

  $title = $item->title;
  $des = $item->description;
  // $date = $item->pubDate;
  // $postdate = date("Y-m-d H:i:s", $date);
  // echo '<pre>';
  // echo $date;
  // echo '</pre>';
  
  $post = array(
    'post_title' => $title,
    'post_type' => 'post',
    'post_content' => $des,
    'post_status' => 'publish',
    // 'post_date' => $postdate,
  );

  $post_id = wp_insert_post($post, $wp_error = false);
  update_field('source_post', 'bahazit', $post_id);
  break;
}

// kol-barama - 403

// hamehadesh - true
$feed = file_get_contents("https://hm-news.co.il/feed/");
$xml = simplexml_load_string($feed);
foreach ($xml->channel->item as $item) {

  $title = $item->title;
  $des = $item->description;
  $date = $item->pubDate;
  // $postdate = date("Y-m-d H:i:s", $date);
  // echo '<pre>';
  // echo $date;
  // echo '</pre>';
  
  $post = array(
    'post_title' => $title,
    'post_type' => 'post',
    'post_content' => $des,
    'post_status' => 'publish',
    // 'post_date' => $postdate,
  );

  $post_id = wp_insert_post($post, $wp_error = false);
  update_field('source_post', 'kol-barama', $post_id);
  break;
}

// haredim10 - 403  
// update_field('last_time_posts', date("H:i:s"), 'options');
// $last_time = get_field('last_time_posts', 'options');
// echo $last_time;
?>