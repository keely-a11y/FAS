<?php
/**
 * Generic page fallback. Brand pages use their dedicated templates;
 * anything else renders title only since pages have no free-form editor.
 *
 * @package hkla
 */

get_header();
the_post();
?>
<div class="page-shell">
	<header class="index-header">
		<h1 class="display-l"><?php the_title(); ?></h1>
	</header>
</div>
<?php
get_footer();
