<?php
/**
 * wishee functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wishee
 */

if ( ! function_exists( 'wishee_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wishee_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on wishee, use a find and replace
	 * to change 'wishee' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'wishee', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'wishee' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'wishee_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'wishee_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wishee_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wishee_content_width', 640 );
}
add_action( 'after_setup_theme', 'wishee_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wishee_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'wishee' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'wishee' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'wishee_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wishee_scripts() {
	wp_enqueue_style( 'wishee-style', get_stylesheet_uri() );

	wp_enqueue_script( 'wishee-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
	
	wp_enqueue_script( 'jquery-match-height', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.6.0/jquery.matchHeight-min.js', array('jquery'), false, true );

	wp_enqueue_script( 'wishee-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wishee_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

//A shortcut function for get_template_directory_uri()
function theme_url($return = false) {
	if($return) return get_template_directory_uri();
	echo get_template_directory_uri();
}

//Accepts a Custom Field image name. Returns an <img> tag with the image src and alt text
function get_image_with_alt($imagefield, $postID = null, $imagesize = 'full', $class = null) {
	if($postID === null) $postID = get_the_id();
	$imageID = get_field($imagefield, $postID); 
	$image = wp_get_attachment_image_src( $imageID, $imagesize ); 
	$alt_text = get_post_meta($imageID , '_wp_attachment_image_alt', true);
	$class = $class != null ? 'class="' . $class . '"' : '';
	return '<img src="' . $image[0] . '" alt="' . $alt_text . '" ' . $class . ' />';
}

//Accepts a Custom Fields image object, image size (optional) and class (optional) and returns an image tag with an alt attribute. Usage:
//echo get_repeater_image_with_alt(get_sub_field('image'));
function get_repeater_image_with_alt($image, $size = 'full', $class = null) {
	$src = $size == 'full' ? $image['url'] : $image['sizes'][$size];
	$class = $class != null ? 'class="' . $class . '"' : '';
	return '<img src="' . $src . '" alt="' . $image["alt"] . '" ' . $class . ' />';
}

//Adds a html5 shim in the header for IE<9
function add_ie_html5_shim () {
	echo '<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
}
add_action('wp_head', 'add_ie_html5_shim');

//remove_action( 'wp_head', 'feed_links', 2 ); // Removes the header links to the post and comment feeds to fix the broken links if there are no posts

//Renames the admin menu labels
add_filter('gettext', 'rename_admin_menu_items');
add_filter('ngettext', 'rename_admin_menu_items');
function rename_admin_menu_items( $menu ) {
	$menu = str_ireplace( 'WooCommerce', 'Shop', $menu );
	$menu = str_ireplace( 'Cyclone Slider', 'Home Page Slider', $menu );
	return $menu;
}

//Adds a new link to the admin sidebar called "Menu" that points to the "Appearance>Menus" page.
//The "20.22" sets the position in the sidebar. Be wary that this may overwrite plugin admin menu links. More details here: http://codex.wordpress.org/Function_Reference/add_menu_page
add_action( 'admin_menu', 'register_custom_menu_admin_link' );
function register_custom_menu_admin_link() {
	add_menu_page( 'Menu Navigation', 'Menu', 'manage_options', 'nav-menus.php', '', 'dashicons-welcome-widgets-menus', '20.22' );
}

//Adds the cookieBox script. Add the "cookieBox.js" file to the "js" folder. Please note, the cookie css is now included in style.css
function enqueue_cookie_scripts() {
	wp_enqueue_script( 'cookie-js', get_stylesheet_directory_uri() . '/js/cookieBox.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_cookie_scripts');

//Sets the cookieBox options
function set_cookieBox_options() {
	if ( wp_script_is( 'cookie-js', 'enqueued' ) ) {
		?>
			<script type="text/javascript">
				//CookieBox options
				var message = "This website uses cookies. If you do not wish to accept them, please navigate away from this website. You can read more about them <a href='<?php echo site_url(); ?>/privacy-policy#cookies'>here</a>.",
					buttonText = "ok";
			</script>
		<?php
	}
}
add_action( 'wp_head', 'set_cookieBox_options' );


if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Header Settings',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'theme-general-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));
	}

