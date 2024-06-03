<?php
// $args = array(
//     'post_type' => 'post',
//     'post_status' => 'publish',
//     'post_per_page' => -1,
//     'order' => 'DESC',
// );
// $query = new WP_Query($args);

$sites_images = get_field('sites_images', 'option');

get_header(); ?>

<div class="askan-content">
    <!-- <div class="sites-slider front">
        <div class="site-warp">
            <div class="site" style="background-image: url(<?php echo get_template_directory_uri().'/images/h1.jpeg'; ?>);"></div>
            <div class="site-ttl">
                פוליטי
            </div>
        </div>
        <div class="site-warp">

            <div class="site" style="background-image: url(<?php echo get_template_directory_uri().'/images/h2.jpeg'; ?>);"></div>
            <div class="site-ttl">
                משפט
            </div>
        </div>
        <div class="site-warp">
            <div class="site" style="background-image: url(<?php echo get_template_directory_uri().'/images/h3.jpeg'; ?>);"></div>
            <div class="site-ttl">
                בעולם
            </div>
        </div>
        <div class="site-warp">
            <div class="site" style="background-image: url(<?php echo get_template_directory_uri().'/images/h4.jpeg'; ?>);"></div>
            <div class="site-ttl">
                מעניין
            </div>
        </div>
        <div class="site-warp">
            <div class="site" style="background-image: url(<?php echo get_template_directory_uri().'/images/h5.png'; ?>);"></div>
            <div class="site-ttl">
                חסידי ואנ"ש
            </div>
        </div>
    </div> -->
    <!-- <button onclick="window.ReactNativeWebView.postMessage('openNotificationSettings')">פתח הגדרות התראות</button> -->


    <div class="posts"></div>
    <!-- <button class="new-updates">
        <i class="fa-solid fa-circle-arrow-up"></i>
        2 עדכונים חדשים
    </button>
    <div class="bunner">
        <img src="<?php echo get_template_directory_uri(  ).'/images/small-bunner.png'; ?>" alt="">
    </div> -->

    <div class="reportModal">
        <div class="modal-content">
            <span class="close"><i class="fa fa-times-circle-o" aria-hidden="true"></i> הסתרה </span>
            <div class="line"></div>
            <span class="rep"><i class="fas fa-exclamation-triangle"></i>דווח על תוכן לא הולם</span>
        </div>
    </div>
</div>

<?php get_footer(); ?>