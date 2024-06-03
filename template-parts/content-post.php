<?php  $sites_images = get_field('sites_images', 'option'); ?>

<?php  
$source_key = get_field('source_post');
$source_name = '';

switch ($source_key) {
    case 'hm-news':
        $source_name = 'המחדש';
        break;
    case 'jdn':
        $source_name = 'חדשות JDN';
        break;
    case 'kol-hay':
        $source_name = 'קול חי';
        break;
    case 'bhol':
        $source_name = 'בחדרי חרדים';
        break;
    case 'bizzness':
        $source_name = 'ביזנעס';
        break;
    case 'askan':
        $source_name = 'עסקן';
        break;
    case 'kikar':
        $source_name = 'כיכר השבת';
        break;
    // הוסף כאן את שאר האתרים שלך
    default:
        $source_name = '';
        break;



}

$link = "https://api.whatsapp.com/send?text=" . urlencode(
    'ראיתי באפליקציית עסקן News' . "\n" .
    'ידיעה שיכולה לעניין אותך' . "\n" .
    'מאתר: ' . $source_name . "\n" .
    '*' . get_the_title() . '*' . "\n" .get_the_excerpt()
);
?>
<div class="single-post" data-post-id ="<?php the_ID()?>">
        <div class="top">
            <?php foreach ($sites_images as $key => $site) {
                if($site['site'] == get_field('source_post')){
                    $url = $site['logo'];
                }
            }?>
            <img src="<?php echo $url; ?>" alt="" class="site-img">
            <b><?php echo get_post_time( 'H:i' )." "; ?> </b>

            <?php
                        $post_id = get_the_ID();

                        // קבל את זמן פרסום הפוסט
                        $post_date = get_post_time('U', false, $post_id);

                        // קבל את זמן הפרסום בפורמט של Unix timestamp
                        $current_timestamp = current_time('U');

                        // חשב את הפער בין זמן הפרסום לזמן הנוכחי
                        $time_difference = $current_timestamp - $post_date;

                        // אם פער הזמן הוא ימים
                        if ($time_difference > 86400) {
                            $days = floor($time_difference / 86400);
                            $time_ago = "$days ימים";
                        } elseif ($time_difference > 3600) {
                            // אם פער הזמן הוא שעות
                            $hours = floor($time_difference / 3600);
                            $time_ago = "$hours שעות";
                        } else {
                            // אחרת, חשב את הפער בפורמט של דקות
                            $minutes = floor($time_difference / 60);
                            $time_ago = "$minutes דקות";
                        }

                        // הצג את התוצאה בעברית
                        echo " לפני $time_ago";
