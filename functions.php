<?php
/**
 * Passion functions and definitions
 *
 * @package Passion
 * @since Passion 1.0
 */

require( get_template_directory() . '/inc/customizer.php' ); // new customizer options


/* Include plugin activation file to install plugins */
include get_template_directory() . '/inc/plugin-activation/plugin-details.php';



/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Passion 1.0
 *
 * @return void
 */
if ( ! function_exists( 'passion_setup' ) ) {
	function passion_setup() {
		global $content_width;
                
                /**
                * Set the content width based on the theme's design and stylesheet.
                *
                * @since Passion 1.0
                */
               if ( ! isset( $content_width ) )
                       $content_width = 727; /* Default the embedded content width to 790px */


		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on Passion, use a find and replace
		 * to change 'passion' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'passion', trailingslashit( get_template_directory_uri() ) . 'languages' );

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );

		// Create an extra image size for the Post featured image
		add_image_size( 'post_feature_full_width', 300, 300, true );
                
                 add_image_size('post-thumb', 716, 400, true); // custom thumbnail for post  
		// Create an extra image size for the Post thumbnail image
		add_image_size( 'post_feature_thumb', 368, 243, true );
                
		// This theme uses wp_nav_menu() in one location
		register_nav_menus( array(
				'primary' => esc_html__( 'Primary Menu', 'passion' ),
                                'footer' => esc_html__( 'Footer Menu', 'passion' )
			) );

		// This theme supports a variety of post formats
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );

		// Enable support for Custom Backgrounds
		add_theme_support( 'custom-background', array(
				// Background color default
				'default-color' => 'eee',
				// Background image default
				'default-image' => ''
                                
			) );
               
	}
}
add_action( 'after_setup_theme', 'passion_setup' );



/**
 * Enqueue scripts and styles
 *
 * @since Passion 1.0
 *
 * @return void
 */
function passion_scripts_styles() {

	// Register and enqueue our icon font
	// We're using the awesome Font Awesome icon font. http://fortawesome.github.io/Font-Awesome
	wp_enqueue_style( 'fontawesome', trailingslashit( get_template_directory_uri() ) . 'assets/css/font-awesome.min.css' , array(), '4.0.3', 'all' );
        
        // Load animation stylesheet 
        wp_enqueue_style( 'passion-animate', trailingslashit( get_template_directory_uri() ) . 'assets/css/animate.min.css' , array(), '1.0', 'all' );
        
        if (class_exists('woocommerce')) {
            wp_enqueue_style( 'passion-woocommerce', trailingslashit( get_template_directory_uri() ) . 'assets/css/passion-woocommerce.css' , array(), '1.0', 'all' );
        }
      
        
	$fonts_url = 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,700|Roboto+Slab:400,700';
	if ( !empty( $fonts_url ) ) {
		wp_enqueue_style( 'passion-fonts', esc_url_raw( $fonts_url ), array(), null );
	}

	// Enqueue the default WordPress stylesheet
	wp_enqueue_style( 'style', get_stylesheet_uri(), array(), '1.0', 'all' );
       

	/**
	 * Register and enqueue our scripts
	 */

	// Load Modernizr at the top of the document, which enables HTML5 elements and feature detects
	wp_enqueue_script( 'modernizr', trailingslashit( get_template_directory_uri() ) . 'assets/js/modernizr-2.7.1-min.js', array(), '2.7.1', false );
        wp_enqueue_script('jquery'); 
        wp_enqueue_script('passion-slider', get_template_directory_uri() . '/assets/js/jquery.flexslider-min.js', array('jquery'));
	wp_enqueue_script( 'passion-slicknav', get_template_directory_uri() . '/assets/js/jquery.slicknav.min.js' );
        wp_enqueue_script('mixitup', get_template_directory_uri() . '/assets/js/jquery.mixitup.js', array('jquery'));
        wp_enqueue_script('passion-custom-scripts', get_template_directory_uri() . '/assets/js/custom-scripts.js', array(), '1.0', 'all', false);
     
	// Adds JavaScript to pages with the comment form to support sites with threaded comments (when in use)
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'passion_scripts_styles' );



/**
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @since Passion 1.2.5
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string The filtered CSS paths list.
 */
function passion_mce_css( $mce_css ) {
	$fonts_url = 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,700|Montserrat:400,700';

	if ( empty( $fonts_url ) ) {
		return $mce_css;
	}

	if ( !empty( $mce_css ) ) {
		$mce_css .= ',';
	}

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $fonts_url ) );

	return $mce_css;
}
add_filter( 'mce_css', 'passion_mce_css' );


/**
 * Register widgetized areas
 *
 * @since Passion 1.0
 *
 * @return void
 */
