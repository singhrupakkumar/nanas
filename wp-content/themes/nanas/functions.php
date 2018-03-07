<?php
/**
 * Twenty Sixteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/**
 * Twenty Sixteen only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentysixteen_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own twentysixteen_setup() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentysixteen
	 * If you're building a theme based on Twenty Sixteen, use a find and replace
	 * to change 'twentysixteen' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'twentysixteen' );

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
	 * Enable support for custom logo.
	 *
	 *  @since Twenty Sixteen 1.2
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
	) );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'twentysixteen' ),
		'social'  => __( 'Social Links Menu', 'twentysixteen' ),
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

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', twentysixteen_fonts_url() ) );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // twentysixteen_setup
add_action( 'after_setup_theme', 'twentysixteen_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'twentysixteen_content_width', 840 );
}
add_action( 'after_setup_theme', 'twentysixteen_content_width', 0 );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'twentysixteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 1', 'twentysixteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 2', 'twentysixteen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentysixteen_widgets_init' );

if ( ! function_exists( 'twentysixteen_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Sixteen.
 *
 * Create your own twentysixteen_fonts_url() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function twentysixteen_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
	}

	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Montserrat:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Inconsolata:400';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentysixteen_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentysixteen-fonts', twentysixteen_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

	// Theme stylesheet.
	wp_enqueue_style( 'twentysixteen-style', get_stylesheet_uri() );
	
	wp_enqueue_style( 'twentysixteen-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
	wp_enqueue_style( 'twentysixteen-bootstrap-theme', get_template_directory_uri() . '/css/bootstrap-theme.min.css');
	wp_enqueue_style( 'twentysixteen-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
	wp_enqueue_style( 'twentysixteen-bxslider', get_template_directory_uri() . '/css/jquery.bxslider.min.css');
	wp_enqueue_style( 'twentysixteen-stylemy', get_template_directory_uri() . '/css/style.css');
	wp_enqueue_style( 'twentysixteen-nav', get_template_directory_uri() . '/css/scrolling-nav.css');
	wp_enqueue_style( 'twentysixteen-slick', get_template_directory_uri() . '/css/slick.css');
	wp_enqueue_style( 'twentysixteen-slick-theme', get_template_directory_uri() . '/css/slick-theme.css');

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie', 'conditional', 'lt IE 10' );

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie7', 'conditional', 'lt IE 8' );

	// Load the html5 shiv.
	wp_enqueue_script( 'twentysixteen-jquery.min', get_template_directory_uri() . '/js/jquery.min.js', array( 'jquery' ), '20160412', true );
	wp_enqueue_script( 'twentysixteen-bootstrap.min', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '20160412', true );
	
	wp_enqueue_script( 'twentysixteen-bxslider.min', get_template_directory_uri() . '/js/jquery.bxslider.min.js', array( 'jquery' ), '20160412', true );
	
	wp_enqueue_script( 'twentysixteen-custom.min', get_template_directory_uri() . '/js/custom.js', array( 'jquery' ), '20160412', true );
	
	wp_enqueue_script( 'twentysixteen-easing.min', get_template_directory_uri() . '/js/jquery.easing.min.js', array( 'jquery' ), '20160412', true );
	
	wp_enqueue_script( 'twentysixteen-scrolling', get_template_directory_uri() . '/js/scrolling-nav.js', array( 'jquery' ), '20160412', true );
	
	wp_enqueue_script( 'twentysixteen-slick.min', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), '20160412', true );
	
	wp_enqueue_script( 'twentysixteen-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'twentysixteen-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'twentysixteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160816', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentysixteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160816' );
	}

	wp_enqueue_script( 'twentysixteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20160816', true );

	wp_localize_script( 'twentysixteen-script', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'twentysixteen' ),
		'collapse' => __( 'collapse child menu', 'twentysixteen' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'twentysixteen_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function twentysixteen_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'twentysixteen_body_classes' );

/**
 * Converts a HEX value to RGB.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function twentysixteen_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentysixteen_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentysixteen_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function twentysixteen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentysixteen_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Twenty Sixteen 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function twentysixteen_widget_tag_cloud_args( $args ) {
	$args['largest'] = 1;
	$args['smallest'] = 1;
	$args['unit'] = 'em';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentysixteen_widget_tag_cloud_args' );




//***************************theme option*******************

 $themename = "Theme Options";
$shortname = "nt";

$categories = get_categories('hide_empty=0&orderby=name');
$wp_cats = array();
foreach ($categories as $category_list ) {
       $wp_cats[$category_list->cat_ID] = $category_list->cat_name;
}
array_unshift($wp_cats, "Choose a category"); 

$options = array (
 
array( "name" => $themename." Options",
	"type" => "title"),
 

array( "name" => "General",
	"type" => "section"),
array( "type" => "open"),

array( "name" => "Logo URL",
	"desc" => "Enter the link to your logo image",
	"id" => $shortname."_logo",
	"type" => "text",
	"std" => ""),

array( "name" => "Footer Logo URL",
	"desc" => "Enter the link to your footer logo image",
	"id" => $shortname."_footerlogo",
	"type" => "text",
	"std" => ""),	
	
	array( "name" => "Phone",
	"desc" => "Enter the phone number",
	"id" => $shortname."_phone",
	"type" => "text",
	"std" => ""),
	
	array( "name" => "Email",
	"desc" => "Enter the email Id",
	"id" => $shortname."_email",
	"type" => "text",
	"std" => ""),
	
	array( "name" => "Shop Address",
	"desc" => "Enter the address",
	"id" => $shortname."_address",
	"type" => "textarea",
	"std" => ""),

	array( "name" => "Opening Hours:",
	"desc" => "Enter the Shop Opening Hours:",
	"id" => $shortname."_opening",
	"type" => "text",
	"std" => ""),
	
array( "name" => "Custom CSS",
	"desc" => "Want to add any custom CSS code? Put in here, and the rest is taken care of. This overrides any other stylesheets. eg: a.button{color:green}",
	"id" => $shortname."_custom_css",
	"type" => "textarea",
	"std" => ""),		
	
array( "type" => "close"),
array( "name" => "Social Icons",
	"type" => "section"),
array( "type" => "open"),

array( "name" => "Facebook URL",
	"desc" => "Enter the link to your facebook fan page.",
	"id" => $shortname."_facebook_social_link",
	"type" => "text",
	"std" => ""),

	
array( "name" => "Twitter URL",
	"desc" => "Enter the link to your twitter.",
	"id" => $shortname."_twitter_social_link",
	"type" => "text",
	"std" => ""),

	
array( "name" => "Linkedin URL",
	"desc" => "Enter the link to your Linkedin.",
	"id" => $shortname."_linkedin_social_link",
	"type" => "text",
	"std" => ""),
	array( "name" => "Google plus URL",
	"desc" => "Enter the link to your Google plus.",
	"id" => $shortname."_googleplus_social_link",
	"type" => "text",
	"std" => ""),
	array( "name" => "Dribbble URL",
	"desc" => "Enter the link to your dribbble.",
	"id" => $shortname."_dribbble_social_link",
	"type" => "text",
	"std" => ""),
array( "type" => "close"),
array( "name" => "Footer",
	"type" => "section"),
array( "type" => "open"),
	
array( "name" => "Footer copyright text",
	"desc" => "Enter text used in the right side of the footer. It can be HTML",
	"id" => $shortname."_footer_text",
	"type" => "text",
	"std" => ""),
	
array( "name" => "Google Analytics Code",
	"desc" => "You can paste your Google Analytics or other tracking code in this box. This will be automatically added to the footer.",
	"id" => $shortname."_ga_code",
	"type" => "textarea",
	"std" => ""),	
	
array( "name" => "Custom Favicon",
	"desc" => "A favicon is a 16x16 pixel icon that represents your site; paste the URL to a .ico image that you want to use as the image",
	"id" => $shortname."_favicon",
	"type" => "text",
	"std" => get_bloginfo('url') ."/favicon.ico"),	
	
array( "name" => "Feedburner URL",
	"desc" => "Feedburner is a Google service that takes care of your RSS feed. Paste your Feedburner URL here to let readers see it in your website",
	"id" => $shortname."_feedburner",
	"type" => "text",
	"std" => get_bloginfo('rss2_url')),

 
array( "type" => "close")
 
);


function mytheme_add_admin() {
 
global $themename, $shortname, $options;
 
if ( $_GET['page'] == basename(__FILE__) ) {
 
	if ( 'save' == $_REQUEST['action'] ) {
 
		foreach ($options as $value) {
		update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }
 
foreach ($options as $value) {
	if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }
 
	header("Location: admin.php?page=functions.php&saved=true");
die;
 
} 
else if( 'reset' == $_REQUEST['action'] ) {
 
	foreach ($options as $value) {
		delete_option( $value['id'] ); }
 
	header("Location: admin.php?page=functions.php&reset=true");
die;
 
}
}
 
add_menu_page($themename, $themename, 'administrator', basename(__FILE__), 'mytheme_admin');
}

function mytheme_add_init() {

$file_dir=get_bloginfo('template_directory');
wp_enqueue_style("functions", $file_dir."/functions/functions.css", false, "1.0", "all");
wp_enqueue_script("rm_script", $file_dir."/functions/rm_script.js", false, "1.0");

}
function mytheme_admin() {
 
global $themename, $shortname, $options;
$i=0;
 
if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
 
?>
<div class="wrap rm_wrap">
<h2><?php echo $themename; ?> Settings</h2>
 
<div class="rm_opts">
<form method="post">
<?php foreach ($options as $value) {
switch ( $value['type'] ) {
 
case "open":
?>
 
<?php break;
 
case "close":
?>
 
</div>
</div>
<br />

 
<?php break;
 
case "title":
?>
<p>To easily use the <?php echo $themename;?> theme, you can use the menu below.</p>

 
<?php break;
 
case 'text':
?>

<div class="rm_input rm_text">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'])  ); } else { echo $value['std']; } ?>" />
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 
 </div>
<?php
break;
 
case 'textarea':
?>

<div class="rm_input rm_textarea">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id']) ); } else { echo $value['std']; } ?></textarea>
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 
 </div>
  
<?php
break;
 
case 'select':
?>

<div class="rm_input rm_select">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	
<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
<?php foreach ($value['options'] as $option) { ?>
		<option <?php if (get_settings( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
</select>

	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
</div>
<?php
break;
 
case "checkbox":
?>

<div class="rm_input rm_checkbox">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	
<?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />


	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 </div>
<?php break; 
case "section":

$i++;

?>

<div class="rm_section">
<div class="rm_title"><h3><img src="<?php bloginfo('template_directory')?>/functions/images/trans.gif" class="inactive" alt=""><?php echo $value['name']; ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" class="savebtn" value="Save changes" />
</span><div class="clearfix"></div></div>
<div class="rm_options">

 
<?php break;
 
}
}
?>
 
<input type="hidden" name="action" value="save" />
</form>
<form method="post">
<p class="submit">
<input name="reset" class="savebtn" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>
<div style="font-size:9px; margin-bottom:10px;">Icons: <a href="http://www.woothemes.com/2009/09/woofunction/">WooFunction</a></div>
 </div> 
 

<?php
}
?>
<?php
add_action('admin_init', 'mytheme_add_init');
add_action('admin_menu', 'mytheme_add_admin');
//_________theme option_---------------------


	/////////////custom post Testimonial/////////////////
	
add_action( 'init', 'register_cpt_testimonial' );
function register_cpt_testimonial() {
$labels = array( 
            'name' => _x( 'Testimonial', 'testimonial' ),
            'singular_name' => _x( 'Testimonial', 'testimonial' ),
            'add_new' => _x( 'Add New', 'testimonial' ),
            'add_new_item' => _x( 'Add New Testimonial', 'testimonial' ),
            'edit_item' => _x( 'Edit Testimonial', 'testimonial' ),
            'new_item' => _x( 'New Testimonial', 'testimonial' ),
            'view_item' => _x( 'View Testimonial', 'testimonial' ),
            'search_items' => _x( 'Search Testimonial', 'testimonial' ),
            'not_found' => _x( 'No Testimonial found', 'testimonial' ),
            'not_found_in_trash' => _x( 'No Testimonial found in Trash', 'testimonial' ),
            'parent_item_colon' => _x( 'Parent testimonial:', 'testimonial' ),
            'menu_name' => _x( 'Testimonial', 'testimonial' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => true,
            
            'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'trackbacks', 'custom-fields', 'page-attributes' ),
          //  'taxonomies' => array( 'category', 'post_tag', 'page-category' ),
            'public' => true,
            'show_ui' => true,
			'menu_icon'   => 'dashicons-testimonial',
            'show_in_menu' => true,
            
            
            'show_in_nav_menus' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => true,
            'capability_type' => 'post'
        );

        register_post_type( 'testimonial', $args );
		create_my_taxonomies3();
		
    }

function create_my_taxonomies3() {
    register_taxonomy(
        'testimonial_taxo',
        'testimonial',
        array(
            'labels' => array(
                'name' => 'Testimonial category',
                'add_new_item' => 'Add New Category',
                'new_item_name' => "New Category"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}

	/////////////custom post menu/////////////////
	
add_action( 'init', 'register_cpt_menu' );
function register_cpt_menu() {
$labels = array( 
            'name' => _x( 'Menu', 'menu' ),
            'singular_name' => _x( 'Menu', 'menu' ),
            'add_new' => _x( 'Add New Menu', 'menu' ),
            'add_new_item' => _x( 'Add New Menu', 'menu' ),
            'edit_item' => _x( 'Edit Menu', 'menu' ),
            'new_item' => _x( 'New Menu', 'menu' ),
            'view_item' => _x( 'View Menu', 'menu' ),
            'search_items' => _x( 'Search Menu', 'menu' ),
            'not_found' => _x( 'No Menu found', 'menu' ),
            'not_found_in_trash' => _x( 'No Menu found in Trash', 'menu' ),
            'parent_item_colon' => _x( 'Parent menu:', 'menu' ),
            'menu_name' => _x( 'Menu', 'testimonial' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => true,
            
            'supports' => array( 'title', 'editor', 'excerpt', 'comments','thumbnail','gallery','trackbacks', 'custom-fields', 'page-attributes' ),
          //  'taxonomies' => array( 'category', 'post_tag', 'page-category' ),
            'public' => true,
            'show_ui' => true,
			'menu_icon'   => 'dashicons-menu',
            'show_in_menu' => true,
            
            
            'show_in_nav_menus' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => true,
            'has_archive' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => true,
            'capability_type' => 'post'
        );

        register_post_type( 'menu', $args );
		create_my_taxonomies4();
		
    }

function create_my_taxonomies4() {
    register_taxonomy(
        'menu_taxo',
        'menu',
        array(
            'labels' => array(
                'name' => 'Menu Category',
                'add_new_item' => 'Add New Category',
                'new_item_name' => "New Category"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}


//Register Meta Box
function rm_register_meta_box() {
    add_meta_box( 'rm-meta-box-id', esc_html__( 'Menu Price', 'text-domain' ), 'rm_meta_box_callback', 'menu', 'advanced', 'high' );
	   add_meta_box( 'rm-meta-box-id', esc_html__( 'Author Name', 'text-domain' ), 'rm_meta_box_callback1', 'testimonial', 'advanced', 'high' );
}
add_action( 'add_meta_boxes', 'rm_register_meta_box');



function rm_meta_box_callback1( $meta_id ) {
 
    $outline = '<label for="author" style="width:150px; display:inline-block;">'. esc_html__('Name', 'text-domain') .'</label>';
    $title_field = get_post_meta( $meta_id->ID, 'testimonial_author', true );
    $outline .= '<input type="text" name="testimonial_author" id="testimonial_author" class="title_field" value="'. esc_attr($title_field) .'" style="width:300px;"/>';
 
    echo $outline;
}
 
//Add field
function rm_meta_box_callback( $meta_id ) {
 
    $outline = '<label for="price" style="width:150px; display:inline-block;">'. esc_html__('Price', 'text-domain') .'</label>';
    $title_field = get_post_meta( $meta_id->ID, 'menu_price', true );
    $outline .= '<input type="text" name="menu_price" id="menu_price" class="title_field" value="'. esc_attr($title_field) .'" style="width:300px;"/>';
 
    echo $outline;
}

add_action( 'save_post', 'testimonial_text_box_save' );
function testimonial_text_box_save( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    return;

    if ( 'menu' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
        return;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return;
    }
	if ( 'menu' != $_POST['post_type'] )
		return ;
    $testimonial_text = $_POST['menu_price'];
    update_post_meta( $post_id, 'menu_price', $testimonial_text );
}	
/*********************author name******************/
add_action( 'save_post', 'testimonial_text_box_save1' );
function testimonial_text_box_save1( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    return;

    if ( 'testimonial' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
        return;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return;
    }
	if ( 'testimonial' != $_POST['post_type'] )
		return ;
    $testimonial_text = $_POST['testimonial_author'];
    update_post_meta( $post_id, 'testimonial_author', $testimonial_text );
}

