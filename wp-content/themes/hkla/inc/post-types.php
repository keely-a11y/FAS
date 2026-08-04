<?php
/**
 * CPT and taxonomy registration. In code, never via UI plugins.
 *
 * @package hkla
 */

function hkla_register_post_types() {

	register_post_type(
		'project',
		array(
			'labels'       => array(
				'name'          => __( 'Projects', 'hkla' ),
				'singular_name' => __( 'Project', 'hkla' ),
				'add_new_item'  => __( 'Add New Project', 'hkla' ),
				'edit_item'     => __( 'Edit Project', 'hkla' ),
			),
			'public'       => true,
			'has_archive'  => 'projects',
			'rewrite'      => array(
				'slug'       => 'projects',
				'with_front' => false,
			),
			'menu_icon'    => 'dashicons-location-alt',
			'menu_position' => 5,
			'supports'     => array( 'title', 'thumbnail' ),
			'show_in_rest' => false,
		)
	);

	register_taxonomy(
		'sector',
		'project',
		array(
			'labels'            => array(
				'name'          => __( 'Sectors', 'hkla' ),
				'singular_name' => __( 'Sector', 'hkla' ),
			),
			'public'            => true,
			'hierarchical'      => false,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'       => 'projects/sector',
				'with_front' => false,
			),
			'show_in_rest'      => false,
		)
	);

	register_post_type(
		'person',
		array(
			'labels'        => array(
				'name'          => __( 'People', 'hkla' ),
				'singular_name' => __( 'Person', 'hkla' ),
				'add_new_item'  => __( 'Add New Person', 'hkla' ),
				'edit_item'     => __( 'Edit Person', 'hkla' ),
			),
			'public'             => false,
			'show_ui'            => true,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'has_archive'        => false,
			'menu_icon'          => 'dashicons-groups',
			'menu_position'      => 6,
			'supports'           => array( 'title', 'page-attributes' ),
			'show_in_rest'       => false,
		)
	);
}
add_action( 'init', 'hkla_register_post_types' );

/**
 * Seed the five sectors so the filter is complete from day one.
 */
function hkla_seed_sectors() {
	$sectors = array(
		'civic-parks'              => 'Civic + Parks',
		'education'                => 'Education',
		'healthcare'               => 'Healthcare',
		'infrastructure'           => 'Infrastructure',
		'institutional-commercial' => 'Institutional + Commercial',
	);
	foreach ( $sectors as $slug => $name ) {
		if ( ! term_exists( $slug, 'sector' ) ) {
			wp_insert_term( $name, 'sector', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'after_switch_theme', 'hkla_seed_sectors' );

/**
 * Flush rewrites on activation so /projects/ routes work immediately.
 */
function hkla_flush_rewrites() {
	hkla_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'hkla_flush_rewrites', 20 );
