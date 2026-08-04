<?php
/**
 * Template Name: Purpose
 *
 * Design as a civic act: four commitments with project proof, outcomes, numbers.
 *
 * @package hkla
 */

get_header();
the_post();
?>
<div class="page-shell">
	<header class="index-header">
		<p class="eyebrow"><?php esc_html_e( 'Purpose', 'hkla' ); ?></p>
		<h1 class="display-l"><?php echo esc_html( hkla_field( 'framing_headline', false, 'Design as a civic act.' ) ); ?></h1>
		<?php if ( hkla_field( 'framing_body' ) ) : ?>
			<div class="lede prose"><?php hkla_rich_text( hkla_field( 'framing_body' ) ); ?></div>
		<?php endif; ?>
	</header>

	<?php
	$commitments = hkla_field( 'commitments', false, array() );
	if ( $commitments ) :
	?>
	<section aria-labelledby="commitments-heading">
		<h2 id="commitments-heading" class="visually-hidden"><?php esc_html_e( 'Our commitments', 'hkla' ); ?></h2>
		<ul class="commitments">
			<?php foreach ( $commitments as $commitment ) : ?>
				<li class="commitment reveal">
					<h3 class="commitment__title display-s"><?php echo esc_html( $commitment['title'] ?? '' ); ?></h3>
					<?php if ( ! empty( $commitment['body'] ) ) : ?>
						<div class="prose"><?php hkla_rich_text( $commitment['body'] ); ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $commitment['proof_projects'] ) ) : ?>
						<p class="commitment__proof utility">
							<?php esc_html_e( 'In practice:', 'hkla' ); ?>
							<?php
							$links = array();
							foreach ( (array) $commitment['proof_projects'] as $proof ) {
								$proof_post = $proof instanceof WP_Post ? $proof : get_post( $proof );
								if ( $proof_post ) {
									$links[] = '<a href="' . esc_url( get_permalink( $proof_post ) ) . '">' . esc_html( get_the_title( $proof_post ) ) . '</a>';
								}
							}
							echo implode( ', ', $links ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
							?>
						</p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php endif; ?>

	<?php if ( hkla_field( 'outcomes' ) ) : ?>
	<section class="band reveal" aria-labelledby="outcomes-heading">
		<h2 id="outcomes-heading" class="eyebrow"><?php esc_html_e( 'Community outcomes', 'hkla' ); ?></h2>
		<div class="prose lede"><?php hkla_rich_text( hkla_field( 'outcomes' ) ); ?></div>
	</section>
	<?php endif; ?>

	<?php
	$numbers = hkla_field( 'numbers', false, array() );
	if ( $numbers ) :
	?>
	<section class="band band--stats" aria-labelledby="numbers-heading">
		<h2 id="numbers-heading" class="eyebrow"><?php esc_html_e( 'In numbers', 'hkla' ); ?></h2>
		<dl class="stat-band">
			<?php foreach ( $numbers as $number ) : ?>
				<div class="stat">
					<dt class="stat__label"><?php echo esc_html( $number['label'] ?? '' ); ?></dt>
					<dd class="stat__value display-m"><?php echo esc_html( $number['value'] ?? '' ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</section>
	<?php endif; ?>
</div>
<?php
get_footer();
