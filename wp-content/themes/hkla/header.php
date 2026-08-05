<?php
/**
 * Site header: wordmark left, five nav items.
 *
 * @package hkla
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'hkla' ); ?></a>

<?php
// Home floats the nav over the hero; project pages carry their title in the band.
$hkla_overlay = is_front_page();
?>
<header class="site-header<?php echo $hkla_overlay ? ' site-header--overlay' : ''; ?>" role="banner">
	<div class="site-header__inner">
		<a class="site-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'HKLA home', 'hkla' ); ?>">
			<?php hkla_wordmark( $hkla_overlay ? 'paper' : 'ink' ); ?>
		</a>
		<?php if ( is_singular( 'project' ) ) : ?>
			<p class="site-header__title"><?php the_title(); ?></p>
		<?php endif; ?>
		<button class="nav-toggle" aria-expanded="false" aria-controls="site-nav">
			<span class="nav-toggle__label"><?php esc_html_e( 'Menu', 'hkla' ); ?></span>
			<span class="nav-toggle__bars" aria-hidden="true"></span>
		</button>
		<nav id="site-nav" class="nav" aria-label="<?php esc_attr_e( 'Primary', 'hkla' ); ?>">
			<?php hkla_primary_nav(); ?>
		</nav>
	</div>
</header>

<main id="main" class="site-main">
