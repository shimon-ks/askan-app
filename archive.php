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

        <div class="posts">

        </div>


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
