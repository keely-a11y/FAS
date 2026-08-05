<?php
/**
 * Template Name: Careers
 *
 * Culture statement, open roles, how to apply. Linked from the footer,
 * deliberately outside the main menu.
 *
 * @package hkla
 */

get_header();
the_post();
?>
<div class="page-shell">
	<header class="index-header">
		<h1 class="display-l"><?php echo esc_html( hkla_field( 'culture_headline', false, 'Work on places people share.' ) ); ?></h1>
		<?php if ( hkla_field( 'culture_body' ) ) : ?>
			<div class="lede prose"><?php hkla_rich_text( hkla_field( 'culture_body' ) ); ?></div>
		<?php endif; ?>
	</header>

	<?php
	$roles = hkla_field( 'open_roles', false, array() );
	if ( $roles ) :
	?>
	<section class="band" aria-labelledby="roles-heading">
		<h2 id="roles-heading" class="eyebrow"><?php esc_html_e( 'Open roles', 'hkla' ); ?></h2>
		<ul class="roles">
			<?php foreach ( $roles as $role ) : ?>
				<li class="role reveal">
					<h3 class="role__title display-s"><?php echo esc_html( $role['role_title'] ?? '' ); ?></h3>
					<p class="role__meta utility">
						<?php echo esc_html( trim( ( $role['role_type'] ?? '' ) . ' · ' . ( $role['role_location'] ?? '' ), ' ·' ) ); ?>
					</p>
					<?php if ( ! empty( $role['description'] ) ) : ?>
						<div class="prose"><?php hkla_rich_text( $role['description'] ); ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $role['apply_link'] ) ) : ?>
						<p><a class="text-link" href="<?php echo esc_url( $role['apply_link'] ); ?>"><?php esc_html_e( 'Apply for this role', 'hkla' ); ?></a></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php else : ?>
	<section class="band" aria-labelledby="roles-heading">
		<h2 id="roles-heading" class="eyebrow"><?php esc_html_e( 'Open roles', 'hkla' ); ?></h2>
		<p class="lede"><?php esc_html_e( 'No open roles right now. We still want to hear from you.', 'hkla' ); ?></p>
	</section>
	<?php endif; ?>

	<section class="band" aria-labelledby="apply-heading">
		<h2 id="apply-heading" class="eyebrow"><?php esc_html_e( 'How to apply', 'hkla' ); ?></h2>
		<?php if ( hkla_field( 'apply_note' ) ) : ?>
			<div class="prose"><?php hkla_rich_text( hkla_field( 'apply_note' ) ); ?></div>
		<?php endif; ?>
		<?php if ( hkla_field( 'apply_email' ) ) : ?>
			<p class="lede"><a href="mailto:<?php echo esc_attr( hkla_field( 'apply_email' ) ); ?>"><?php echo esc_html( hkla_field( 'apply_email' ) ); ?></a></p>
		<?php endif; ?>
	</section>
</div>
<?php
get_footer();
