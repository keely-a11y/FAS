<?php
/**
 * Section: video. Self-hosted MP4 or Vimeo URL, poster required by field setup.
 *
 * @package hkla
 */

$video_url = get_sub_field( 'video_url' );
$poster    = get_sub_field( 'poster' );
if ( ! $video_url ) {
	return;
}

$is_vimeo = str_contains( $video_url, 'vimeo.com' );
?>
<section class="section section--video reveal">
	<?php if ( $is_vimeo ) : ?>
		<div class="video-embed">
			<?php echo wp_oembed_get( $video_url ) ?: ''; // phpcs:ignore WordPress.Security.EscapeOutput -- oEmbed HTML. ?>
		</div>
	<?php else : ?>
		<video class="video-native" controls preload="none" playsinline
			<?php if ( $poster ) : ?>poster="<?php echo esc_url( is_array( $poster ) ? ( $poster['sizes']['hkla-wide'] ?? $poster['url'] ) : $poster ); ?>"<?php endif; ?>>
			<source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
		</video>
	<?php endif; ?>
</section>
