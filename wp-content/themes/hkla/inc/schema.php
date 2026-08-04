<?php
/**
 * Schema.org JSON-LD: Organization sitewide, BreadcrumbList, projects as CreativeWork.
 *
 * @package hkla
 */

function hkla_schema() {
	$graph = array();

	$graph[] = array(
		'@type'   => 'Organization',
		'@id'     => home_url( '/#organization' ),
		'name'    => 'HKLA, Hongjoo Kim Landscape Architects',
		'url'     => home_url( '/' ),
		'slogan'  => 'Shared spaces. Shared stories.',
		'address' => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => hkla_setting( 'address', '714 West Olympic Blvd, Suite 735' ),
			'addressLocality' => 'Los Angeles',
			'addressRegion'   => 'CA',
			'postalCode'      => '90015',
			'addressCountry'  => 'US',
		),
	);

	$crumbs = array(
		array(
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => 'Home',
			'item'     => home_url( '/' ),
		),
	);

	if ( is_singular( 'project' ) ) {
		$crumbs[] = array(
			'@type'    => 'ListItem',
			'position' => 2,
			'name'     => 'Projects',
			'item'     => get_post_type_archive_link( 'project' ),
		);
		$crumbs[] = array(
			'@type'    => 'ListItem',
			'position' => 3,
			'name'     => get_the_title(),
			'item'     => get_permalink(),
		);

		$work = array(
			'@type'    => array( 'CreativeWork', 'Article' ),
			'headline' => hkla_field( 'story_headline', get_the_ID(), get_the_title() ),
			'name'     => get_the_title(),
			'url'      => get_permalink(),
			'author'   => array( '@id' => home_url( '/#organization' ) ),
			'datePublished' => get_the_date( 'c' ),
			'dateModified'  => get_the_modified_date( 'c' ),
		);
		if ( has_post_thumbnail() ) {
			$work['image'] = get_the_post_thumbnail_url( null, 'hkla-wide' );
		}
		$location = hkla_field( 'location', get_the_ID() );
		if ( $location ) {
			$work['contentLocation'] = array(
				'@type' => 'Place',
				'name'  => $location,
			);
		}
		$graph[] = $work;
	} elseif ( ! is_front_page() ) {
		$crumbs[] = array(
			'@type'    => 'ListItem',
			'position' => 2,
			'name'     => wp_strip_all_tags( single_post_title( '', false ) ?: post_type_archive_title( '', false ) ?: get_the_title() ),
			'item'     => hkla_current_url(),
		);
	}

	if ( count( $crumbs ) > 1 ) {
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $crumbs,
		);
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_UNESCAPED_SLASHES
		)
	);
}
add_action( 'wp_head', 'hkla_schema', 9 );
