<?php
/**
 * Home. Hero, mission, featured projects, quote, recognition.
 *
 * @package hkla
 */

get_header();

$hero_image   = hkla_field( 'hero_image' );
$hero_video   = hkla_field( 'hero_video' );
$hero_line    = hkla_field( 'hero_line', false, 'Shared spaces. Shared stories.' );
$mission      = hkla_field( 'mission_statement', false, 'HKLA is a civic landscape specialist. Grounded in storytelling and a commitment to community, we shape environments for a shared and sustainable future.' );
$featured     = hkla_field( 'featured_projects', false, array() );
?>

<section class="hero hero--home">
	<?php if ( $hero_video ) : ?>
		<video class="hero__media" autoplay muted loop playsinline preload="metadata"
			<?php if ( $hero_image ) : ?>poster="<?php echo esc_url( is_array( $hero_image ) ? $hero_image['sizes']['hkla-wide'] ?? $hero_image['url'] : '' ); ?>"<?php endif; ?>>
			<source src="<?php echo esc_url( $hero_video ); ?>" type="video/mp4">
		</video>
	<?php elseif ( $hero_image ) : ?>
		<?php hkla_hero_image( $hero_image, 'hkla-hero', 'hero__media' ); ?>
	<?php endif; ?>
	<div class="hero__scrim" aria-hidden="true"></div>
	<h1 class="hero__line display-xl"><?php echo esc_html( $hero_line ); ?></h1>
</section>

<section class="band band--mission reveal">
	<p class="mission"><?php echo esc_html( $mission ); ?></p>
</section>

<?php if ( $featured ) : ?>
<section class="band" aria-labelledby="featured-heading">
	<h2 id="featured-heading" class="eyebrow"><?php esc_html_e( 'Selected work', 'hkla' ); ?></h2>
	<div class="project-grid project-grid--featured">
		<?php
		foreach ( $featured as $project ) {
			$post = $project instanceof WP_Post ? $project : get_post( $project );
			if ( $post ) {
				setup_postdata( $GLOBALS['post'] = $post ); // phpcs:ignore
				get_template_part( 'template-parts/project-card' );
			}
		}
		wp_reset_postdata();
		?>
	</div>
	<p class="band__more"><a class="text-link" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>"><?php esc_html_e( 'All projects', 'hkla' ); ?></a></p>
</section>
<?php endif; ?>


<?php
$quote_text = hkla_field( 'quote_text' );
if ( $quote_text ) :
?>
<section class="band band--quote reveal">
	<blockquote class="voice">
		<p class="voice__quote display-s">&ldquo;<?php echo esc_html( $quote_text ); ?>&rdquo;</p>
		<?php if ( hkla_field( 'quote_name' ) ) : ?>
			<footer class="voice__attribution">
				<span class="voice__name"><?php echo esc_html( hkla_field( 'quote_name' ) ); ?></span>
				<?php if ( hkla_field( 'quote_role' ) ) : ?>
					<span class="voice__role"><?php echo esc_html( hkla_field( 'quote_role' ) ); ?></span>
				<?php endif; ?>
			</footer>
		<?php endif; ?>
	</blockquote>
</section>
<?php endif; ?>



<?php
$recognition = hkla_field( 'recognition', false, array() );
if ( $recognition ) :
?>
<section class="band band--recognition" aria-labelledby="recognition-heading">
	<h2 id="recognition-heading" class="eyebrow"><?php esc_html_e( 'Recognition', 'hkla' ); ?></h2>
	<ul class="recognition-strip">
		<?php foreach ( $recognition as $item ) : ?>
			<li><?php echo esc_html( $item['name'] ?? '' ); ?></li>
		<?php endforeach; ?>
	</ul>
</section>
<?php endif; ?>


<?php
get_footer();
