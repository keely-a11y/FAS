<?php
/**
 * HKLA seed content. Run with:
 *   wp eval-file bin/seed-content.php
 *
 * Creates the committed pages (Home, Process, Projects archive, About,
 * News, Contact), three real HKLA projects with copy edited into the new
 * brand voice, sample people, and site settings. Idempotent: existing
 * items are left alone.
 *
 * Project facts and copy are drawn from hklainc.com and public sources,
 * tone-aligned to the new brand. Verify details with HKLA before launch.
 * Placeholder images are generated locally with GD in the palette's warm
 * tones; replace with real photography from the asset pack.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

/**
 * Generate and sideload a placeholder image in a warm tone.
 */
function hkla_seed_image( $label, $width = 2400, $height = 1500, $tone = 'stone' ) {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'title'          => $label,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( $existing ) {
		return $existing[0];
	}

	$tones = array(
		'stone' => array( 151, 142, 125 ),
		'paper' => array( 239, 235, 232 ),
		'moss'  => array( 122, 130, 103 ),
		'earth' => array( 118, 100, 82 ),
	);
	$rgb = $tones[ $tone ] ?? $tones['stone'];

	$img = imagecreatetruecolor( $width, $height );
	$bg  = imagecolorallocate( $img, $rgb[0], $rgb[1], $rgb[2] );
	imagefill( $img, 0, 0, $bg );

	// A quiet horizon line so images read as landscapes, not swatches.
	$line = imagecolorallocatealpha( $img, 0, 0, 0, 96 );
	imagesetthickness( $img, max( 2, (int) ( $height / 400 ) ) );
	imageline( $img, 0, (int) ( $height * 0.62 ), $width, (int) ( $height * 0.58 ), $line );

	$text = imagecolorallocatealpha( $img, 255, 255, 255, 40 );
	imagestring( $img, 5, 24, 24, strtoupper( $label ), $text );

	$tmp = wp_tempnam( sanitize_title( $label ) . '.jpg' );
	imagejpeg( $img, $tmp, 78 );
	imagedestroy( $img );

	$attachment_id = media_handle_sideload(
		array(
			'name'     => sanitize_title( $label ) . '.jpg',
			'tmp_name' => $tmp,
		),
		0,
		$label
	);
	if ( is_wp_error( $attachment_id ) ) {
		WP_CLI::warning( 'Image failed: ' . $attachment_id->get_error_message() );
		return 0;
	}
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $label );
	return $attachment_id;
}

/**
 * Create a page with a template if it does not exist. Returns the ID.
 */
function hkla_seed_page( $title, $slug, $template = '' ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		return $page->ID;
	}
	$id = wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_status' => 'publish',
		)
	);
	if ( $template && ! is_wp_error( $id ) ) {
		update_post_meta( $id, '_wp_page_template', $template );
	}
	return $id;
}

WP_CLI::log( 'Seeding sectors...' );
hkla_seed_sectors();

WP_CLI::log( 'Seeding pages...' );
$home_id    = hkla_seed_page( 'Home', 'home' );
$process_id = hkla_seed_page( 'Process', 'process', 'page-process.php' );
$about_id   = hkla_seed_page( 'About', 'about', 'page-about.php' );
$contact_id = hkla_seed_page( 'Contact', 'contact', 'page-contact.php' );
$news_id    = hkla_seed_page( 'News', 'news' );

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home_id );
update_option( 'page_for_posts', $news_id );

/**
 * Three real HKLA projects, copy tone-aligned to the new brand voice:
 * short declarative headlines, no jargon, no hedging, no em dashes.
 */
