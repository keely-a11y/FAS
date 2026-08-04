<?php
/**
 * Template Name: Careers
 *
 * Culture statement, studio imagery, open roles from Site Settings, speculative contact.
 *
 * @package hkla
 */

get_header();
the_post();
?>
<div class="page-shell">
	<header class="index-header">
		<p class="eyebrow"><?php esc_html_e( 'Careers', 'hkla' ); ?></p>
		<h1 class="display-l"><?php echo esc_html( hkla_field( 'culture_statement', false, 'Do the best work of your career on places everyone can use.' ) ); ?></h1>
		<?php if ( hkla_setting( 'careers_intro' ) ) : ?>
			<div class="lede prose"><?php hkla_rich_text( hkla_setting( 'careers_intro' ) ); ?></div>
		<?php endif; ?>
	</header>

	<?php
	$studio_images = hkla_field( 'studio_images', false, array() );
	if ( $studio_images ) :
	?>
	<section class="section section--gallery reveal" aria-label="<?php esc_attr_e( 'Studio life', 'hkla' ); ?>">
		<div class="gallery">
			<?php foreach ( $studio_images as $image ) : ?>
				<figure class="gallery__item">
					<?php hkla_image( $image, 'hkla-half', array( 'sizes' => '(min-width: 800px) 33vw, 100vw' ) ); ?>
				</figure>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<section class="band" aria-labelledby="roles-heading">
		<h2 id="roles-heading" class="eyebrow"><?php esc_html_e( 'Open roles', 'hkla' ); ?></h2>
		<?php
		$roles = hkla_setting( 'open_roles', array() );
		if ( $roles ) :
		?>
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
		<?php else : ?>
			<p class="lede"><?php esc_html_e( 'No open roles right now. Good people are always worth meeting.', 'hkla' ); ?></p>
		<?php endif; ?>
	</section>

	<section class="band band--invite reveal">
		<p class="display-s invite__line"><?php echo esc_html( hkla_field( 'speculative_text', false, 'If you believe public space is worth a career, introduce yourself.' ) ); ?></p>
		<?php if ( hkla_setting( 'email' ) ) : ?>
			<p><a class="button" href="mailto:<?php echo esc_attr( hkla_setting( 'email' ) ); ?>"><?php esc_html_e( 'Say hello', 'hkla' ); ?></a></p>
		<?php endif; ?>
	</section>
</div>
<?php
get_footer();
