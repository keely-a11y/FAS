<?php
/**
 * Section: voice. A quote from the community, client, or team.
 *
 * @package hkla
 */

$quote = get_sub_field( 'quote' );
if ( ! $quote ) {
	return;
}
?>
<section class="section section--voice reveal">
	<blockquote class="voice">
		<p class="voice__quote display-s">&ldquo;<?php echo esc_html( $quote ); ?>&rdquo;</p>
		<?php if ( get_sub_field( 'name' ) ) : ?>
			<footer class="voice__attribution">
				<span class="voice__name"><?php echo esc_html( get_sub_field( 'name' ) ); ?></span>
				<?php if ( get_sub_field( 'role' ) ) : ?>
					<span class="voice__role"><?php echo esc_html( get_sub_field( 'role' ) ); ?></span>
				<?php endif; ?>
			</footer>
		<?php endif; ?>
	</blockquote>
</section>
