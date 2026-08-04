<?php
/**
 * 404 in the brand voice.
 *
 * @package hkla
 */

get_header();
?>
<div class="page-shell page-404">
	<p class="eyebrow"><?php esc_html_e( '404', 'hkla' ); ?></p>
	<h1 class="display-l"><?php esc_html_e( 'This path is not on the plan.', 'hkla' ); ?></h1>
	<p class="lede"><?php esc_html_e( 'The page you are looking for has moved or never existed. The work is still here.', 'hkla' ); ?></p>
	<p><a class="button" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>"><?php esc_html_e( 'See the projects', 'hkla' ); ?></a></p>
</div>
<?php
get_footer();