/********************show menu colum***************************/

add_image_size( 'admin-list-thumb', 100, 100, false );

// add featured thumbnail to admin post columns
function wpcs_add_thumbnail_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'featured_thumb' => 'Image',
        'title' => 'Title',
        'author' => 'Author',
        'price' => 'Price',
        'categories' => 'Categories',
        'tags' => 'Tags',
        'comments' => '<span class="vers"><div title="Comments" class="comment-grey-bubble"></div></span>',
        'date' => 'Date'
    );
    return $columns;
}

function wpcs_add_thumbnail_columns_data( $column, $post_id ) {
    switch ( $column ) {
    case 'featured_thumb':
        echo '<a href="' . get_edit_post_link() . '">';
        echo the_post_thumbnail( 'admin-list-thumb' );
        echo '</a>';
        break;
	 case 'price':
        echo "$" .get_post_meta($post_id,'menu_price',true);
        break;	
    }
}

if ( function_exists( 'add_theme_support' ) ) {
    add_filter( 'manage_menu_posts_columns' , 'wpcs_add_thumbnail_columns' );
    add_action( 'manage_menu_posts_custom_column' , 'wpcs_add_thumbnail_columns_data', 10, 2 );

}	


  
  
 ////////***************************Booking Form*********************************/////////// 
  function rtb_print_booking_form1( $args = array() ) {

	global $rtb_controller;

	// Only allow the form to be displayed once on a page
	if ( $rtb_controller->form_rendered === true ) {
		return;
	} else {
		$rtb_controller->form_rendered = true;
	}

	// Sanitize incoming arguments
	if ( isset( $args['location'] ) ) {
		$args['location'] = $rtb_controller->locations->get_location_term_id( $args['location'] );
	} else {
		$args['location'] = 0;
	}

	// Enqueue assets for the form
	rtb_enqueue_assets();

	// Allow themes and plugins to override the booking form's HTML output.
	$output = apply_filters( 'rtb_booking_form_html_pre', '' );
	if ( !empty( $output ) ) {
		return $output;
	}

	// Process a booking request
	if ( !empty( $_POST['action'] ) && $_POST['action'] == 'booking_request' ) {

		if ( get_class( $rtb_controller->request ) === 'stdClass' ) {
			require_once( RTB_PLUGIN_DIR . '/includes/Booking.class.php' );
			$rtb_controller->request = new rtbBooking();
		}

		$rtb_controller->request->insert_booking();
	}

	// Define the form's action parameter
	$booking_page = $rtb_controller->settings->get_setting( 'booking-page' );
	if ( !empty( $booking_page ) ) {
		$booking_page = get_permalink( $booking_page );
	}

	// Retrieve the form fields
	$fields = $rtb_controller->settings->get_booking_form_fields( $rtb_controller->request, $args );

	ob_start();

	?>

	<?php if ( $rtb_controller->request->request_inserted === true ) : ?>
	<div class="rtb-message">
		<p><?php echo $rtb_controller->settings->get_setting( 'success-message' ); ?></p>
	</div>
	<?php else : ?>
	<form method="POST" class="reservtn_frm" action="<?php echo esc_attr( $booking_page ); ?>">
		<input type="hidden" name="action" value="booking_request">

		<?php if ( !empty( $args['location'] ) ) : ?>
			<input type="hidden" name="rtb-location" value="<?php echo absint( $args['location'] ); ?>">
		<?php endif; ?>

		<?php do_action( 'rtb_booking_form_before_fields' ); ?>

		<?php foreach( $fields as $fieldset => $contents ) :
			$fieldset_classes = isset( $contents['callback_args']['classes'] ) ? $contents['callback_args']['classes'] : array();
			$legend_classes = isset( $contents['callback_args']['legend_classes'] ) ? $contents['callback_args']['legend_classes'] : array();
		?>
		<fieldset <?php echo rtb_print_element_class( $fieldset, $fieldset_classes ); ?>>

			<?php if ( !empty( $contents['legend'] ) ) : ?>
			<legend <?php echo rtb_print_element_class( '', $legend_classes ); ?>>
				<?php echo $contents['legend']; ?>
			</legend>
			<?php endif; ?>

			<?php
				foreach( $contents['fields'] as $slug => $field ) {

					$args = empty( $field['callback_args'] ) ? array() : $field['callback_args'];

					if ( !empty( $field['required'] ) ) {
						$args = array_merge( $args, array( 'required' => $field['required'] ) );
					}

					call_user_func( $field['callback'], $slug, $field['title'], $field['request_input'], $args );
				}
			?>
		</fieldset>
		<?php endforeach; ?>

		<?php do_action( 'rtb_booking_form_after_fields' ); ?>

		<?php
			$button = sprintf(
				' <div class="btn-sec"><div class="fndmre"><button type="submit">%s</button></div></div>',
				apply_filters( 'rtb_booking_form_submit_label', __( 'Book Now', 'restaurant-reservations' ) )
			);

			echo apply_filters( 'rtb_booking_form_submit_button', $button );
		?>


	</form>
	<?php endif; ?>


	<?php

	$output = ob_get_clean();

	$output = apply_filters( 'rtb_booking_form_html_post', $output );

	return $output;
}
add_shortcode('book_form','rtb_print_booking_form1');


	//////////////////Blog Search///////////////////////////
	

