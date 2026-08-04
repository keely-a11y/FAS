<?php
/**
 * Template Name: Process
 *
 * Process Oriented Design (POD): framing, four stages, POD diagrams,
 * partners section, link to projects.
 *
 * @package hkla
 */

get_header();
the_post();
?>
<div class="page-shell">
	<header class="index-header">
		<p class="eyebrow"><?php esc_html_e( 'Process Oriented Design', 'hkla' ); ?></p>
		<h1 class="display-l"><?php echo esc_html( hkla_field( 'framing_statement', false, 'Before a line is drawn, we find the story.' ) ); ?></h1>
		<?php if ( hkla_field( 'framing_body' ) ) : ?>
			<div class="lede prose"><?php hkla_rich_text( hkla_field( 'framing_body' ) ); ?></div>
		<?php endif; ?>
	</header>

	<?php
	$stages = hkla_field( 'stages', false, array() );
	if ( $stages ) :
	?>
	<ol class="stages">
		<?php foreach ( $stages as $i => $stage ) : ?>
			<li class="stage reveal">
				<p class="stage__number eyebrow"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></p>
				<h2 class="stage__title display-m"><?php echo esc_html( $stage['title'] ?? '' ); ?></h2>
				<?php if ( ! empty( $stage['statement'] ) ) : ?>
					<p class="stage__statement lede"><?php echo esc_html( $stage['statement'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $stage['body'] ) ) : ?>
					<div class="stage__body prose"><?php hkla_rich_text( $stage['body'] ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $stage['image'] ) ) : ?>
					<figure class="stage__media <?php echo ( 2 === $i ) ? 'sketch-frame js-sketch' : ''; ?>">
						<?php hkla_image( $stage['image'], 'hkla-wide', array( 'class' => ( 2 === $i ) ? 'sketch-frame__img' : '' ) ); ?>
					</figure>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
	<?php endif; ?>

	<?php
	$diagrams = hkla_field( 'pod_diagrams', false, array() );
	if ( $diagrams ) :
	?>
	<section class="band" aria-labelledby="pod-heading">
		<h2 id="pod-heading" class="eyebrow"><?php esc_html_e( 'POD, drawn out', 'hkla' ); ?></h2>
		<?php foreach ( $diagrams as $diagram ) : ?>
			<figure class="sketch-frame js-sketch stage__media">
				<?php if ( ! empty( $diagram['image'] ) ) : ?>
					<?php hkla_image( $diagram['image'], 'hkla-wide', array( 'class' => 'sketch-frame__img' ) ); ?>
				<?php endif; ?>
				<?php if ( ! empty( $diagram['caption'] ) ) : ?>
					<figcaption class="caption"><?php echo esc_html( $diagram['caption'] ); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endforeach; ?>
	</section>
	<?php endif; ?>
</div>

<section class="band band--partners reveal" aria-labelledby="partners-heading">
	<h2 id="partners-heading" class="eyebrow"><?php esc_html_e( 'For architecture firms', 'hkla' ); ?></h2>
	<?php if ( hkla_field( 'partners_heading' ) ) : ?>
		<p class="display-s"><?php echo esc_html( hkla_field( 'partners_heading' ) ); ?></p>
	<?php endif; ?>
	<?php if ( hkla_field( 'partners_body' ) ) : ?>
		<div class="prose"><?php hkla_rich_text( hkla_field( 'partners_body' ) ); ?></div>
	<?php endif; ?>
	<?php if ( hkla_field( 'partner_quote' ) ) : ?>
		<blockquote class="voice">
			<p class="voice__quote display-s">&ldquo;<?php echo esc_html( hkla_field( 'partner_quote' ) ); ?>&rdquo;</p>
			<?php if ( hkla_field( 'partner_quote_name' ) ) : ?>
				<footer class="voice__attribution">
					<span class="voice__name"><?php echo esc_html( hkla_field( 'partner_quote_name' ) ); ?></span>
					<?php if ( hkla_field( 'partner_quote_role' ) ) : ?>
						<span class="voice__role"><?php echo esc_html( hkla_field( 'partner_quote_role' ) ); ?></span>
					<?php endif; ?>
				</footer>
			<?php endif; ?>
		</blockquote>
	<?php endif; ?>
	<p class="band__more"><a class="button" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>"><?php esc_html_e( 'See the work', 'hkla' ); ?></a></p>
</section>
<?php
get_footer();
