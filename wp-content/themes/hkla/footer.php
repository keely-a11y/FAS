<?php
/**
 * Site footer on ink. One of the two immersive dark moments.
 *
 * @package hkla
 */
?>
</main>

<footer class="site-footer" role="contentinfo">
	<div class="site-footer__inner">
		<p class="site-footer__line"><?php echo esc_html( hkla_setting( 'footer_line', 'Shared spaces. Shared stories.' ) ); ?></p>

		<div class="site-footer__grid">
			<div class="site-footer__col">
				<?php hkla_wordmark( 'paper', 'site-footer__mark' ); ?>
				<address class="site-footer__address">
					<?php echo nl2br( esc_html( hkla_setting( 'address', "714 West Olympic Blvd, Suite 735\nLos Angeles, CA 90015" ) ) ); ?>
				</address>
				<?php if ( hkla_setting( 'phone' ) ) : ?>
					<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', hkla_setting( 'phone' ) ) ); ?>"><?php echo esc_html( hkla_setting( 'phone' ) ); ?></a></p>
				<?php endif; ?>
				<?php if ( hkla_setting( 'email' ) ) : ?>
					<p><a href="mailto:<?php echo esc_attr( hkla_setting( 'email' ) ); ?>"><?php echo esc_html( hkla_setting( 'email' ) ); ?></a></p>
				<?php endif; ?>
			</div>
			<nav class="site-footer__col site-footer__nav" aria-label="<?php esc_attr_e( 'Footer', 'hkla' ); ?>">
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/careers/' ) ); ?>"><?php esc_html_e( 'Careers', 'hkla' ); ?></a></li>
					<?php if ( hkla_setting( 'instagram_url' ) ) : ?>
						<li><a href="<?php echo esc_url( hkla_setting( 'instagram_url' ) ); ?>" rel="noopener"><?php esc_html_e( 'Instagram', 'hkla' ); ?></a></li>
					<?php endif; ?>
					<?php if ( hkla_setting( 'linkedin_url' ) ) : ?>
						<li><a href="<?php echo esc_url( hkla_setting( 'linkedin_url' ) ); ?>" rel="noopener"><?php esc_html_e( 'LinkedIn', 'hkla' ); ?></a></li>
					<?php endif; ?>
				</ul>
			</nav>
		</div>

		<p class="site-footer__legal">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> HKLA. Hongjoo Kim Landscape Architects.</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