function title_filter( $where, &$wp_query )
{
    global $wpdb;
    if ( $search_term = $wp_query->get( 'search_post_title' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $search_term ) ) . '%\'';
    }
    return $where;
}

function get_my_suggestions() {

    $search_term = $_GET['term'];
    //$cat = $_GET['scat'];

    $args = array(
        'post_type' => 'post',
        'search_post_title' => $search_term,
        //'cat' => $cat,
        'post_status' => 'publish',
        'orderby'     => 'title', 
        'order'       => 'DESC'        
    );

    add_filter( 'posts_where', 'title_filter', 10, 2 );
    $wp_query = new WP_Query($args);
    remove_filter( 'posts_where', 'title_filter', 10, 2 ); 
		$i = 0;
		while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
		$ran++;
		$Id = get_the_ID();	
		$cat	= get_the_category($Id); 
		$content = substr( get_the_content(),0, 100);

		$content = apply_filters('the_content', $content);

		$content = str_replace(']]>', ']]>', $content); 	

		?>
		   <div class="blog-content">
          <div class="col-sm-3 <?php if($ran%2==0){ echo "pull-right"; } ?>">
            <div class="Blg_story"> <a href="JavaScript:Void(0);" class="thumbnail"> <img src="<?php echo get_the_post_thumbnail_url($Id); ?>" width="250" alt=""> </a> </div>
          </div>
          <div class="col-sm-9">
            <div class="blog_summery">
              <div class="posted"> <a href="JavaScript:Void(0);"> <i class="icon-calendar"></i> <?php echo get_the_date(); ?></a> | By <a href="JavaScript:Void(0);"><?php echo get_the_author($Id); ?></a> | In <?php foreach($cat as $catitem){ ?><a href="JavaScript:Void(0); <?php // echo get_category_link($catitem->term_id) ?>"> <?php echo $catitem->name; ?>. </a><?php } ?></div>
              <h1><?php the_title(); ?></h1>
              <div class="section-content menus_lst<?php echo $i; ?>"><?php
					echo $content;
					?>
				<span class="more readmore<?php echo $i; ?>" data-val="0" id="m<?php echo $i; ?>">Read More</span>	
				</div>
				<div class="section-content menus_lst1<?php echo $i; ?>" style="display:none;"><?php
					echo get_the_content();
					?>
				<span class="less readmore<?php echo $i; ?>" data-val="1" id="m<?php echo $i; ?>">Less</span>	
				</div>
				
				<script>
					jQuery(document).ready(function(){
						
					jQuery(".readmore<?php echo $i; ?>").click(function(e){
					   e.preventDefault();
					   var val= jQuery(this).attr("data-val");
					   if(val=="1"){
						jQuery(".menus_lst1<?php echo $i; ?>").hide(); 
						jQuery(".menus_lst<?php echo $i; ?>").show(500);
					   }else{
						jQuery(".menus_lst<?php echo $i; ?>").hide(500);
						jQuery(".menus_lst1<?php echo $i; ?>").show();    
					   }
					});	

				
					});
				</script>
             
            </div>
          </div>
        </div>


			
		<?php
$i++;
endwhile; 
wp_reset_postdata();
    
}
add_shortcode('blog','get_my_suggestions');

