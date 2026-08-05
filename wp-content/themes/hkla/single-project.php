<?php
/**
 * Single project: the narrative case study. Story first, always.
 *
 * @package hkla
 */

get_header();
the_post();

$hero_image = hkla_field( 'hero_image', get_the_ID() ) ?: ( has_post_thumbnail() ? get_post_thumbnail_id() : 0 );
$hero_video = hkla_field( 'hero_video', get_the_ID() );
$headline   = hkla_field( 'story_headline', get_the_ID(), get_the_title() );
$intro      = hkla_field( 'story_intro', get_the_ID() );
$location   = hkla_field( 'location', get_the_ID() );
?>

<article class="project">

	<?php $hero_image_2 = hkla_field( 'hero_image_2', get_the_ID() ); ?>
	<header class="project-hero">
		<h1 class="visually-hidden"><?php the_title(); ?></h1>
		<div class="project-hero__media<?php echo ( $hero_image && $hero_image_2 ) ? ' project-hero__media--pair' : ''; ?>">
			<?php if ( $hero_video ) : ?>
				<video autoplay muted loop playsinline preload="metadata"
					<?php if ( $hero_image ) : ?>poster="<?php echo esc_url( is_array( $hero_image ) ? $hero_image['sizes']['hkla-wide'] ?? $hero_image['url'] : wp_get_attachment_image_url( $hero_image, 'hkla-wide' ) ); ?>"<?php endif; ?>>
					<source src="<?php echo esc_url( $hero_video ); ?>" type="video/mp4">
				</video>
			<?php elseif ( $hero_image ) : ?>
				<?php hkla_hero_image( $hero_image, 'hkla-hero', '' ); ?>
			<?php endif; ?>
			<?php if ( $hero_image_2 && ! $hero_video ) : ?>
				<?php hkla_hero_image( $hero_image_2, 'hkla-hero', '' ); ?>
			<?php endif; ?>
		</div>
	</header>

	<section class="project-lead">
		<h2 class="display-l project-lead__headline reveal"><?php echo esc_html( $headline ); ?></h2>
		<?php if ( $intro ) : ?>
			<div class="project-lead__intro lede"><?php hkla_rich_text( $intro ); ?></div>
		<?php endif; ?>
	</section>

	<?php
	$facts = array(
		__( 'Client', 'hkla' )     => hkla_field( 'client', get_the_ID() ),
		__( 'Location', 'hkla' )   => $location,
		__( 'Size', 'hkla' )       => hkla_field( 'size', get_the_ID() ),
		__( 'Completion', 'hkla' ) => hkla_field( 'completion', get_the_ID() ),
		__( 'Services', 'hkla' )   => hkla_field( 'services', get_the_ID() ),
	);
	$facts = array_filter( $facts );
	if ( $facts ) :
	?>
	<section class="facts-bar" aria-label="<?php esc_attr_e( 'Project facts', 'hkla' ); ?>">
		<dl class="facts-bar__list">
			<?php foreach ( $facts as $label => $value ) : ?>
				<div class="facts-bar__item">
					<dt><?php echo esc_html( $label ); ?></dt>
					<dd><?php echo esc_html( $value ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</section>
	<?php endif; ?>

	<?php
	// Flexible narrative sections.
	if ( function_exists( 'have_rows' ) && have_rows( 'project_sections' ) ) :
		while ( have_rows( 'project_sections' ) ) :
			the_row();
			get_template_part( 'template-parts/sections/section', get_row_layout() );
		endwhile;
	endif;
	?>

	<?php
	$impact_summary = hkla_field( 'impact_summary', get_the_ID() );
	$impact_metrics = hkla_field( 'impact_metrics', get_the_ID(), array() );
	if ( $impact_summary || $impact_metrics ) :
	?>
	<section class="band band--impact reveal" aria-labelledby="impact-heading">
		<h2 id="impact-heading" class="eyebrow"><?php esc_html_e( 'Impact', 'hkla' ); ?></h2>
		<?php if ( $impact_summary ) : ?>
			<p class="display-s"><?php echo esc_html( $impact_summary ); ?></p>
		<?php endif; ?>
		<?php if ( $impact_metrics ) : ?>
			<dl class="stat-band">
				<?php foreach ( $impact_metrics as $metric ) : ?>
					<div class="stat">
						<dt class="stat__label"><?php echo esc_html( $metric['label'] ?? '' ); ?></dt>
						<dd class="stat__value display-m"><?php echo esc_html( $metric['value'] ?? '' ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
		<?php endif; ?>
	</section>
	<?php endif; ?>

	<?php
	$collaborators = hkla_field( 'collaborators', get_the_ID(), array() );
	$awards        = hkla_field( 'awards', get_the_ID(), array() );
	if ( $collaborators || $awards ) :
	?>
	<section class="band band--credits" aria-labelledby="credits-heading">
		<h2 id="credits-heading" class="eyebrow"><?php esc_html_e( 'Credits', 'hkla' ); ?></h2>
		<div class="credits">
			<?php if ( $collaborators ) : ?>
				<div class="credits__col">
					<h3 class="credits__title"><?php esc_html_e( 'Collaborators', 'hkla' ); ?></h3>
					<ul>
						<?php foreach ( $collaborators as $c ) : ?>
							<li><span class="credits__name"><?php echo esc_html( $c['name'] ?? '' ); ?></span> <span class="credits__role"><?php echo esc_html( $c['role'] ?? '' ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			<?php if ( $awards ) : ?>
				<div class="credits__col">
					<h3 class="credits__title"><?php esc_html_e( 'Awards', 'hkla' ); ?></h3>
					<ul>
						<?php foreach ( $awards as $a ) : ?>
							<li>
								<span class="credits__name"><?php echo esc_html( $a['title'] ?? '' ); ?></span>
								<span class="credits__role"><?php echo esc_html( trim( ( $a['organization'] ?? '' ) . ' ' . ( $a['year'] ?? '' ) ) ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php
	$next = hkla_next_project( get_the_ID() );
	if ( $next ) :
	?>
	<aside class="next-project" aria-labelledby="next-heading">
		<p id="next-heading" class="eyebrow"><?php esc_html_e( 'Next project', 'hkla' ); ?></p>
		<a class="next-project__link" href="<?php echo esc_url( get_permalink( $next ) ); ?>">
			<span class="display-m"><?php echo esc_html( hkla_field( 'story_headline', $next->ID, get_the_title( $next ) ) ); ?></span>
			<span class="next-project__name"><?php echo esc_html( get_the_title( $next ) ); ?></span>
		</a>
	</aside>
	<?php endif; ?>

</article>

<?php
get_footer();
