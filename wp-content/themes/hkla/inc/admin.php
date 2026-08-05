<?php
/**
 * Admin cleanup and editor guardrails.
 *
 * @package hkla
 */

/**
 * Comments are off sitewide. Journal is editorial, not conversational.
 */
function hkla_disable_comments() {
	foreach ( get_post_types() as $type ) {
		remove_post_type_support( $type, 'comments' );
		remove_post_type_support( $type, 'trackbacks' );
	}
}
add_action( 'init', 'hkla_disable_comments', 100 );

add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );

function hkla_admin_menu_cleanup() {
	remove_menu_page( 'edit-comments.php' );
	if ( ! current_user_can( 'manage_options' ) ) {
		remove_menu_page( 'tools.php' );
	}
}
add_action( 'admin_menu', 'hkla_admin_menu_cleanup', 999 );

/**
 * Rename Posts to News.
 */
function hkla_rename_posts_menu() {
	global $menu, $submenu;
	if ( isset( $menu ) ) {
		foreach ( $menu as $key => $item ) {
			if ( 'edit.php' === ( $item[2] ?? '' ) ) {
				$menu[ $key ][0] = __( 'News + Ideas', 'hkla' );
			}
		}
	}
	if ( isset( $submenu['edit.php'][5] ) ) {
		$submenu['edit.php'][5][0] = __( 'All News + Ideas', 'hkla' );
	}
}
add_action( 'admin_menu', 'hkla_rename_posts_menu' );

function hkla_rename_posts_labels( $labels ) {
	$labels->name          = __( 'News + Ideas', 'hkla' );
	$labels->singular_name = __( 'News Entry', 'hkla' );
	$labels->add_new_item  = __( 'Add New News Entry', 'hkla' );
	$labels->edit_item     = __( 'Edit News Entry', 'hkla' );
	$labels->menu_name     = __( 'News + Ideas', 'hkla' );
	return $labels;
}
add_filter( 'post_type_labels_post', 'hkla_rename_posts_labels' );

/**
 * Dashboard widget: how to update this site.
 */
function hkla_dashboard_widget() {
	wp_add_dashboard_widget( 'hkla_guide', __( 'How to update this site', 'hkla' ), 'hkla_dashboard_widget_render' );
}
add_action( 'wp_dashboard_setup', 'hkla_dashboard_widget' );

function hkla_dashboard_widget_render() {
	echo '<p>' . esc_html__( 'Everything on the site is edited through structured fields. You change words and images; the design takes care of itself.', 'hkla' ) . '</p>';
	echo '<ul style="list-style:disc;padding-left:1.2em">';
	echo '<li>' . wp_kses_post( __( '<strong>Add a project:</strong> Projects, Add New. Fill in the story headline, intro, hero image, facts, and story sections. Assign a sector.', 'hkla' ) ) . '</li>';
	echo '<li>' . wp_kses_post( __( '<strong>Edit a bio:</strong> People. Each person has a role, credentials, bio, and headshot.', 'hkla' ) ) . '</li>';
	echo '<li>' . wp_kses_post( __( '<strong>Post an open role:</strong> Site Settings, Open Roles.', 'hkla' ) ) . '</li>';
	echo '<li>' . wp_kses_post( __( '<strong>Update contact details:</strong> Site Settings.', 'hkla' ) ) . '</li>';
	echo '</ul>';
	echo '<p><a href="https://github.com/keely-a11y/fas/blob/main/docs/EDITOR_GUIDE.md">' . esc_html__( 'Read the full editor guide', 'hkla' ) . '</a></p>';
}

/**
 * Alt text guardrail 1: a visible column in the media library.
 */
function hkla_media_alt_column( $columns ) {
	$columns['hkla_alt'] = __( 'Alt Text', 'hkla' );
	return $columns;
}
add_filter( 'manage_media_columns', 'hkla_media_alt_column' );

function hkla_media_alt_column_content( $column, $post_id ) {
	if ( 'hkla_alt' !== $column ) {
		return;
	}
	$alt = get_post_meta( $post_id, '_wp_attachment_image_alt', true );
	if ( $alt ) {
		echo esc_html( $alt );
	} elseif ( wp_attachment_is_image( $post_id ) ) {
		echo '<span style="color:#b32d2e;font-weight:600">' . esc_html__( 'Missing', 'hkla' ) . '</span>';
	}
}
add_action( 'manage_media_custom_column', 'hkla_media_alt_column_content', 10, 2 );

/**
 * Alt text guardrail 2: persistent notice counting images without alt text.
 */
function hkla_missing_alt_notice() {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->base, array( 'dashboard', 'upload' ), true ) ) {
		return;
	}
	$missing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					'key'     => '_wp_attachment_image_alt',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_wp_attachment_image_alt',
					'value'   => '',
					'compare' => '=',
				),
			),
		)
	);
	if ( $missing ) {
		printf(
			'<div class="notice notice-warning"><p>%s <a href="%s">%s</a></p></div>',
			esc_html__( 'Some images are missing alt text. Every image needs a short description before publishing: it is required for accessibility.', 'hkla' ),
			esc_url( admin_url( 'upload.php?mode=list' ) ),
			esc_html__( 'Review the media library.', 'hkla' )
		);
	}
}
add_action( 'admin_notices', 'hkla_missing_alt_notice' );

/**
 * Keep editors out of anything structural that a role or plugin might expose.
 */
function hkla_editor_caps( $caps, $cap ) {
	$blocked = array( 'edit_theme_options', 'customize', 'switch_themes', 'edit_themes' );
	if ( in_array( $cap, $blocked, true ) && ! current_user_can( 'manage_options' ) ) {
		// Menus stay admin-only; editors have no layout surface anywhere.
		$caps[] = 'manage_options';
	}
	return $caps;
}
add_filter( 'map_meta_cap', 'hkla_editor_caps', 10, 2 );
