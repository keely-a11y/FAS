<?php
/**
 * Section: full-bleed image.
 *
 * @package hkla
 */

$image = get_sub_field( 'image' );
if ( ! $image ) {
	return;
}
?>
<figure class="section section--full-image reveal">
	<?php hkla_image( $image, 'hkla-hero', array( 'sizes' => '100vw' ) ); ?>
	<?php if ( get_sub_field( 'caption' ) ) : ?>
		<figcaption class="caption"><?php echo esc_html( get_sub_field( 'caption' ) ); ?></figcaption>
	<?php endif; ?>
</figure>