$projects = array(
	array(
		'title'    => 'Vermont Miracle Park',
		'sector'   => 'civic-parks',
		'tone'     => 'moss',
		'headline' => 'Open space for a neighborhood that had none.',
		'intro'    => "<p>South Los Angeles has some of the least park space in the county. This block of Vermont Avenue had a story worth building on.</p><p>The park carries five values the community chose: creativity, leadership, positivism, stewardship, and resiliency. Every path, planting, and gathering place answers to one of them.</p>",
		'facts'    => array(
			'location'   => 'South Los Angeles',
			'services'   => 'Landscape architecture, community engagement, planting design',
		),
		'impact'   => 'An underserved community gained real open space, designed around the values its residents named.',
		'metrics'  => array(
			array( 'value' => '5', 'label' => 'Community values built into the design' ),
		),
	),
	array(
		'title'    => 'UCR Student Success Center',
		'sector'   => 'education',
		'tone'     => 'earth',
		'headline' => 'A campus crossroads became common ground.',
		'intro'    => "<p>A student center works when students actually cross paths. The landscape makes that happen.</p><p>Porous, multi-level circulation draws people through, and carefully placed outdoor rooms give them reasons to stay. Campus planting palettes and paving let the new center belong to Riverside from day one.</p>",
		'facts'    => array(
			'client'     => 'University of California, Riverside',
			'location'   => 'Riverside, California',
			'completion' => '2022',
			'services'   => 'Landscape architecture, hardscape design, campus integration',
		),
		'impact'   => 'The center reads as if it was always part of campus, and the outdoor spaces work as hard as the building.',
		'metrics'  => array(
			array( 'value' => '2022', 'label' => 'DBDA National Award' ),
		),
	),
	array(
		'title'    => 'Rancho Los Amigos National Rehabilitation Center',
		'sector'   => 'healthcare',
		'tone'     => 'stone',
		'headline' => 'Landscape as part of the recovery.',
		'intro'    => "<p>Healing does not stop at the building door. At one of the nation's leading rehabilitation hospitals, the grounds are part of the treatment.</p><p>Quiet courtyards, accessible paths, and planting chosen for calm give patients, families, and staff a place to breathe.</p>",
		'facts'    => array(
			'client'     => 'County of Los Angeles',
			'location'   => 'Downey, California',
			'services'   => 'Landscape architecture, therapeutic landscape, accessible design',
		),
		'impact'   => 'The campus treats its open space as clinical space, and patients use it every day.',
		'metrics'  => array(),
	),
);

WP_CLI::log( 'Seeding projects...' );
foreach ( $projects as $data ) {
	if ( get_page_by_path( sanitize_title( $data['title'] ), OBJECT, 'project' ) ) {
		WP_CLI::log( '  Exists: ' . $data['title'] );
		continue;
	}

	$id = wp_insert_post(
		array(
			'post_type'   => 'project',
			'post_title'  => $data['title'],
			'post_status' => 'publish',
		)
	);
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( '  Failed: ' . $data['title'] );
		continue;
	}

	wp_set_object_terms( $id, $data['sector'], 'sector' );

	$hero_id   = hkla_seed_image( $data['title'] . ' hero', 2400, 1500, $data['tone'] );
	$wide_id   = hkla_seed_image( $data['title'] . ' site', 1600, 1000, $data['tone'] );
	$sketch_id = hkla_seed_image( $data['title'] . ' sketch', 1600, 1100, 'paper' );

	if ( $hero_id ) {
		set_post_thumbnail( $id, $hero_id );
	}

	if ( function_exists( 'update_field' ) ) {
		update_field( 'story_headline', $data['headline'], $id );
		update_field( 'story_intro', $data['intro'], $id );
		update_field( 'hero_image', $hero_id, $id );
		foreach ( $data['facts'] as $key => $value ) {
			update_field( $key, $value, $id );
		}
		update_field( 'impact_summary', $data['impact'], $id );
		update_field( 'impact_metrics', $data['metrics'], $id );
		update_field(
			'project_sections',
			array(
				array(
					'acf_fc_layout' => 'text',
					'heading'       => 'Listening first',
					'body'          => '<p>The design began with the people who use this place every day. What they asked for is what got built.</p>',
				),
				array(
					'acf_fc_layout' => 'full_image',
					'image'         => $wide_id,
					'caption'       => 'Placeholder photography. Real imagery arrives with the asset pack.',
				),
				array(
					'acf_fc_layout' => 'sketch',
					'image'         => $sketch_id,
					'caption'       => 'The first drawing, made by hand on site.',
				),
			),
			$id
		);
	}
	WP_CLI::log( '  Created: ' . $data['title'] );
}

