<?php
/**
 * Section: text. Measured column, roughly 65ch.
 *
 * @package hkla
 */

$heading = get_sub_field( 'heading' );
$body    = get_sub_field( 'body' );
if ( ! $body ) {
	return;
}
?>
<section class="section section--text reveal">
	<?php if ( $heading ) : ?>
		<h2 class="section__heading display-s"><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>
	<div class="prose"><?php hkla_rich_text( $body ); ?></div>
</section>
