<?php
/**
 * Journal entry.
 *
 * @package hkla
 */

get_header();
the_post();
?>
<article class="page-shell journal-entry">
	<header class="index-header">
		<p class="utility"><?php echo esc_html( get_the_date() ); ?></p>
		<h1 class="display-l"><?php the_title(); ?></h1>
	</header>
	<div class="prose">
		<?php the_content(); ?>
	</div>
	<p class="band__more"><a class="text-link" href="<?php echo esc_url( home_url( '/journal/' ) ); ?>"><?php esc_html_e( 'Back to the journal', 'hkla' ); ?></a></p>
</article>
<?php
get_footer();
