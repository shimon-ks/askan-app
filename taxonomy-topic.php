<?php
$tax_obj = get_queried_object();

$args = array(
    'posts_per_page' => -1,
    'orderby' => 'publish_date',
    'order' => 'ASC',
    'post_type' => 'post',
    'post_status' => 'publish',
    'tax_query' => array(
        array(
            'taxonomy' => 'topic',
            'field' => 'term_id',
            'terms' => $tax_obj->term_id
        )			
    )
);
$query = new WP_Query( $args );
$image = get_field('topic_image', 'topic_'.$tax_obj->term_id);
    // echo '<pre>';
    // ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
    // echo '</pre>';
get_header();
?>
<div class="topic-bunner" style="background-image:url(<?php echo $image; ?>);"><h1><?php echo $tax_obj->name; ?></h1></div>
<div class="askan-content">
    <?php
    if($query){?>
     <div class="posts">
            <?php
                while($query->have_posts()){
                    $query->the_post();
                    $tags = get_the_tags(); ?>
                    <div class="single-post">
                        <div class="top">
                            <img src="<?php echo get_template_directory_uri(  ).'/images/bhol.png'; ?>" alt="" class="site-img">
                            <b><?php echo get_post_time( 'h:s' ); ?></b>
                        </div>
                        
                        <div class="post-ttl">
                            <?php
                            if(has_post_thumbnail()){?>
                            <div class="img" style="background-image:url(<?php echo get_the_post_thumbnail_url(); ?>);"></div>
                            <?php
                            }
                            ?>
                            <h2 style="width: <?php echo has_post_thumbnail()? '61%' : '100%'; ?>"><?php the_title(); ?></h2>
                        </div>
                        <?php 
                            $content = get_the_content();
                            $content = strip_tags($content);
                            $words = explode(' ', $content, 51); 
                            $first30Words = implode(' ', array_slice($words, 0, 50));
                        ?>
                        <div class="content"><?php echo $first30Words; // echo get_the_content(); ?></div>
                        <a href="<?php echo get_post_permalink(); ?>" class="post-link">לדיווח המלא</a>
                        <div class="bottom-post">
                            <?php
                            if($tags){?>
                            <div class="tags">
                                <?php
                                 foreach ($tags as $key => $tag) {?>
                                    <a href="<?php //echo get_tag_link(); ?>" class="tag-link"><?php echo $tag->name; ?></a>
                                <?php
                                }
                                ?>
                            </div>
                            <?php
                            }
                            ?>
                            <div class="option">
                                <div><i class="fa-solid fa-ellipsis-vertical"></i></div>
                                <div class="report"><i class="fa-solid fa-file"></i></div>
                                <div><i class="fa-sharp fa-solid fa-share-nodes"></i></div>
                            </div>
                        </div>
                    </div>
            <?php 
                } ?>
        </div>
    <?php
    } ?>
</div>