WP_CLI::log( 'Seeding people...' );
// Names from public directories; titles unpublished, confirm with HKLA.
$people = array(
	array( 'name' => 'Hongjoo Kim', 'role' => 'Founding Principal', 'credentials' => 'ASLA, PLA', 'leadership' => true ),
	array( 'name' => 'David Hanrahan', 'role' => 'Title to confirm', 'credentials' => '', 'leadership' => false ),
	array( 'name' => 'Zhaoheng Chen', 'role' => 'Title to confirm', 'credentials' => '', 'leadership' => false ),
	array( 'name' => 'Minglei Xiong', 'role' => 'Title to confirm', 'credentials' => '', 'leadership' => false ),
	array( 'name' => 'Ken Park', 'role' => 'Title to confirm', 'credentials' => '', 'leadership' => false ),
);
$order = 0;
foreach ( $people as $p ) {
	if ( get_page_by_path( sanitize_title( $p['name'] ), OBJECT, 'person' ) ) {
		continue;
	}
	$id = wp_insert_post(
		array(
			'post_type'   => 'person',
			'post_title'  => $p['name'],
			'post_status' => 'publish',
			'menu_order'  => $order++,
		)
	);
	if ( is_wp_error( $id ) || ! function_exists( 'update_field' ) ) {
		continue;
	}
	$headshot = hkla_seed_image( $p['name'] . ' headshot', 800, 800, 'stone' );
	update_field( 'role_title', $p['role'], $id );
	update_field( 'credentials', $p['credentials'], $id );
	if ( 'Hongjoo Kim' === $p['name'] ) {
		update_field( 'bio', '<p>Hongjoo founded HKLA in 2012. He holds a Master of Landscape Architecture from the Harvard Graduate School of Design and brings nearly three decades of practice to every project.</p>', $id );
	}
	update_field( 'headshot', $headshot, $id );
	update_field( 'is_leadership', $p['leadership'], $id );
}

