<?php 
date_default_timezone_set('Europe/Prague');

function register_my_session(){
  if( !session_id() ){
  	session_id($_COOKIE['PHPSESSID']);
    session_start();
  }
}

add_action('init', 'register_my_session');

function theme_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_script( 'jquery');
    //wp_enqueue_script( 'jquery-ui-core');
    //wp_enqueue_script( 'jquery-ui-tooltip');
    wp_enqueue_script( 'scripts', get_template_directory_uri() . '/js/scripts.js');
    wp_localize_script( 'scripts', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}

add_action( 'wp_enqueue_scripts', 'theme_scripts' );

//LOAD THEME TEXT DOMAIN//

add_action( 'after_setup_theme', 'setup' );

function setup() {
	load_theme_textdomain('dogcitylife', get_template_directory_uri() .'/languages');
}

//add_image_size( 'slider_detail', 712, 475, true );


//REMOVE EMOJIS
//remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
//remove_action( 'wp_print_styles', 'print_emoji_styles' );

add_theme_support('post-thumbnails');
add_image_size( "listing", $width = 351, 238, true );
add_image_size( "single", $width = 1168, 394, true );

//MENUS//
function theme_menus() {
  register_nav_menus(
    array(
      'main-menu' => __( 'Main Menu' ),
    )
  );
}
add_action( 'init', 'theme_menus' );

//SIDEBARS WIDGETS//
function theme_widgets_init() {

	register_sidebar(
	 array(
		'name' => 'Banner desktop cz',
		'id' => 'banner_desktop_cz',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );

	register_sidebar(
	 array(
		'name' => 'Banner desktop en',
		'id' => 'banner_desktop_en',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );

	register_sidebar(
	 array(
		'name' => 'Banner mobile cz',
		'id' => 'banner_mobile_cz',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );

	register_sidebar(
	 array(
		'name' => 'Banner mobile en',
		'id' => 'banner_mobile_en',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );

	
}
add_action( 'widgets_init', 'theme_widgets_init' );

function theme_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'aukce' ), max( $paged, $page ) );
	
	return $title;
}
add_filter( 'wp_title', 'theme_title', 10, 2 );

function get_excerpt($limit, $source = null){

    if($source == "content" ? ($excerpt = get_the_content()) : ($excerpt = get_the_excerpt()));
    $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $excerpt = substr($excerpt, 0, $limit);
    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
    $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
    $excerpt = $excerpt.'...';
    return $excerpt;
}

add_filter( 'avatar_defaults', 'wpb_new_gravatar' );
function wpb_new_gravatar ($avatar_defaults) {
	$myavatar = get_bloginfo('template_directory') . "/img/gravatar.png";
	$avatar_defaults[$myavatar] = "Default Gravatar";
	return $avatar_defaults;
}

?>