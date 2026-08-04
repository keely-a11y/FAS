<?php
/**
 * ACF configuration: JSON sync, options page, minimal WYSIWYG toolbar.
 *
 * @package hkla
 */

/**
 * Field groups live in the theme's acf-json/ directory (ACF default save/load
 * point once the folder exists). Declared explicitly for clarity.
 */
function hkla_acf_json_save( $path ) {
	return HKLA_DIR . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'hkla_acf_json_save' );

function hkla_acf_json_load( $paths ) {
	$paths[] = HKLA_DIR . '/acf-json';
	return array_unique( $paths );
}
add_filter( 'acf/settings/load_json', 'hkla_acf_json_load' );

/**
 * Site Settings options page.
 */
function hkla_acf_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}
	acf_add_options_page(
		array(
			'page_title' => __( 'Site Settings', 'hkla' ),
			'menu_title' => __( 'Site Settings', 'hkla' ),
			'menu_slug'  => 'hkla-site-settings',
			'capability' => 'edit_pages', // Editors can update contact details and roles.
			'redirect'   => false,
			'position'   => 25,
			'icon_url'   => 'dashicons-admin-generic',
		)
	);
}
add_action( 'acf/init', 'hkla_acf_options_page' );

/**
 * Minimal WYSIWYG toolbar: paragraph, bold, italic, link, lists.
 * No headings inside body fields; headings come from dedicated fields.
 */
function hkla_acf_toolbars( $toolbars ) {
	$toolbars['HKLA Minimal'] = array(
		1 => array( 'bold', 'italic', 'link', 'unlink', 'bullist', 'numlist' ),
	);
	return $toolbars;
}
add_filter( 'acf/fields/wysiwyg/toolbars', 'hkla_acf_toolbars' );

/**
 * Gentle admin warning when ACF Pro is missing: the whole site is field-driven.
 */
function hkla_acf_missing_notice() {
	if ( function_exists( 'acf_add_options_page' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-error"><p>';
	esc_html_e( 'HKLA theme requires ACF Pro. All page content is driven by structured fields and will not render until it is active.', 'hkla' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'hkla_acf_missing_notice' );

/**
 * Safe get_field wrapper so templates degrade instead of fataling without ACF.
 */
function hkla_field( $name, $post_id = false, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $name, $post_id );
	return ( null === $value || false === $value || '' === $value ) ? $default : $value;
}

/**
 * Site Settings shortcut.
 */
function hkla_setting( $name, $default = '' ) {
	return hkla_field( $name, 'option', $default );
}