WP_CLI::log( 'Seeding site settings and page fields...' );
if ( function_exists( 'update_field' ) ) {
	update_field( 'address', "714 West Olympic Blvd, Suite 735\nLos Angeles, CA 90015", 'option' );
	update_field( 'email', 'info@hklainc.com', 'option' );
	update_field( 'footer_line', 'Shared spaces. Shared stories.', 'option' );

	// Home.
	$project_ids = get_posts(
		array(
			'post_type'      => 'project',
			'posts_per_page' => 3,
			'fields'         => 'ids',
		)
	);
	update_field( 'hero_image', hkla_seed_image( 'Home hero', 2400, 1500, 'moss' ), $home_id );
	update_field( 'hero_line', 'Shared spaces. Shared stories.', $home_id );
	update_field( 'mission_statement', 'HKLA is a civic landscape specialist. Grounded in storytelling and a commitment to community, we shape environments for a shared and sustainable future.', $home_id );
	update_field( 'featured_projects', $project_ids, $home_id );
	update_field( 'process_sketch', hkla_seed_image( 'Process sketch', 1600, 1100, 'paper' ), $home_id );
	update_field( 'process_photo', hkla_seed_image( 'Process photo', 1600, 1100, 'moss' ), $home_id );
	update_field( 'process_caption', 'Every project begins as a drawing. The hand finds what the survey cannot.', $home_id );
	update_field(
		'stats',
		array(
			array( 'value' => '2012', 'label' => 'Practicing since' ),
			array( 'value' => '345', 'label' => 'Acres as CSUDH campus landscape architect' ),
			array( 'value' => '5', 'label' => 'Sectors served' ),
		),
		$home_id
	);
	update_field(
		'recognition',
		array(
			array( 'name' => 'DBDA National Award 2022' ),
			array( 'name' => 'ASLA' ),
			array( 'name' => 'CSUDH Campus Master Landscape Architect' ),
		),
		$home_id
	);
	update_field( 'contact_invitation', 'Every client works directly with our senior team.', $home_id );

	// Process (POD).
	update_field( 'framing_statement', 'Before a line is drawn, we find the story.', $process_id );
	update_field( 'framing_body', '<p>From the first conceptual sketch to construction, our commitment runs through Process Oriented Design. POD is how we uncover what a place is trying to say, then build it with purpose for people and the environment.</p>', $process_id );
	update_field(
		'stages',
		array(
			array(
				'title'     => 'Listen',
				'statement' => 'Every place already has people who know it best.',
				'body'      => '<p>We start in the community, not the studio. Workshops, walks, and conversations shape the brief before the design begins.</p>',
				'image'     => hkla_seed_image( 'Stage listen', 1600, 1000, 'stone' ),
				'image_2'   => hkla_seed_image( 'Stage listen detail', 1600, 1000, 'stone' ),
			),
			array(
				'title'     => 'Find the story',
				'statement' => 'A site is a narrative waiting to be read.',
				'body'      => '<p>History, ecology, and daily life give each place its plot. The design grows from what is already true.</p>',
				'image'     => hkla_seed_image( 'Stage story', 1600, 1000, 'earth' ),
				'image_2'   => hkla_seed_image( 'Stage story detail', 1600, 1000, 'earth' ),
			),
			array(
				'title'     => 'Draw by hand',
				'statement' => 'The hand finds what the survey cannot.',
				'body'      => '<p>We draw before we model. Sketching keeps the design honest, human, and open to change.</p>',
				'image'     => hkla_seed_image( 'Stage draw', 1600, 1000, 'paper' ),
				'image_2'   => hkla_seed_image( 'Stage draw detail', 1600, 1000, 'paper' ),
			),
			array(
				'title'     => 'Build together',
				'statement' => 'Construction is a continuation of the conversation.',
				'body'      => '<p>We stay close through documentation and construction, and the community stays involved until opening day.</p>',
				'image'     => hkla_seed_image( 'Stage build', 1600, 1000, 'moss' ),
				'image_2'   => hkla_seed_image( 'Stage build detail', 1600, 1000, 'moss' ),
			),
		),
		$process_id
	);
	update_field(
		'pod_diagrams',
		array(
			array(
				'image'   => hkla_seed_image( 'POD diagram one', 1600, 1000, 'paper' ),
				'caption' => 'Process Oriented Design, from listening to opening day. Final diagram to come.',
			),
		),
		$process_id
	);

	// About.
	update_field( 'belief_statement', 'Urban open space is the pinnacle of democratic practice. Everyone, regardless of origin, color, religion, or interest, can be there together.', $about_id );
	update_field(
		'principles',
		array(
			array( 'title' => 'Collaborative design', 'body' => '<p>The best ideas come from the table with the most seats. Community, client, and collaborators shape the work with us.</p>' ),
			array( 'title' => 'Landscape as art, in context', 'body' => '<p>Every site has a story worth telling. We read the context first and let the art grow out of it.</p>' ),
			array( 'title' => 'Innovative urban ecology', 'body' => '<p>Native planting, stormwater as a resource, materials with second lives. Ecology is a design tool, not a checkbox.</p>' ),
		),
		$about_id
	);
	update_field( 'framing_statement', 'Harmony between people and nature.', $about_id );
	update_field( 'approach_body', '<p>HKLA is a full service landscape architecture practice in Los Angeles, founded in 2012. Three principles guide the work: collaborative design, contextual exploration of landscape as art, and innovative urban ecology. The goal is simple: harmony between people and nature.</p>', $about_id );
	update_field( 'founder_name', 'Hongjoo Kim', $about_id );
	update_field( 'founder_title', 'Founding Principal, ASLA', $about_id );
	update_field( 'founder_bio', '<p>Hongjoo founded HKLA in 2012. He holds a Master of Landscape Architecture from the Harvard Graduate School of Design and brings nearly three decades of practice to public work across Southern California.</p>', $about_id );
	update_field( 'founder_portrait', hkla_seed_image( 'Hongjoo Kim portrait', 1200, 1500, 'stone' ), $about_id );
	update_field( 'boutique_statement', 'Boutique by design. Small enough that every project gets our best people. Experienced enough that nothing surprises us.', $about_id );
	update_field(
		'commitments',
		array(
			array( 'title' => 'Native and drought tolerant planting', 'body' => '<p>Planting that thrives in the climate we are getting, not the one we had.</p>' ),
			array( 'title' => 'Stormwater and groundwater recharge', 'body' => '<p>Ground that drinks the rain. Stormwater is a resource, not a nuisance.</p>' ),
			array( 'title' => 'Recycled and local materials', 'body' => '<p>Materials sourced close to home, with second lives built in.</p>' ),
			array( 'title' => 'Cooling and shade', 'body' => '<p>Design that prevents solar heat gain and gives every visitor somewhere cool to be.</p>' ),
		),
		$about_id
	);
	update_field(
		'recognition',
		array(
			array( 'title' => 'DBDA National Award', 'organization' => 'UCR Student Success Center', 'year' => '2022' ),
			array( 'title' => 'Campus Master Landscape Architect', 'organization' => 'CSU Dominguez Hills', 'year' => 'Since 2018' ),
			array( 'title' => 'Little Saigon Streetscape Feasibility Study', 'organization' => 'City of Westminster', 'year' => '' ),
		),
		$about_id
	);
	update_field( 'contact_invitation', 'Every client works directly with our senior team.', $about_id );

	// Contact.
	update_field( 'statement', 'Every client works directly with our senior team.', $contact_id );
}

