<?php
/**
 * Section: sketch spotlight. White ground, generous margins, drawn-line reveal.
 * The one signature motion moment, fully off under prefers-reduced-motion.
 *
 * @package hkla
 */

$image = get_sub_field( 'image' );
if ( ! $image ) {
	return;
}
?>
<section class="section section--sketch">
	<figure class="sketch-frame js-sketch">
		<svg class="sketch-frame__line" viewBox="0 0 600 12" preserveAspectRatio="none" aria-hidden="true" focusable="false">
			<path d="M2 8 C 90 3, 180 10, 300 6 S 520 4, 598 7" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" pathLength="1" />
		</svg>
		<?php hkla_image( $image, 'hkla-wide', array( 'class' => 'sketch-frame__img', 'sizes' => '(min-width: 1100px) 900px, 92vw' ) ); ?>
		<?php if ( get_sub_field( 'caption' ) ) : ?>
			<figcaption class="caption caption--sketch"><?php echo esc_html( get_sub_field( 'caption' ) ); ?></figcaption>
		<?php endif; ?>
	</figure>
</section>
