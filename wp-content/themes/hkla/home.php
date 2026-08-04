<?php
/**
 * Journal index (phase 2, native posts).
 *
 * @package hkla
 */

get_header();
?>
<div class="page-shell">
	<header class="index-header">
		<p class="eyebrow"><?php esc_html_e( 'Journal', 'hkla' ); ?></p>
		<h1 class="display-l"><?php esc_html_e( 'Notes from the studio.', 'hkla' ); ?></h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<ul class="journal-list">
			<?php while ( have_posts() ) : the_post(); ?>
				<li class="journal-item reveal">
					<a href="<?php the_permalink(); ?>">
						<p class="utility"><?php echo esc_html( get_the_date() ); ?></p>
						<h2 class="display-s"><?php the_title(); ?></h2>
					</a>
				</li>
			<?php endwhile; ?>
		</ul>
		<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
	<?php else : ?>
		<p class="lede"><?php esc_html_e( 'The journal starts soon.', 'hkla' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
