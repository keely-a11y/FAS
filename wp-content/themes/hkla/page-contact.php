<?php
/**
 * Template Name: Contact
 *
 * Senior-access statement, form, studio details.
 *
 * @package hkla
 */

get_header();
the_post();
?>
<div class="page-shell">
	<header class="index-header">
		<h1 class="visually-hidden"><?php esc_html_e( 'Contact', 'hkla' ); ?></h1>
	</header>

	<div class="contact-layout">
		<div class="contact-layout__form">
			<?php hkla_contact_form(); ?>
		</div>
		<aside class="contact-layout__details">
			<h2 class="eyebrow"><?php esc_html_e( 'Studio', 'hkla' ); ?></h2>
			<address>
				<?php echo nl2br( esc_html( hkla_setting( 'address', "714 West Olympic Blvd, Suite 735\nLos Angeles, CA 90015" ) ) ); ?>
			</address>
			<?php if ( hkla_setting( 'phone' ) ) : ?>
				<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', hkla_setting( 'phone' ) ) ); ?>"><?php echo esc_html( hkla_setting( 'phone' ) ); ?></a></p>
			<?php endif; ?>
			<?php if ( hkla_setting( 'email' ) ) : ?>
				<p><a href="mailto:<?php echo esc_attr( hkla_setting( 'email' ) ); ?>"><?php echo esc_html( hkla_setting( 'email' ) ); ?></a></p>
			<?php endif; ?>
			<ul class="contact-social">
				<?php if ( hkla_setting( 'instagram_url' ) ) : ?>
					<li><a href="<?php echo esc_url( hkla_setting( 'instagram_url' ) ); ?>" rel="noopener"><?php esc_html_e( 'Instagram', 'hkla' ); ?></a></li>
				<?php endif; ?>
				<?php if ( hkla_setting( 'linkedin_url' ) ) : ?>
					<li><a href="<?php echo esc_url( hkla_setting( 'linkedin_url' ) ); ?>" rel="noopener"><?php esc_html_e( 'LinkedIn', 'hkla' ); ?></a></li>
				<?php endif; ?>
			</ul>
			<?php if ( hkla_setting( 'rfp_email' ) ) : ?>
				<p class="caption"><?php esc_html_e( 'RFPs and proposal requests:', 'hkla' ); ?> <a href="mailto:<?php echo esc_attr( hkla_setting( 'rfp_email' ) ); ?>"><?php echo esc_html( hkla_setting( 'rfp_email' ) ); ?></a></p>
			<?php endif; ?>
		</aside>
	</div>

	<section class="band" aria-labelledby="contact-careers-heading">
		<h2 id="contact-careers-heading" class="eyebrow"><?php esc_html_e( 'Careers', 'hkla' ); ?></h2>
		<p class="lede"><?php esc_html_e( 'Want to build shared places with us?', 'hkla' ); ?> <a class="text-link" href="<?php echo esc_url( home_url( '/careers/' ) ); ?>"><?php esc_html_e( 'See open roles', 'hkla' ); ?></a></p>
	</section>

	<?php if ( hkla_setting( 'press_email' ) ) : ?>
	<section class="band" aria-labelledby="contact-press-heading">
		<h2 id="contact-press-heading" class="eyebrow"><?php esc_html_e( 'Press', 'hkla' ); ?></h2>
		<p class="lede"><a href="mailto:<?php echo esc_attr( hkla_setting( 'press_email' ) ); ?>"><?php echo esc_html( hkla_setting( 'press_email' ) ); ?></a></p>
	</section>
	<?php endif; ?>
</div>
<?php
get_footer();
