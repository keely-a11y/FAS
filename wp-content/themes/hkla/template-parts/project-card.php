<?php
/**
 * Editorial project card: story headline leads, then name, sector, location.
 *
 * @package hkla
 */

$headline = hkla_field( 'story_headline', get_the_ID(), get_the_title() );
$location = hkla_field( 'location', get_the_ID() );
?>
<article class="project-card reveal">
	<a class="project-card__link" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="project-card__media">
				<?php the_post_thumbnail( 'hkla-card', array( 'class' => 'project-card__img', 'loading' => 'lazy', 'sizes' => '(min-width: 900px) 45vw, 100vw' ) ); ?>
			</div>
		<?php endif; ?>
		<h3 class="project-card__headline display-s"><?php echo esc_html( $headline ); ?></h3>
		<p class="project-card__meta">
			<span class="project-card__name"><?php the_title(); ?></span>
			<?php if ( hkla_project_sectors() ) : ?>
				<span class="project-card__sector"><?php echo esc_html( hkla_project_sectors() ); ?></span>
			<?php endif; ?>
			<?php if ( $location ) : ?>
				<span class="project-card__location"><?php echo esc_html( $location ); ?></span>
			<?php endif; ?>
		</p>
	</a>
</article>