function passion_widgets_init() {
	register_sidebar( array(
			'name' => esc_html__( 'Main Sidebar', 'passion' ),
			'id' => 'sidebar-main',
			'description' => esc_html__( 'Appears in the sidebar on posts and pages except the optional Front Page template, which has its own widgets', 'passion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		) );

	register_sidebar( array(
			'name' => esc_html__( 'Footer #1', 'passion' ),
			'id' => 'sidebar-footer1',
			'description' => esc_html__( 'Appears in the footer sidebar', 'passion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		) );

	register_sidebar( array(
			'name' => esc_html__( 'Footer #2', 'passion' ),
			'id' => 'sidebar-footer2',
			'description' => esc_html__( 'Appears in the footer sidebar', 'passion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		) );

	register_sidebar( array(
			'name' => esc_html__( 'Footer #3', 'passion' ),
			'id' => 'sidebar-footer3',
			'description' => esc_html__( 'Appears in the footer sidebar', 'passion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		) );

	register_sidebar( array(
			'name' => esc_html__( 'Footer #4', 'passion' ),
			'id' => 'sidebar-footer4',
			'description' => esc_html__( 'Appears in the footer sidebar', 'passion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		) );
}
add_action( 'widgets_init', 'passion_widgets_init' );


/**
 * Adjusts content_width value for full-width templates and attachments
 *
 * @since Passion 1.0
 *
 * @return void
 */
function passion_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() ) {
		global $content_width;
		$content_width = 1100;
	}
}
add_action( 'template_redirect', 'passion_content_width' );


/**
 * Change the "read more..." link so it links to the top of the page rather than part way down
 *
 * @since Passion 1.0
 *
 * @param string The 'Read more' link
 * @return string The link to the post url without the more tag appended on the end
 */
function passion_remove_more_jump_link( $link ) {
	$offset = strpos( $link, '#more-' );
	if ( $offset ) {
		$end = strpos( $link, '"', $offset );
	}
	if ( $end ) {
		$link = substr_replace( $link, '', $offset, $end-$offset );
	}
	return $link;
}
add_filter( 'the_content_more_link', 'passion_remove_more_jump_link' );


/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Passion 1.0
 *
 * @return string The 'Read more' link
 */
function passion_continue_reading_link() {
	return '&hellip;<p><a class="more-link" href="'. esc_url( get_permalink() ) . '" title="' . esc_html__( 'Read more', 'passion' ) . ' &lsquo;' . esc_attr(get_the_title()) . '&rsquo;">' . wp_kses( __( 'Read more ', 'passion' ), array( 'span' => array( 
			'class' => array() ) ) ) . '</a></p>';
}


/**
 * Replaces "[...]" (appended to automatically generated excerpts) with the passion_continue_reading_link().
 *
 * @since Passion 1.0
 *
 * @param string Auto generated excerpt
 * @return string The filtered excerpt
 */
function passion_auto_excerpt_more( $more ) {
	return passion_continue_reading_link();
}
add_filter( 'excerpt_more', 'passion_auto_excerpt_more' );



/**
 * Add Filter to allow Shortcodes to work in the Sidebar
 *
 * @since Passion 1.0
 */
add_filter( 'widget_text', 'do_shortcode' );

/** 
 * Additional settings for Easy Digital Downloads
 * 
 * @since Passion 1.0
 */


/**
 * Recreate the default filters on the_content
 * This will make it much easier to output the Theme Options Editor content with proper/expected formatting.
 * We don't include an add_filter for 'prepend_attachment' as it causes an image to appear in the content, on attachment pages.
 * Also, since the Theme Options editor doesn't allow you to add images anyway, no big deal.
 *
 * @since Passion 1.0
 */
add_filter( 'meta_content', 'wptexturize' );
add_filter( 'meta_content', 'convert_smilies' );
add_filter( 'meta_content', 'convert_chars'  );
add_filter( 'meta_content', 'wpautop' );
add_filter( 'meta_content', 'shortcode_unautop'  );


/*
 * Check if the front page is set 
 * to display latest blog posts
 * or a static front page
 * 
 * If it's set to display blog posts
 * then ignore the front-page.php 
 * template and head over to index.php
 * 
 *  
 * @since Passion 1.0
 */


function passion_filter_front_page_template( $template ) {
     return is_home() ? '' : $template ;
}
add_filter( 'frontpage_template', 'passion_filter_front_page_template' );


function passion_custom_favicon(){ 
    if(get_theme_mod('custom_favicon')) { ?>
    <link rel="shortcut icon" href="<?php echo get_theme_mod('custom_favicon'); ?> " />
    <?php }
}
add_action('wp_head','passion_custom_favicon');


/* Add theme extras */
require( get_template_directory() . '/inc/theme-extras.php' );

/**
 * Add support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );

function comment_reform ($arg) {
$arg['title_reply'] = __('<span>Leave a Reply</span>');
return $arg;
}
add_filter('comment_form_defaults','comment_reform');