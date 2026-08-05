<?php
/**
 * Template Name: About
 *
 * Four blocks only, per the brief: purpose statement, studio narrative,
 * founder feature, team grid.
 *
 * @package hkla
 */

get_header();
the_post();
?>
<div class="page-shell">
	<header class="index-header">
		<h1 class="display-l"><?php echo esc_html( hkla_field( 'purpose_headline', false, 'Design as a civic act.' ) ); ?></h1>
		<?php if ( hkla_field( 'purpose_body' ) ) : ?>
			<div class="lede prose"><?php hkla_rich_text( hkla_field( 'purpose_body' ) ); ?></div>
		<?php endif; ?>
	</header>

	<?php if ( hkla_field( 'studio_narrative' ) ) : ?>
	<section class="band section--text reveal" aria-label="<?php esc_attr_e( 'The studio', 'hkla' ); ?>">
		<div class="prose"><?php hkla_rich_text( hkla_field( 'studio_narrative' ) ); ?></div>
	</section>
	<?php endif; ?>

	<?php
	$founder_name = hkla_field( 'founder_name', false, 'Hongjoo Kim' );
	$founder_bio  = hkla_field( 'founder_bio' );
	?>
	<section class="founder reveal" aria-labelledby="founder-heading">
		<?php if ( hkla_field( 'founder_portrait' ) ) : ?>
			<figure class="founder__portrait">
				<?php hkla_image( hkla_field( 'founder_portrait' ), 'hkla-half', array( 'sizes' => '(min-width: 800px) 40vw, 100vw' ) ); ?>
			</figure>
		<?php endif; ?>
		<div class="founder__text">
			<h2 id="founder-heading" class="display-m"><?php echo esc_html( $founder_name ); ?></h2>
			<?php if ( hkla_field( 'founder_title' ) ) : ?>
				<p class="founder__title utility"><?php echo esc_html( hkla_field( 'founder_title' ) ); ?></p>
			<?php endif; ?>
			<?php if ( $founder_bio ) : ?>
				<div class="prose"><?php hkla_rich_text( $founder_bio ); ?></div>
			<?php endif; ?>
		</div>
	</section>

	<?php
	$people = get_posts(
		array(
			'post_type'      => 'person',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);
	if ( $people ) :
	?>
	<section class="band" aria-labelledby="team-heading">
		<h2 id="team-heading" class="eyebrow"><?php esc_html_e( 'The team', 'hkla' ); ?></h2>
		<ul class="team-grid">
			<?php foreach ( $people as $person ) : ?>
				<li class="person reveal">
					<?php if ( hkla_field( 'headshot', $person->ID ) ) : ?>
						<figure class="person__headshot">
							<?php hkla_image( hkla_field( 'headshot', $person->ID ), 'hkla-headshot', array( 'sizes' => '(min-width: 800px) 25vw, 50vw' ) ); ?>
						</figure>
					<?php else : ?>
						<div class="person__headshot person__headshot--empty" aria-hidden="true"></div>
					<?php endif; ?>
					<h3 class="person__name"><?php echo esc_html( get_the_title( $person ) ); ?></h3>
					<p class="person__role utility">
						<?php echo esc_html( hkla_field( 'role_title', $person->ID ) ); ?>
						<?php if ( hkla_field( 'credentials', $person->ID ) ) : ?>
							<span class="person__credentials"><?php echo esc_html( hkla_field( 'credentials', $person->ID ) ); ?></span>
						<?php endif; ?>
					</p>
					<?php if ( hkla_field( 'bio', $person->ID ) ) : ?>
						<div class="person__bio prose"><?php hkla_rich_text( hkla_field( 'bio', $person->ID ) ); ?></div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php endif; ?>
</div>
<?php
get_footer();
