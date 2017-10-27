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

// Shortcode para exibir informações do usuário logado
function show_loggedin_function( $atts ) {
    
    global $current_user, $user_login;
    wp_get_current_user();

    $user_info = get_userdata($current_user->ID);
    if($current_user->user_firstname) {
        $nome_usuario = $current_user->user_firstname;
    } else {
        $nome_usuario = $current_user->user_login;
    }

    add_filter('widget_text', 'do_shortcode');
    if ($user_login) 
        /* Função retornava: email e user role
        return '<h4>' . $current_user->display_name . '</h4><p>' . $current_user->user_email . ' <span style="text-transform:uppercase; font-style:oblique;">(' . implode(', ', $user_info->roles) . ')</span></p>';
        */
        return '<h4>' . $nome_usuario . '</h4><p>' . $current_user->empresa . ' <a href="' . wp_logout_url( ) . '"><span id="botao-logout"><i class="fa fa-sign-out" aria-hidden="true" style="font-family:FontAwesome;"></i> Sair</span></a>';
    else
        return '<a href="' . wp_login_url() . ' ">Login</a>';
    
    }
add_shortcode( 'show_loggedin_as', 'show_loggedin_function' );

// Shortcode para exibir avatar do usuário logado
function shortcode_user_avatar() {
    if(is_user_logged_in()) { // check if user is logged in
        global $current_user; // get current user's information
        get_currentuserinfo();
        return get_avatar( $current_user -> ID, 60 ); // display the logged-in user's avatar
    }
    else {
      // if not logged in, show default avatar. change URL to show your own default avatar
        return get_avatar( 'http://1.gravatar.com/avatar/ad524503a11cd5ca435acc9bb6523536?s=64', 60 );
    }
}
add_shortcode('display-user-avatar','shortcode_user_avatar');


// Redireciona o usuário para página de login ao acessar a area do cliente
add_action( 'template_redirect', 'redirect_to_specific_page' );

function redirect_to_specific_page() {
    if ( is_page('area-do-cliente-3') && !is_user_logged_in() && !is_admin() ) {
    auth_redirect();
    }
}

// Shortcode para exibir link para pasta do usuário logado (File Away)
function linkto_userfolder( $atts ) {
    
    global $current_user, $user_login;
    wp_get_current_user();

    $user_info = get_userdata($current_user->ID);
    $nome_usuario = $current_user->user_login;   

    add_filter('widget_text', 'do_shortcode');
    if ($user_login) {
        return '<a href="?drawer1=area-do-cliente*' . implode(', ', $user_info->roles) . '*' . $nome_usuario . '" class="btn-irarquivos"><i class="fa fa-folder-open-o" aria-hidden="true"></i> Meus Arquivos</a>';
    }
}
add_shortcode( 'show_linkto_userfolder', 'linkto_userfolder' );

// Cria pasta própria para cada novo usuário assim que ele faz login
add_action( 'wp_login', 'create_user_dir', 10, 2);

function create_user_dir($user_login, $user) {
    $user_info = get_userdata($user->ID);
    $nome_usuario = $user_login;
    $funcao_usuario = implode(', ', $user_info->roles);
    $upload_dir = wp_upload_dir();
    $user_dir = $upload_dir['basedir'] . '/area-do-cliente/' . $funcao_usuario . '/' . $nome_usuario;

    if(!file_exists($user_dir)) wp_mkdir_p($user_dir);
}


/* DESATIVADO: Cria pasta própria para cada novo usuário assim que ele é registrado
add_action( 'user_register', 'create_user_dir', 10, 1);

function create_user_dir($user_id) {
    $user_info = get_userdata($user_id);
    $nome_usuario = $user_info->user_login;
    $funcao_usuario = implode(', ', $user_info->roles);
    $upload_dir = wp_upload_dir();
    $user_dir = $upload_dir['basedir'] . '/area-do-cliente/' . $funcao_usuario . '/' . $nome_usuario;

    if(!file_exists($user_dir)) wp_mkdir_p($user_dir);
} */

