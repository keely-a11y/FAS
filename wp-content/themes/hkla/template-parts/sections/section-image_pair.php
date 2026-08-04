<?php
/**
 * Section: image pair, side by side on desktop.
 *
 * @package hkla
 */

$image_a = get_sub_field( 'image_a' );
$image_b = get_sub_field( 'image_b' );
if ( ! $image_a && ! $image_b ) {
	return;
}
?>
<figure class="section section--image-pair reveal">
	<div class="pair">
		<?php if ( $image_a ) : ?>
			<div class="pair__item"><?php hkla_image( $image_a, 'hkla-half', array( 'sizes' => '(min-width: 800px) 50vw, 100vw' ) ); ?></div>
		<?php endif; ?>
		<?php if ( $image_b ) : ?>
			<div class="pair__item"><?php hkla_image( $image_b, 'hkla-half', array( 'sizes' => '(min-width: 800px) 50vw, 100vw' ) ); ?></div>
		<?php endif; ?>
	</div>
	<?php if ( get_sub_field( 'caption' ) ) : ?>
		<figcaption class="caption"><?php echo esc_html( get_sub_field( 'caption' ) ); ?></figcaption>
	<?php endif; ?>
</figure>
