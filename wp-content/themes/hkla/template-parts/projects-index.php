<?php
/**
 * Shared body for the projects archive and sector term views:
 * framing line, server-side sector filter, editorial card grid.
 *
 * @package hkla
 */

$current_term = is_tax( 'sector' ) ? get_queried_object() : null;
$sectors      = get_terms(
	array(
		'taxonomy'   => 'sector',
		'hide_empty' => false,
	)
);
?>
<div class="page-shell">
	<header class="index-header">
		<p class="eyebrow"><?php esc_html_e( 'Projects', 'hkla' ); ?></p>
		<h1 class="display-l"><?php esc_html_e( 'Public places with stories to tell.', 'hkla' ); ?></h1>
	</header>

	<?php if ( $sectors && ! is_wp_error( $sectors ) ) : ?>
	<nav class="filter" aria-label="<?php esc_attr_e( 'Filter projects by sector', 'hkla' ); ?>">
		<ul class="filter__list">
			<li>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>"
					<?php echo $current_term ? '' : 'aria-current="true"'; ?>>
					<?php esc_html_e( 'All', 'hkla' ); ?>
				</a>
			</li>
			<?php foreach ( $sectors as $sector ) : ?>
				<li>
					<a href="<?php echo esc_url( get_term_link( $sector ) ); ?>"
						<?php echo ( $current_term && $current_term->term_id === $sector->term_id ) ? 'aria-current="true"' : ''; ?>>
						<?php echo esc_html( $sector->name ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php endif; ?>

	<div class="project-grid" id="project-grid" aria-live="polite">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/project-card' ); ?>
			<?php endwhile; ?>
		<?php else : ?>
			<p class="project-grid__empty"><?php esc_html_e( 'No projects in this sector yet. The work is coming.', 'hkla' ); ?></p>
		<?php endif; ?>
	</div>

	<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
</div>
