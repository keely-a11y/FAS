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
		<p class="eyebrow"><?php esc_html_e( 'Contact', 'hkla' ); ?></p>
		<h1 class="display-l"><?php echo esc_html( hkla_field( 'statement', false, 'Every client works directly with our senior team.' ) ); ?></h1>
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
		</aside>
	</div>
</div>
<?php
get_footer();
