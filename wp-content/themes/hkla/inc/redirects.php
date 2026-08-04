<?php
/**
 * Launch redirects. The full legacy Wix map belongs in Redirection or at the
 * server level; the two structural moves are guaranteed here in code.
 *
 * @package hkla
 */

function hkla_legacy_redirects() {
	if ( is_admin() ) {
		return;
	}
	$path = untrailingslashit( wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ) );

	$map = apply_filters(
		'hkla_redirect_map',
		array(
			'/process-of-design' => '/process/',
			'/journal'           => '/news/',
			'/people'            => '/about/',
			'/purpose'           => '/about/',
			'/careers'           => '/about/',
		)
	);

	if ( isset( $map[ $path ] ) ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'hkla_legacy_redirects', 1 );
