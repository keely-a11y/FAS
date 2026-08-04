<?php
/**
 * Section: stats repeater.
 *
 * @package hkla
 */

$stats = get_sub_field( 'stats' );
if ( ! $stats ) {
	return;
}
?>
<section class="section section--stats reveal">
	<dl class="stat-band">
		<?php foreach ( $stats as $stat ) : ?>
			<div class="stat">
				<dt class="stat__label"><?php echo esc_html( $stat['label'] ?? '' ); ?></dt>
				<dd class="stat__value display-m">
					<?php echo esc_html( $stat['value'] ?? '' ); ?>
					<?php if ( ! empty( $stat['note'] ) ) : ?>
						<span class="stat__note"><?php echo esc_html( $stat['note'] ); ?></span>
					<?php endif; ?>
				</dd>
			</div>
		<?php endforeach; ?>
	</dl>
</section>
