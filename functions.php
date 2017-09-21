<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

function my_et_builder_post_types( $post_types ) {
    $post_types[] = 'cuar_private_file';
    $post_types[] = 'cuar_private_page';
    $post_types[] = 'private-page';
     
    return $post_types;
}
add_filter( 'et_builder_post_types', 'my_et_builder_post_types' );

// Shortcode para exibir o usuário logado

function show_loggedin_function( $atts ) {
    
    global $current_user, $user_login;
          get_currentuserinfo();
    add_filter('widget_text', 'do_shortcode');
    if ($user_login) 
    return 'Welcome ' . $current_user->display_name . '!';
    else
    return '<a href="' . wp_login_url() . ' ">Login</a>';
    
    }
    add_shortcode( 'show_loggedin_as', 'show_loggedin_function' );