WP_CLI::log( 'Seeding news entries...' );
$news_items = array(
	array(
		'title'    => 'UCR Student Success Center wins a DBDA National Award',
		'category' => 'Recognition',
		'date'     => '2022-06-15 09:00:00',
		'excerpt'  => 'The campus landscape that makes the new center belong to Riverside earns national recognition.',
		'image'    => 'UCR Student Success Center hero',
	),
	array(
		'title'    => 'Westminster selects HKLA for the Little Saigon streetscape study',
		'category' => 'News',
		'date'     => '2024-03-01 09:00:00',
		'excerpt'  => 'The City of Westminster taps the studio to lead the Little Saigon streetscape feasibility study.',
		'image'    => 'Stage listen',
	),
);
foreach ( $news_items as $item ) {
	if ( get_page_by_path( sanitize_title( $item['title'] ), OBJECT, 'post' ) ) {
		continue;
	}
	$cat_id = wp_create_category( $item['category'] );
	$pid    = wp_insert_post(
		array(
			'post_type'     => 'post',
			'post_title'    => $item['title'],
			'post_excerpt'  => $item['excerpt'],
			'post_content'  => '<p>' . $item['excerpt'] . ' Full entry to come; confirm details with HKLA.</p>',
			'post_status'   => 'publish',
			'post_date'     => $item['date'],
			'post_category' => array( $cat_id ),
		)
	);
	if ( ! is_wp_error( $pid ) ) {
		$thumb = hkla_seed_image( $item['image'], 1600, 1067, 'earth' );
		if ( $thumb ) {
			set_post_thumbnail( $pid, $thumb );
		}
	}
}

flush_rewrite_rules();
WP_CLI::success( 'Seed complete. Visit the site.' );