?>
        </div>
        <a href="<?php echo get_field('external'); ?>" class="post-link">
        <div class="post-ttl">
            <?php
            if(get_field('main_image')){?>
            <div class="img" style="background-image:url(<?php echo get_field('main_image'); ?>);"></div>
            <?php
            }
            ?>
            <h2><?php the_title(); ?></h2>
        </div>

        <?php 

            $content = get_the_content();
            $content = strip_tags($content);
            $words = explode(' ', $content, 51); 
            $first30Words = implode(' ', array_slice($words, 0, 50));
            ?>
        <div class="content">
            <?php
            if ( has_excerpt() ) {
                the_excerpt();
            } else {
                echo $first30Words; 
            } ?>
            </div>
        לדיווח המלא</a>
        <div class="bottom-post">
            <div class="tags">
            </div>
            <div class="option">
                <div class="report-wrap" data-post-id ="<?php the_ID()?>">

                    <div class="report-txt">דווח על תוכן לא הולם</div>
                </div>

                <div class="open-report">
                <svg width="29" height="30" viewBox="0 0 61 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.2">
                    <path d="M61 30.6794C61 22.5319 57.8273 14.873 52.0672 9.11155C46.307 3.35141 38.6468 0.178711 30.5007 0.178711C22.3532 0.178711 14.6943 3.35141 8.93284 9.11155C3.17134 14.873 0 22.5319 0 30.6794C0 38.8269 3.1727 46.4857 8.93284 52.2472C14.6943 58.0074 22.3532 61.1801 30.5007 61.1801C38.6482 61.1801 46.307 58.0074 52.0672 52.2472C57.8273 46.4857 61 38.8269 61 30.6794ZM30.4993 56.6885C23.5523 56.6885 17.0204 53.9826 12.1083 49.0704C7.19608 44.1583 4.49024 37.6264 4.49024 30.6794C4.49024 23.7324 7.19608 17.2005 12.1083 12.2883C17.0204 7.37616 23.5523 4.67031 30.4993 4.67031C37.4463 4.67031 43.9782 7.37616 48.8904 12.2883C53.8026 17.2005 56.5084 23.7324 56.5084 30.6794C56.5084 37.6264 53.8026 44.1583 48.8904 49.0704C43.9782 53.9826 37.4463 56.6885 30.4993 56.6885Z" fill="#424041"/>
                    <path d="M30.5456 14.5576C29.3057 14.5576 28.2998 15.5635 28.2998 16.8034V37.4634C28.2998 38.7034 29.3057 39.7092 30.5456 39.7092C31.7856 39.7092 32.7914 38.7034 32.7914 37.4634V16.8034C32.7914 15.5635 31.7856 14.5576 30.5456 14.5576Z" fill="#424041"/>
                    <path d="M30.5456 42.3379C29.3057 42.3379 28.2998 43.3437 28.2998 44.5837V45.2261C28.2998 46.4661 29.3057 47.4719 30.5456 47.4719C31.7856 47.4719 32.7914 46.4661 32.7914 45.2261V44.5837C32.7914 43.3437 31.7856 42.3379 30.5456 42.3379Z" fill="#424041"/>
                    </g>
                </svg>

                </div>
                <a href="<?php echo $link; ?>" target="_blank" class="whatsapp">
                <svg width="35" height="44" viewBox="0 0 42 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.896279 21.8704C0.895295 25.4268 1.82455 28.8994 3.59149 31.9601L0.727295 42.4177L11.4294 39.6117C14.3895 41.2232 17.7061 42.0675 21.0763 42.0677H21.0851C32.2111 42.0677 41.2678 33.0143 41.2726 21.8864C41.2747 16.4941 39.1766 11.4236 35.3648 7.60882C31.5536 3.79436 26.4849 1.69256 21.0843 1.69009C9.95713 1.69009 0.900873 10.7431 0.896279 21.8704Z" fill="url(#paint0_linear_1154_206)"/>
                    <path d="M0.175547 21.8639C0.174399 25.5483 1.13695 29.1449 2.96691 32.3153L0 43.1479L11.0859 40.2412C14.1404 41.9066 17.5794 42.7846 21.079 42.7859H21.0879C32.613 42.7859 41.9952 33.4068 42 21.8805C42.0019 16.2946 39.8284 11.0418 35.8804 7.09038C31.932 3.13942 26.682 0.961983 21.0879 0.959686C9.56091 0.959686 0.180141 10.3375 0.175547 21.8639ZM6.77743 31.7693L6.36348 31.1123C4.62344 28.3455 3.70503 25.1483 3.70634 21.8652C3.7101 12.2848 11.507 4.4903 21.0945 4.4903C25.7374 4.49227 30.1009 6.30221 33.3827 9.5861C36.6645 12.8703 38.4704 17.236 38.4693 21.8792C38.4649 31.4598 30.6679 39.2551 21.0879 39.2551H21.0811C17.9618 39.2535 14.9025 38.4158 12.2345 36.8327L11.5995 36.4563L5.02097 38.1811L6.77743 31.7693Z" fill="url(#paint1_linear_1154_206)"/>
                    <path d="M15.8612 13.1246C15.4698 12.2546 15.0578 12.237 14.6856 12.2218C14.3807 12.2087 14.0323 12.2096 13.6841 12.2096C13.3357 12.2096 12.7695 12.3407 12.2909 12.8633C11.8119 13.3863 10.4619 14.6502 10.4619 17.221C10.4619 19.7919 12.3344 22.2762 12.5954 22.6251C12.8568 22.9735 16.2102 28.4177 21.5213 30.5122C25.9352 32.2526 26.8335 31.9064 27.7914 31.8192C28.7495 31.7323 30.8829 30.5557 31.3183 29.3354C31.7538 28.1155 31.7538 27.0698 31.6233 26.8513C31.4926 26.6335 31.1441 26.5028 30.6216 26.2416C30.099 25.9802 27.53 24.7162 27.0511 24.5417C26.5721 24.3674 26.2238 24.2805 25.8752 24.8038C25.5268 25.3262 24.5262 26.5028 24.2212 26.8513C23.9166 27.2005 23.6116 27.244 23.0892 26.9826C22.5663 26.7204 20.8835 26.1694 18.8871 24.3894C17.3337 23.0045 16.285 21.2942 15.9802 20.771C15.6754 20.2485 15.9475 19.9654 16.2096 19.705C16.4443 19.4709 16.7323 19.0948 16.9937 18.7898C17.2542 18.4847 17.3413 18.2669 17.5155 17.9185C17.6899 17.5697 17.6025 17.2646 17.4722 17.0032C17.3413 16.7419 16.3259 14.1577 15.8612 13.1246Z" fill="white"/>
                    <defs>
                    <linearGradient id="paint0_linear_1154_206" x1="20.9998" y1="42.4177" x2="20.9998" y2="1.69009" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#1FAF38"/>
                    <stop offset="1" stop-color="#60D669"/>
                    </linearGradient>
                    <linearGradient id="paint1_linear_1154_206" x1="21" y1="43.1479" x2="21" y2="0.959686" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#F9F9F9"/>
                    <stop offset="1" stop-color="white"/>
                    </linearGradient>
                    </defs>
                </svg>


                </a>



            </div>
        </div>
    </div>
