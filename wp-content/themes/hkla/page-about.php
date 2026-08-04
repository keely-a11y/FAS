<?php
/**
 * Template Name: About
 *
 * The studio in one page: approach, founder, team, commitments,
 * recognition, open roles, contact invitation.
 *
 * @package hkla
 */

get_header();
the_post();
?>
<div class="page-shell">
	<header class="index-header">
		<p class="eyebrow"><?php esc_html_e( 'About', 'hkla' ); ?></p>
		<h1 class="display-l"><?php echo esc_html( hkla_field( 'framing_statement', false, 'Harmony between people and nature.' ) ); ?></h1>
		<?php if ( hkla_field( 'approach_body' ) ) : ?>
			<div class="lede prose"><?php hkla_rich_text( hkla_field( 'approach_body' ) ); ?></div>
		<?php endif; ?>
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

<div class="page-shell">
	<?php
	$commitments = hkla_field( 'commitments', false, array() );
	if ( $commitments ) :
	?>
	<section aria-labelledby="commitments-heading">
		<h2 id="commitments-heading" class="eyebrow"><?php esc_html_e( 'How we build', 'hkla' ); ?></h2>
		<ul class="commitments">
			<?php foreach ( $commitments as $commitment ) : ?>
				<li class="commitment reveal">
					<h3 class="commitment__title display-s"><?php echo esc_html( $commitment['title'] ?? '' ); ?></h3>
					<?php if ( ! empty( $commitment['body'] ) ) : ?>
						<div class="prose"><?php hkla_rich_text( $commitment['body'] ); ?></div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php endif; ?>

	<?php
	$recognition = hkla_field( 'recognition', false, array() );
	if ( $recognition ) :
	?>
	<section class="band" aria-labelledby="about-recognition-heading">
		<h2 id="about-recognition-heading" class="eyebrow"><?php esc_html_e( 'Recognition', 'hkla' ); ?></h2>
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

	<?php
	$roles = hkla_setting( 'open_roles', array() );
	if ( $roles ) :
	?>
	<section class="band" aria-labelledby="roles-heading">
		<h2 id="roles-heading" class="eyebrow"><?php esc_html_e( 'Open roles', 'hkla' ); ?></h2>
		<ul class="roles">
			<?php foreach ( $roles as $role ) : ?>
				<li class="role reveal">
					<h3 class="role__title display-s"><?php echo esc_html( $role['role_title'] ?? '' ); ?></h3>
					<p class="role__meta utility">
						<?php echo esc_html( trim( ( $role['role_type'] ?? '' ) . ' · ' . ( $role['role_location'] ?? '' ), ' ·' ) ); ?>
					</p>
					<?php if ( ! empty( $role['description'] ) ) : ?>
						<div class="prose"><?php hkla_rich_text( $role['description'] ); ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $role['apply_link'] ) ) : ?>
						<p><a class="text-link" href="<?php echo esc_url( $role['apply_link'] ); ?>"><?php esc_html_e( 'Apply for this role', 'hkla' ); ?></a></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php endif; ?>

	<section class="band band--invite reveal">
		<p class="display-s invite__line"><?php echo esc_html( hkla_field( 'contact_invitation', false, 'Every client works directly with our senior team.' ) ); ?></p>
		<p><a class="button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Start a conversation', 'hkla' ); ?></a></p>
	</section>
</div>
<?php
get_footer();
