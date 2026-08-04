<?php
/**
 * HKLA theme bootstrap.
 *
 * @package hkla
 */

define( 'HKLA_VERSION', '0.1.0' );
define( 'HKLA_DIR', get_template_directory() );
define( 'HKLA_URI', get_template_directory_uri() );

require HKLA_DIR . '/inc/post-types.php';
require HKLA_DIR . '/inc/acf.php';
require HKLA_DIR . '/inc/template-tags.php';
require HKLA_DIR . '/inc/admin.php';
require HKLA_DIR . '/inc/redirects.php';
require HKLA_DIR . '/inc/schema.php';
require HKLA_DIR . '/inc/contact-form.php';

/**
 * Theme supports.
 */
function hkla_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary', 'hkla' ),
		)
	);

	// Image sizes tuned to the layout. Hero is served via srcset from the original.
	add_image_size( 'hkla-hero', 2400, 0, false );
	add_image_size( 'hkla-wide', 1600, 0, false );
	add_image_size( 'hkla-card', 1200, 0, false );
	add_image_size( 'hkla-half', 900, 0, false );
	add_image_size( 'hkla-headshot', 800, 800, true );
}
add_action( 'after_setup_theme', 'hkla_setup' );

/**
 * Pages are field-driven. No free-form editor, no layout decisions.
 */
function hkla_lock_page_editor() {
	remove_post_type_support( 'page', 'editor' );
}
add_action( 'init', 'hkla_lock_page_editor', 20 );

/**
 * News (native posts) is the one block editor surface, restricted to text and image.
 */
function hkla_allowed_blocks( $allowed, $context ) {
	if ( isset( $context->post ) && 'post' === $context->post->post_type ) {
		return array(
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/list-item',
			'core/quote',
			'core/image',
		);
	}
	return $allowed;
}
add_filter( 'allowed_block_types_all', 'hkla_allowed_blocks', 10, 2 );

/**
 * Assets. One CSS file, one small vanilla JS file, no jQuery on the front end.
 */
function hkla_assets() {
	wp_enqueue_style( 'hkla-main', HKLA_URI . '/assets/css/main.css', array(), HKLA_VERSION );
	wp_enqueue_script( 'hkla-main', HKLA_URI . '/assets/js/main.js', array(), HKLA_VERSION, array( 'strategy' => 'defer' ) );
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'hkla_assets', 20 );

/**
 * Preload the brand webfont once the licensed WOFF2 files land in assets/fonts/.
 */
function hkla_preload_fonts() {
	$font = HKLA_DIR . '/assets/fonts/jl-jungka-regular.woff2';
	if ( file_exists( $font ) ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( HKLA_URI . '/assets/fonts/jl-jungka-regular.woff2' )
		);
	}
}
add_action( 'wp_head', 'hkla_preload_fonts', 2 );

/**
 * Trim head cruft.
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

/**
 * Fallback OG tags when no SEO plugin is active. SEOPress/Yoast take over once installed.
 */
function hkla_fallback_og() {
	if ( defined( 'SEOPRESS_VERSION' ) || defined( 'WPSEO_VERSION' ) ) {
		return;
	}
	$title = wp_get_document_title();
	$image = '';
	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( null, 'hkla-wide' );
	}
	if ( ! $image && function_exists( 'get_field' ) ) {
		$fallback = get_field( 'default_og_image', 'option' );
		if ( is_array( $fallback ) && ! empty( $fallback['url'] ) ) {
			$image = $fallback['url'];
		}
	}
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	}
}
add_action( 'wp_head', 'hkla_fallback_og', 5 );
