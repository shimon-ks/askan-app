<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Fonts -->
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/e5f3d26e42.js" crossorigin="anonymous"></script>
    
    <?php if ( is_single() ) : ?>
        <!-- Meta tags for Open Graph -->
        <meta property="og:title" content="<?php the_title(); ?>" />
        <meta property="og:description" content="<?php echo wp_strip_all_tags( get_the_excerpt(), true ); ?>" />
        <meta property="og:image" content="<?php echo get_field('main_image'); ?>" />
        <meta property="og:url" content="<?php the_permalink(); ?>" />
        <meta property="og:type" content="article" />
    <?php endif; ?>
	<script>
        window.addEventListener('error', function(event) {
            alert(`JavaScript Error: ${event.message} at ${event.filename}:${event.lineno}:${event.colno}`);
        });

        window.onerror = function(message, source, lineno, colno, error) {
            alert(`JavaScript Error: ${message} at ${source}:${lineno}:${colno}`);
            return false; // החזרת false כדי לאפשר את הטיפול הרגיל בשגיאה
        };
    </script>

    <?php wp_head(); ?>

</head>


<body <?php body_class(); ?>>

<div id="loader" style="display: none; position: fixed; z-index: 1000; top: 0; left: 0; height: 100%; width: 100%; background: rgba(255, 255, 255, 0.8);">
    <img src="<?php echo get_template_directory_uri().'/images/loader.gif'; ?>" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
</div>
<?php wp_body_open(); 
get_template_part('side-menu', 'none'); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'askan' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="header-content">
			<div class="toggle-icon">
				<i class="fa-solid fa-ellipsis-vertical"></i>
			</div>
			<a href="<?php echo get_site_url(); ?>">
			<?php $logo_img = basename(get_page_template()) == 'setting-page.php' ? '/images/logo.svg' : '/images/logo.svg'; ?>
				<img class="logo" src="<?php echo get_stylesheet_directory_uri(  ).$logo_img; ?>" alt="logo">
			</a>		
			<button class="search"><i class="fa fa-search" aria-hidden="true"></i></button>
		</div>
		<div class="bottom-head">
			<!-- <ul>
				<li>כותרות היום</li> |
				<?php
				foreach (get_category_for_menu() as $key => $cat) {  ?>
					<li><a href="<?php  echo  get_term_link($cat);?>"><?php echo $cat->name; ?></a></li> |
				<?php
				}
				?>
			</ul> -->
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				)
			);
			?>
		</div>
		<!-- <nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'askan' ); ?></button>
			<?php
	
			?>
		</nav> -->
	</header>
<div id="searchBox" style="display:none;">
    <button id="closeSearch" class="close-search">X</button>
    <form action="<?php echo home_url('/'); ?>" method="get">
        <input type="text" name="s" placeholder="Search..." required>
        <button type="submit">Search</button>
    </form>
</div>


