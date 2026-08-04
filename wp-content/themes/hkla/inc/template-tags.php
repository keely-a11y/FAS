<?php
/**
 * Front end helpers.
 *
 * @package hkla
 */

/**
 * Render a responsive image from an ACF image array or attachment ID.
 *
 * @param array|int $image ACF image array or attachment ID.
 * @param string    $size  Registered size.
 * @param array     $attrs Extra attributes (class, sizes, loading, fetchpriority).
 */
function hkla_image( $image, $size = 'hkla-wide', $attrs = array() ) {
	$id = is_array( $image ) ? ( $image['ID'] ?? 0 ) : (int) $image;
	if ( ! $id ) {
		return;
	}
	$defaults = array(
		'loading'  => 'lazy',
		'decoding' => 'async',
	);
	$attrs = array_merge( $defaults, $attrs );
	if ( isset( $attrs['fetchpriority'] ) && 'high' === $attrs['fetchpriority'] ) {
		unset( $attrs['loading'] );
	}
	echo wp_get_attachment_image( $id, $size, false, $attrs );
}

/**
 * Eager hero image: no lazy loading, high fetch priority.
 */
function hkla_hero_image( $image, $size = 'hkla-hero', $class = 'hero__img' ) {
	hkla_image(
		$image,
		$size,
		array(
			'class'         => $class,
			'fetchpriority' => 'high',
			'sizes'         => '100vw',
		)
	);
}

/**
 * Inline the wordmark SVG. Lowercase hkla, never live text.
 *
 * @param string $variant 'ink' or 'paper'.
 */
function hkla_wordmark( $variant = 'ink', $class = '' ) {
	$file = HKLA_DIR . '/assets/img/hkla-wordmark.svg';
	if ( ! file_exists( $file ) ) {
		return;
	}
	$svg = file_get_contents( $file );
	$color = ( 'paper' === $variant ) ? 'var(--color-paper)' : 'var(--color-ink)';
	$svg = str_replace( '<svg ', '<svg style="color:' . $color . '" class="wordmark ' . esc_attr( $class ) . '" ', $svg );
	echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput -- static theme asset.
}

/**
 * Primary nav: menu location if assigned, otherwise the five brand routes.
 */
function hkla_primary_nav() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'nav__list',
				'depth'          => 1,
			)
		);
		return;
	}
	$items = array(
		'Projects' => get_post_type_archive_link( 'project' ),
		'Process'  => home_url( '/process/' ),
		'About'    => home_url( '/about/' ),
		'News'     => home_url( '/news/' ),
		'Contact'  => home_url( '/contact/' ),
	);
	echo '<ul class="nav__list">';
	foreach ( $items as $label => $url ) {
		$current = untrailingslashit( $url ) === untrailingslashit( hkla_current_url() ) ? ' aria-current="page"' : '';
		printf( '<li><a href="%s"%s>%s</a></li>', esc_url( $url ), $current, esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Current URL without query string, for aria-current checks.
 */
function hkla_current_url() {
	global $wp;
	return home_url( $wp->request ? trailingslashit( $wp->request ) : '/' );
}

/**
 * Sector names for a project, comma separated.
 */
function hkla_project_sectors( $post_id = null ) {
	$terms = get_the_terms( $post_id ?? get_the_ID(), 'sector' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return '';
	}
	return implode( ', ', wp_list_pluck( $terms, 'name' ) );
}

/**
 * Next project in the same sector for the single-project footer.
 * Falls back to the most recent other project.
 */
function hkla_next_project( $post_id ) {
	$related = function_exists( 'get_field' ) ? get_field( 'related_projects', $post_id ) : null;
	if ( ! empty( $related ) && is_array( $related ) ) {
		$first = $related[0];
		return $first instanceof WP_Post ? $first : get_post( $first );
	}

	$args = array(
		'post_type'      => 'project',
		'posts_per_page' => 1,
		'post__not_in'   => array( $post_id ),
		'no_found_rows'  => true,
	);
	$terms = get_the_terms( $post_id, 'sector' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'sector',
				'field'    => 'term_id',
				'terms'    => wp_list_pluck( $terms, 'term_id' ),
			),
		);
	}
	$q = get_posts( $args );
	if ( ! $q ) {
		unset( $args['tax_query'] );
		$q = get_posts( $args );
	}
	return $q ? $q[0] : null;
}

/**
 * Minimal WYSIWYG output: keep it to the allowed inline tags and lists.
 */
function hkla_rich_text( $html ) {
	echo wp_kses(
		wpautop( $html ),
		array(
			'p'      => array(),
			'br'     => array(),
			'strong' => array(),
			'b'      => array(),
			'em'     => array(),
			'i'      => array(),
			'a'      => array(
				'href'   => true,
				'title'  => true,
				'rel'    => true,
				'target' => true,
			),
			'ul'     => array(),
			'ol'     => array(),
			'li'     => array(),
		)
	);
}
