<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package askan
 */

get_header();
$sites_images = get_field('sites_images', 'option');

global $wp_query;
?>
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
    <?php
    // if($wp_query):
    // $i = 0; 
    ?>
        <div class="posts">
            <?php
                // while($wp_query->have_posts()){
                //     $wp_query->the_post();
                //     $tags = get_the_tags();
                     ?>
                    <!-- <div class="single-post">
                        <div class="top"> -->
                            <?php
                            //  foreach ($sites_images as $key => $site) {
                            //     if($site['site'] == get_field('source_post')){
                            //         $url = $site['logo'];
                            //     }
                            // }
                            ?>
                            <!-- <img src="<?php echo $url; ?>" alt="" class="site-img">
                            <b>
                                <?php //  echo get_post_time( 'H:i' )." "; ?> </b> -->

                            <?php
                                        // $post_id = get_the_ID();

                                        // // קבל את זמן פרסום הפוסט
                                        // $post_date = get_post_time('U', false, $post_id);

                                        // // קבל את זמן הפרסום בפורמט של Unix timestamp
                                        // $current_timestamp = current_time('U');

                                        // // חשב את הפער בין זמן הפרסום לזמן הנוכחי
                                        // $time_difference = $current_timestamp - $post_date;

                                        // // אם פער הזמן הוא ימים
                                        // if ($time_difference > 86400) {
                                        //     $days = floor($time_difference / 86400);
                                        //     $time_ago = "$days ימים";
                                        // } elseif ($time_difference > 3600) {
                                        //     // אם פער הזמן הוא שעות
                                        //     $hours = floor($time_difference / 3600);
                                        //     $time_ago = "$hours שעות";
                                        // } else {
                                        //     // אחרת, חשב את הפער בפורמט של דקות
                                        //     $minutes = floor($time_difference / 60);
                                        //     $time_ago = "$minutes דקות";
                                        // }

                                        // // הצג את התוצאה בעברית
                                        // echo " לפני $time_ago";
    ?>
                        <!-- </div> -->
                        <!-- <a href="<?php // echo get_post_permalink(); ?>" class="post-link">
                        <div class="post-ttl"> -->
                            <?php
                            // if(get_field('main_image')){
                                ?>
                            <!-- <div class="img" style="background-image:url(<?php echo get_field('main_image'); ?>);"></div> -->
                            <?php
                            // }
                            ?>
                            <!-- <h2 style="width: <?php echo get_field('main_image') ? '57%' : '100%'; ?>"><?php the_title(); ?></h2>
                        </div> -->

                        <?php 

                            // $content = get_the_content();
                            // $content = strip_tags($content);
                            // $words = explode(' ', $content, 51); 
                            // $first30Words = implode(' ', array_slice($words, 0, 50));
                             
                            ?>
                        <!-- <div class="content"><?php echo $first30Words; ?></div>
                     לדיווח המלא</a>
                        <div class="bottom-post">
                            <div class="tags">
                            <?php
                            // if($tags){
                                ?>
                                <?php
                                //  foreach ($tags as $key => $tag) {
                                    ?>
                                    <a href="<?php echo get_term_link( $tag, 'post_tag' ); ?>" class="tag-link"><?php echo $tag->name; ?></a>
                                <?php
                                // }
                                ?>
                            <?php 
                        // }
                         ?>
                            </div>
                            <div class="option">
                            <div class="report">דיווח</div>
                                <div><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <path d="M16 10C17.1046 10 18 9.10457 18 8C18 6.89543 17.1046 6 16 6C14.8954 6 14 6.89543 14 8C14 9.10457 14.8954 10 16 10Z" fill="black"/>
                                    <path d="M16 18C17.1046 18 18 17.1046 18 16C18 14.8954 17.1046 14 16 14C14.8954 14 14 14.8954 14 16C14 17.1046 14.8954 18 16 18Z" fill="black"/>
                                    <path d="M16 26C17.1046 26 18 25.1046 18 24C18 22.8954 17.1046 22 16 22C14.8954 22 14 22.8954 14 24C14 25.1046 14.8954 26 16 26Z" fill="black"/>
                                    </svg>
                                </div>
                                
                                <div class="share">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 18 20" fill="none">
                                        <path d="M15 20C14.1667 20 13.4583 19.7083 12.875 19.125C12.2917 18.5417 12 17.8333 12 17C12 16.8833 12.0083 16.7623 12.025 16.637C12.0417 16.5123 12.0667 16.4 12.1 16.3L5.05 12.2C4.76667 12.45 4.45 12.6457 4.1 12.787C3.75 12.929 3.38333 13 3 13C2.16667 13 1.45833 12.7083 0.875 12.125C0.291667 11.5417 0 10.8333 0 10C0 9.16667 0.291667 8.45833 0.875 7.875C1.45833 7.29167 2.16667 7 3 7C3.38333 7 3.75 7.07067 4.1 7.212C4.45 7.354 4.76667 7.55 5.05 7.8L12.1 3.7C12.0667 3.6 12.0417 3.48767 12.025 3.363C12.0083 3.23767 12 3.11667 12 3C12 2.16667 12.2917 1.45833 12.875 0.875C13.4583 0.291667 14.1667 0 15 0C15.8333 0 16.5417 0.291667 17.125 0.875C17.7083 1.45833 18 2.16667 18 3C18 3.83333 17.7083 4.54167 17.125 5.125C16.5417 5.70833 15.8333 6 15 6C14.6167 6 14.25 5.929 13.9 5.787C13.55 5.64567 13.2333 5.45 12.95 5.2L5.9 9.3C5.93333 9.4 5.95833 9.51233 5.975 9.637C5.99167 9.76233 6 9.88333 6 10C6 10.1167 5.99167 10.2373 5.975 10.362C5.95833 10.4873 5.93333 10.6 5.9 10.7L12.95 14.8C13.2333 14.55 13.55 14.354 13.9 14.212C14.25 14.0707 14.6167 14 15 14C15.8333 14 16.5417 14.2917 17.125 14.875C17.7083 15.4583 18 16.1667 18 17C18 17.8333 17.7083 18.5417 17.125 19.125C16.5417 19.7083 15.8333 20 15 20Z" fill="black"/>
                                    </svg>
                                </div>
                                <div class="report"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                                    <g clip-path="url(#clip0_104_756)">
                                        <path d="M0.554835 12.9447C0.554226 15.1463 1.12948 17.296 2.2233 19.1907L0.450226 25.6645L7.07535 23.9274C8.90777 24.925 10.9609 25.4477 13.0472 25.4478H13.0527C19.9402 25.4478 25.5467 19.8433 25.5497 12.9546C25.551 9.61654 24.2522 6.47765 21.8925 4.11612C19.5332 1.75479 16.3954 0.453672 13.0522 0.452148C6.16393 0.452148 0.557781 6.05637 0.554937 12.9447" fill="url(#paint0_linear_104_756)"/>
                                        <path d="M0.108672 12.9407C0.107961 15.2215 0.703828 17.448 1.83666 19.4106L0 26.1165L6.86268 24.3171C8.75357 25.3481 10.8825 25.8916 13.0489 25.8924H13.0544C20.189 25.8924 25.997 20.0863 26 12.951C26.0012 9.49305 24.6557 6.24132 22.2117 3.79519C19.7674 1.34936 16.5174 0.00142187 13.0544 0C5.91866 0 0.111617 5.80531 0.108773 12.9407H0.108672ZM4.19555 19.0726L3.9393 18.6659C2.86213 16.9531 2.29359 14.9739 2.2944 12.9415C2.29673 7.01076 7.12339 2.18562 13.0585 2.18562C15.9327 2.18684 18.6339 3.30728 20.6655 5.34016C22.6971 7.37323 23.815 10.0758 23.8143 12.9502C23.8116 18.881 18.9849 23.7067 13.0544 23.7067H13.0502C11.1192 23.7057 9.22533 23.1871 7.57372 22.2071L7.18067 21.9741L3.10822 23.0418L4.19555 19.0725V19.0726Z" fill="url(#paint1_linear_104_756)"/>
                                        <path d="M9.81886 7.53066C9.57653 6.99207 9.32151 6.9812 9.09107 6.97176C8.90236 6.96363 8.68664 6.96424 8.47113 6.96424C8.25541 6.96424 7.90492 7.04539 7.60866 7.36887C7.3121 7.69265 6.47644 8.47509 6.47644 10.0665C6.47644 11.658 7.63557 13.1959 7.79716 13.4119C7.95895 13.6276 10.0349 16.9978 13.3227 18.2944C16.0551 19.3718 16.6112 19.1575 17.2042 19.1035C17.7973 19.0497 19.118 18.3213 19.3875 17.5659C19.6571 16.8107 19.6571 16.1634 19.5763 16.0281C19.4954 15.8933 19.2797 15.8124 18.9562 15.6507C18.6327 15.4889 17.0424 14.7064 16.7459 14.5984C16.4494 14.4905 16.2338 14.4367 16.018 14.7606C15.8023 15.084 15.1829 15.8124 14.9941 16.0281C14.8055 16.2443 14.6167 16.2712 14.2933 16.1094C13.9696 15.9471 12.9279 15.606 11.692 14.5041C10.7304 13.6468 10.0812 12.588 9.89249 12.2641C9.70379 11.9407 9.87228 11.7654 10.0345 11.6042C10.1798 11.4593 10.3581 11.2265 10.5199 11.0377C10.6812 10.8488 10.7351 10.714 10.8429 10.4983C10.9509 10.2824 10.8968 10.0935 10.8161 9.9317C10.7351 9.76991 10.1065 8.1703 9.81886 7.53076" fill="white"/>
                                    </g>
                                    <defs>
                                        <linearGradient id="paint0_linear_104_756" x1="12.9999" y1="25.6645" x2="12.9999" y2="0.452148" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#1FAF38"/>
                                        <stop offset="1" stop-color="#60D669"/>
                                        </linearGradient>
                                        <linearGradient id="paint1_linear_104_756" x1="13" y1="26.1165" x2="13" y2="0" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#F9F9F9"/>
                                        <stop offset="1" stop-color="white"/>
                                        </linearGradient>
                                        <clipPath id="clip0_104_756">
                                        <rect width="26" height="26.2031" fill="white"/>
                                        </clipPath>
                                    </defs>
                                    </svg></i></div>
                                 <div><i class="fa-sharp fa-solid fa-share-nodes"></i></div> -->
                            <!-- </div>
                        </div>
                    </div>  -->
                <?php
                    // $i++;
                    // if($i == 500){
                        ?>
                        <!-- <div class="bunner">
                            <button><i class="fa-sharp fa-regular fa-circle-xmark"></i></button>
                            <img src="<?php echo get_template_directory_uri(  ).'/images/bunner.png'; ?>" alt="">
                        </div>
                        <div class="status">
                            <h2>סטטוס דוסינייעס</h2> -->
                            <!-- <img src="<?php echo get_template_directory_uri(  ).'/images/status1.png'; ?>" alt="">
                            <img src="<?php echo get_template_directory_uri(  ).'/images/status2.png'; ?>" alt="">
                            <img src="<?php echo get_template_directory_uri(  ).'/images/status1.png'; ?>" alt=""> -->
                        <!-- </div> --> 
                    <?php   
                //     }
                // }
            ?>
        </div>
    <?php // endif; ?>
    <!-- <button class="new-updates">
        <i class="fa-solid fa-circle-arrow-up"></i>
        2 עדכונים חדשים
    </button>
    <div class="bunner">
        <img src="<?php // echo get_template_directory_uri(  ).'/images/small-bunner.png'; ?>" alt="">
    </div> -->

    <div class="reportModal">
        <div class="modal-content">
            <span class="close"><i class="fa fa-times-circle-o" aria-hidden="true"></i> הסתרה </span>
            <div class="line"></div>
            <span class="rep"><i class="fas fa-exclamation-triangle"></i> דיווח </span>
        </div>
    </div>
</div>

<?php
get_footer();
