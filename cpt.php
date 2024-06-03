<?php
register_taxonomy('topic', array('post'), array(
    'label' => 'נושאים',
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_admin_column' => true,
    'hierarchical' => true,
    //'meta_box_cb' => true
));