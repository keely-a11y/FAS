<?php
/**
 * Section: gallery grid.
 *
 * @package hkla
 */

$images = get_sub_field( 'images' );
if ( ! $images ) {
	return;
}
?>
<section class="section section--gallery reveal" aria-label="<?php esc_attr_e( 'Image gallery', 'hkla' ); ?>">
	<div class="gallery">
		<?php foreach ( $images as $image ) : ?>
			<figure class="gallery__item">
				<?php hkla_image( $image, 'hkla-half', array( 'sizes' => '(min-width: 800px) 33vw, 100vw' ) ); ?>
				<?php if ( ! empty( $image['caption'] ) ) : ?>
					<figcaption class="caption"><?php echo esc_html( $image['caption'] ); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endforeach; ?>
	</div>
</section>
