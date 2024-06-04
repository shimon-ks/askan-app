<?php
$terms = get_terms( array(
    'taxonomy'   => 'topic',
    'hide_empty' => false,
) );
?>
<div class="side-manu-warp">
    <div class="side-manu">
        <div id="lottie-loader">
            <div id="lottie-animation"></div>
        </div>
        <span class="close"><i class="fa-solid fa-x"></i></span>

        <h3 class="main-menu-ttl">הגדרות והעדפות</h3>


                <div class="side-menu-section">
                    <label class="check-container container">
                            <input type="checkbox" id="morale"  value="morale" >
                            <span class="checkmark"></span>
                    </label>
                    <div class="right">
                        <h3>שומרים על המורל</h3>
                        <small>
                            <p>
                            פורמולות חדשות במינון מיוחד עם דגש על חדשות טובות, למי שרוצה להישאר מעודכן בלי להחשף לתוכן קשה
                            </p>
                        </small>
                    </div>
                    <img class="face-image" src="<?php echo get_template_directory_uri(  ).'/images/face.svg'; ?>" alt="Description of image">
                </div>






        <?php $sites_images = get_field('sites_images', 'option'); ?>
        <div class="writers">

        <small>
            <p>
                כאן תוכלו לקבוע מה יופיע בפיד שלכם כברירת מחדל בלי שתצטרכו לסנן כל פעם מחדש
            </p>
        </small>
            <div class="writers-ttl">
                <h2>אני רוצה לקבל מבזקים מ-</h2>
            </div>

            <div class="sites-slider cat-list">
                <?php foreach ($sites_images as $key => $site): ?>
                <div class="site topic">
                    <div class="topic-img" style="background-image: url(<?php echo $site['logo']; ?>);"></div>
                    <!-- <b><?php echo $site['site_name_he']; ?></b> -->
                    <label class="check-container">
                        <input type="checkbox" class="site-select" value="<?php echo $site['site']; ?>" >
                        <span class="checkmark"></span>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="writers">
            <div class="writers-ttl">
                <h2>נושאים שמעניינים אותי</h2>
            </div>
            <div class="sites-slider cat-list">
                <?php
                $menu_items = wp_get_nav_menu_items('121');
                if (!empty($menu_items)) {
                    foreach ($menu_items as $menu_item) {
                       $item_image = get_field('category_image_for_sidebar', 'category_' . $menu_item->object_id );  ?>
                        <div class="site topic">
                            <div class="topic-img" style="background-image: url(<?php  echo $item_image; ?>);"></div>
                            <b><?php echo $menu_item->title; ?></b>
                            <label class="check-container">
                                <input type="checkbox" id="category-select" value="<?php echo $menu_item->title; ?>" >
                                <span class="checkmark"></span>
                            </label>
                        </div>
                <?php }
                }

                ?>
            </div>
        </div>
        <?php if(basename(get_page_template()) == 'setting-page.php'){?>
            <div class="setting-bottom">
                <button>סיימתי להגדיר     <i class="fa-solid fa-arrow-left"></i></button>
            </div>
        <?php } ?>
        
    </div>
</div>