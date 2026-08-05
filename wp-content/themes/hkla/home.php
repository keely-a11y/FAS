<?php
/**
 * News + Recognition index: featured latest entry, then a regular card
 * grid. Category and date lead each card; image is optional and falls
 * back to a palette tone block.
 *
 * @package hkla
 */

get_header();

/**
 * Render the category + date line for a post.
 */
function hkla_news_meta( $post_id ) {
	$cats  = get_the_category( $post_id );
	$label = $cats ? $cats[0]->name : __( 'News', 'hkla' );
	printf(
		'<p class="utility">%s · %s</p>',
		esc_html( $label ),
		esc_html( get_the_date( '', $post_id ) )
	);
}
?>
<div class="page-shell">
	<header class="index-header">
		<h1 class="display-l"><?php esc_html_e( 'Awards and Press', 'hkla' ); ?></h1>
	</header>

	<?php
	$news_cats = get_categories( array( 'hide_empty' => false ) );
	if ( $news_cats ) :
	?>
	<nav class="filter" aria-label="<?php esc_attr_e( 'Filter by category', 'hkla' ); ?>">
		<ul class="filter__list">
			<li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" <?php echo is_category() ? '' : 'aria-current="true"'; ?>><?php esc_html_e( 'All', 'hkla' ); ?></a></li>
			<?php foreach ( $news_cats as $cat ) : ?>
				<?php if ( 'uncategorized' === $cat->slug ) { continue; } ?>
				<li><a href="<?php echo esc_url( get_category_link( $cat ) ); ?>" <?php echo is_category( $cat->term_id ) ? 'aria-current="true"' : ''; ?>><?php echo esc_html( $cat->name ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<?php
		$first = true;
		$cards = array();
		while ( have_posts() ) {
			the_post();
			if ( $first ) {
				$first = false;
				?>
				<article class="news-featured reveal">
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="news-featured__media">
							<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
								<?php the_post_thumbnail( 'hkla-wide' ); ?>
							</a>
						</figure>
					<?php endif; ?>
					<div class="news-featured__text">
						<a href="<?php the_permalink(); ?>">
							<div class="news-featured__meta"><?php hkla_news_meta( get_the_ID() ); ?></div>
							<h2 class="display-m"><?php the_title(); ?></h2>
							<p class="lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
						</a>
					</div>
				</article>
				<?php
			} else {
				$cards[] = get_post();
			}
		}
		?>

		<?php if ( $cards ) : ?>
		<ul class="news-grid">
			<?php
			$tones = array( 'moss', 'earth' );
			foreach ( $cards as $i => $card ) :
			?>
				<li class="news-card reveal">
					<a href="<?php echo esc_url( get_permalink( $card ) ); ?>">
						<?php if ( has_post_thumbnail( $card ) ) : ?>
							<figure class="news-card__media">
								<?php echo get_the_post_thumbnail( $card, 'hkla-half' ); ?>
							</figure>
						<?php else : ?>
							<div class="news-card__media news-card__media--empty news-card__media--<?php echo esc_attr( $tones[ $i % 2 ] ); ?>" aria-hidden="true"></div>
						<?php endif; ?>
						<div class="news-card__meta"><?php hkla_news_meta( $card->ID ); ?></div>
						<h2 class="display-s"><?php echo esc_html( get_the_title( $card ) ); ?></h2>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<?php
		the_posts_pagination(
			array(
				'mid_size'  => 1,
				'prev_text' => __( 'Newer', 'hkla' ),
				'next_text' => __( 'Older', 'hkla' ),
			)
		);
		?>
	<?php else : ?>
		<p class="lede"><?php esc_html_e( 'News is coming.', 'hkla' ); ?></p>
	<?php endif; ?>

	<?php
	$recognition = hkla_setting( 'recognition', array() );
	if ( $recognition ) :
	?>
	<section class="band" aria-labelledby="recognition-index-heading">
		<h2 id="recognition-index-heading" class="eyebrow"><?php esc_html_e( 'Recognition', 'hkla' ); ?></h2>
		<ul class="recognition-list">
			<?php foreach ( $recognition as $item ) : ?>
				<li>
					<span class="credits__name"><?php echo esc_html( $item['award_title'] ?? '' ); ?></span>
					<span class="credits__role"><?php echo esc_html( trim( ( $item['organization'] ?? '' ) . ' ' . ( $item['year'] ?? '' ) ) ); ?></span>
					<?php if ( ! empty( $item['project'] ) ) : ?>
						<a class="text-link" href="<?php echo esc_url( get_permalink( $item['project'] ) ); ?>"><?php echo esc_html( get_the_title( $item['project'] ) ); ?></a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php endif; ?>
</div>
<?php
get_footer();
