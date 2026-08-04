<?php
/**
 * Last-resort fallback template.
 *
 * @package hkla
 */

get_header();
?>
<div class="page-shell">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class(); ?>>
				<h1 class="display-m"><?php the_title(); ?></h1>
				<div class="prose"><?php the_content(); ?></div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<h1 class="display-m"><?php esc_html_e( 'Nothing here yet.', 'hkla' ); ?></h1>
	<?php endif; ?>
</div>
<?php
get_footer();
