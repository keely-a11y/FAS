<?php
/**
 * Template Name: People
 *
 * WHO framing, founder feature, team grid, boutique statement, recognition, careers invitation.
 *
 * @package hkla
 */

get_header();
the_post();
?>
<div class="page-shell">
	<header class="index-header">
		<p class="eyebrow"><?php esc_html_e( 'People', 'hkla' ); ?></p>
		<h1 class="display-l"><?php echo esc_html( hkla_field( 'framing_statement', false, 'The people behind the places.' ) ); ?></h1>
	</header>

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

<section class="band band--statement reveal">
	<p class="display-m"><?php echo esc_html( hkla_field( 'boutique_statement', false, 'Boutique by design. Small enough that every project gets our best people. Experienced enough that nothing surprises us.' ) ); ?></p>
</section>

<?php
$recognition = hkla_field( 'recognition', false, array() );
if ( $recognition ) :
?>
<section class="band" aria-labelledby="people-recognition-heading">
	<h2 id="people-recognition-heading" class="eyebrow"><?php esc_html_e( 'Recognition', 'hkla' ); ?></h2>
	<ul class="recognition-list">
		<?php foreach ( $recognition as $item ) : ?>
			<li>
				<span class="credits__name"><?php echo esc_html( $item['title'] ?? '' ); ?></span>
				<span class="credits__role"><?php echo esc_html( trim( ( $item['organization'] ?? '' ) . ' ' . ( $item['year'] ?? '' ) ) ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
<?php endif; ?>

<section class="band band--invite reveal">
	<p class="display-s invite__line"><?php echo esc_html( hkla_field( 'careers_invitation', false, 'We hire people who care about public life. Come build shared places with us.' ) ); ?></p>
	<p><a class="button" href="<?php echo esc_url( home_url( '/careers/' ) ); ?>"><?php esc_html_e( 'Careers at HKLA', 'hkla' ); ?></a></p>
</section>
<?php
get_footer();
