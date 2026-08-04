<?php
/**
 * HKLA seed content. Run with:
 *   wp eval-file bin/seed-content.php
 *
 * Creates the brand pages (with templates assigned), three sample projects
 * with placeholder imagery and copy in the brand voice, sample people, and
 * site settings. Idempotent: existing items are left alone.
 *
 * Placeholder images are generated locally with GD in the palette's warm
 * tones, so no network access is needed. Replace with real photography from
 * the asset pack.
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
$process_id  = hkla_seed_page( 'Process', 'process', 'page-process.php' );
$people_id   = hkla_seed_page( 'People', 'people', 'page-people.php' );
$purpose_id  = hkla_seed_page( 'Purpose', 'purpose', 'page-purpose.php' );
$careers_id  = hkla_seed_page( 'Careers', 'careers', 'page-careers.php' );
$contact_id  = hkla_seed_page( 'Contact', 'contact', 'page-contact.php' );
$journal_id  = hkla_seed_page( 'Journal', 'journal' );

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home_id );
update_option( 'page_for_posts', $journal_id );

/**
 * Three sample projects. Copy is placeholder, written in the brand voice:
 * short declarative headlines, no jargon, no hedging, no em dashes.
 */
$projects = array(
	array(
		'title'    => 'Wilmington Greenbelt',
		'sector'   => 'civic-parks',
		'tone'     => 'moss',
		'headline' => 'A freeway buffer became a front yard.',
		'intro'    => "<p>For decades, the neighborhood ended at a fence. Nine acres of leftover land sat between the houses and the harbor, holding nothing but weeds and noise.</p><p>We listened to the people who lived with it. They did not ask for a landmark. They asked for shade, for a place to walk, for somewhere their kids could be outside. The design answers them.</p>",
		'facts'    => array(
			'client'     => 'City of Los Angeles',
			'location'   => 'Wilmington, Los Angeles',
			'size'       => '9.1 acres',
			'completion' => '2025',
			'services'   => 'Landscape architecture, community engagement, planting design',
		),
		'impact'   => 'A neighborhood that had the least park space in the harbor area now has a green spine it can walk end to end.',
		'metrics'  => array(
			array( 'value' => '9.1', 'label' => 'Acres of new open space' ),
			array( 'value' => '420', 'label' => 'New trees planted' ),
			array( 'value' => '12', 'label' => 'Community workshops held' ),
		),
	),
	array(
		'title'    => 'Esperanza Elementary Schoolyard',
		'sector'   => 'education',
		'tone'     => 'earth',
		'headline' => 'The asphalt came up. The kids came out.',
		'intro'    => "<p>A struggling tree, overhead flight paths, chain-link fencing. Where others saw problems, we saw potential.</p><p>Working with teachers, students, and families, we traded two acres of asphalt for shade, gardens, and ground that absorbs the rain instead of shedding it. Recess looks different now. So does science class.</p>",
		'facts'    => array(
			'client'     => 'Los Angeles Unified School District',
			'location'   => 'Westlake, Los Angeles',
			'size'       => '2.3 acres',
			'completion' => '2024',
			'services'   => 'Landscape architecture, schoolyard greening, stormwater design',
		),
		'impact'   => 'One of the hottest schoolyards in the district is now one of its coolest, and every classroom uses it.',
		'metrics'  => array(
			array( 'value' => '85%', 'label' => 'Asphalt removed' ),
			array( 'value' => '14°F', 'label' => 'Surface temperature drop' ),
		),
	),
	array(
		'title'    => 'Harbor Wellness Campus',
		'sector'   => 'healthcare',
		'tone'     => 'stone',
		'headline' => 'A garden that works as hard as the clinic.',
		'intro'    => "<p>Healing does not stop at the building door. The county asked for landscape around a new wellness center. We gave them landscape that is part of the treatment.</p><p>Quiet courtyards for counseling. A walking loop measured for physical therapy. Planting chosen for calm, for shade, and for the birds that patients name from the waiting room.</p>",
		'facts'    => array(
			'client'     => 'County of Los Angeles',
			'location'   => 'San Pedro, Los Angeles',
			'size'       => '4.2 acres',
			'completion' => 'In progress',
			'services'   => 'Landscape architecture, therapeutic gardens, native planting',
		),
		'impact'   => 'The campus treats the grounds as clinical space, and patients spend part of every visit outdoors.',
		'metrics'  => array(
			array( 'value' => '100%', 'label' => 'Native or climate-adapted planting' ),
			array( 'value' => '0.6 mi', 'label' => 'Accessible walking loop' ),
		),
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
					'body'          => '<p>The design began in folding chairs, not at a drafting table. What the community asked for is what got built.</p>',
				),
				array(
					'acf_fc_layout' => 'full_image',
					'image'         => $wide_id,
					'caption'       => 'The site, before and after listening.',
				),
				array(
					'acf_fc_layout' => 'sketch',
					'image'         => $sketch_id,
					'caption'       => 'The first drawing, made by hand on site.',
				),
				array(
					'acf_fc_layout' => 'voice',
					'quote'         => 'They kept showing up. They kept asking us what we wanted. And then they built it.',
					'name'          => 'A neighbor',
					'role'          => 'Community member',
				),
			),
			$id
		);
	}
	WP_CLI::log( '  Created: ' . $data['title'] );
}

WP_CLI::log( 'Seeding people...' );
$people = array(
	array( 'name' => 'Hongjoo Kim', 'role' => 'Founding Principal', 'credentials' => 'PLA, ASLA', 'leadership' => true ),
	array( 'name' => 'Maria Delgado', 'role' => 'Senior Landscape Architect', 'credentials' => 'PLA', 'leadership' => true ),
	array( 'name' => 'James Park', 'role' => 'Project Designer', 'credentials' => '', 'leadership' => false ),
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
	update_field( 'bio', '<p>Placeholder bio. ' . $p['name'] . ' shapes public places with care, craft, and a bias for listening.</p>', $id );
	update_field( 'headshot', $headshot, $id );
	update_field( 'is_leadership', $p['leadership'], $id );
}

WP_CLI::log( 'Seeding site settings and home fields...' );
if ( function_exists( 'update_field' ) ) {
	update_field( 'address', "714 West Olympic Blvd, Suite 735\nLos Angeles, CA 90015", 'option' );
	update_field( 'email', 'hello@hklainc.com', 'option' );
	update_field( 'footer_line', 'Shared spaces. Shared stories.', 'option' );

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
	update_field( 'quote_text', 'They designed with us, not for us.', $home_id );
	update_field( 'quote_name', 'Community partner', $home_id );
	update_field(
		'stats',
		array(
			array( 'value' => '60+', 'label' => 'Public projects' ),
			array( 'value' => '15', 'label' => 'Acres of asphalt removed' ),
			array( 'value' => '5', 'label' => 'Sectors served' ),
		),
		$home_id
	);
	update_field( 'contact_invitation', 'Every client works directly with our senior team.', $home_id );

	// Process page stages.
	update_field( 'framing_statement', 'Before a line is drawn, we find the story.', $process_id );
	update_field(
		'stages',
		array(
			array(
				'title'     => 'Listen',
				'statement' => 'Every place already has people who know it best.',
				'body'      => '<p>We start in the community, not the studio. Workshops, walks, and conversations shape the brief before the design begins.</p>',
				'image'     => hkla_seed_image( 'Stage listen', 1600, 1000, 'stone' ),
			),
			array(
				'title'     => 'Find the story',
				'statement' => 'A site is a narrative waiting to be read.',
				'body'      => '<p>History, ecology, and daily life give each place its plot. The design grows from what is already true.</p>',
				'image'     => hkla_seed_image( 'Stage story', 1600, 1000, 'earth' ),
			),
			array(
				'title'     => 'Draw by hand',
				'statement' => 'The hand finds what the survey cannot.',
				'body'      => '<p>We draw before we model. Sketching keeps the design honest, human, and open to change.</p>',
				'image'     => hkla_seed_image( 'Stage draw', 1600, 1000, 'paper' ),
			),
			array(
				'title'     => 'Build together',
				'statement' => 'Construction is a continuation of the conversation.',
				'body'      => '<p>We stay close through documentation and construction, and the community stays involved until opening day.</p>',
				'image'     => hkla_seed_image( 'Stage build', 1600, 1000, 'moss' ),
			),
		),
		$process_id
	);

	// People page.
	update_field( 'framing_statement', 'The people behind the places.', $people_id );
	update_field( 'founder_name', 'Hongjoo Kim', $people_id );
	update_field( 'founder_title', 'Founding Principal', $people_id );
	update_field( 'founder_bio', '<p>Placeholder bio. Hongjoo founded HKLA to make civic landscape a specialty, not a sideline. Three decades of public work stand behind the studio.</p>', $people_id );
	update_field( 'founder_portrait', hkla_seed_image( 'Hongjoo Kim portrait', 1200, 1500, 'stone' ), $people_id );
	update_field( 'boutique_statement', 'Boutique by design. Small enough that every project gets our best people. Experienced enough that nothing surprises us.', $people_id );

	// Purpose page.
	update_field( 'framing_headline', 'Design as a civic act.', $purpose_id );
	update_field( 'framing_body', '<p>Public landscape is infrastructure for shared life. We hold every project to commitments that outlast the ribbon cutting.</p>', $purpose_id );
	update_field(
		'commitments',
		array(
			array( 'title' => 'Climate resilience', 'body' => '<p>Shade, cooling, and planting that thrives in the climate we are getting, not the one we had.</p>' ),
			array( 'title' => 'Water management', 'body' => '<p>Ground that drinks the rain. Stormwater is a resource, not a nuisance.</p>' ),
			array( 'title' => 'Habitat support', 'body' => '<p>Native planting that feeds birds, pollinators, and the soil itself.</p>' ),
			array( 'title' => 'Long-term maintainability', 'body' => '<p>Designs that public crews can actually keep beautiful, year after year.</p>' ),
		),
		$purpose_id
	);
	update_field( 'outcomes', '<p>Cooler schoolyards. Walkable green corridors. Clinics with gardens that work. The outcomes we count are the ones neighbors feel.</p>', $purpose_id );
	update_field(
		'numbers',
		array(
			array( 'value' => '60+', 'label' => 'Public projects delivered' ),
			array( 'value' => '1M+', 'label' => 'Annual visitors to our places' ),
		),
		$purpose_id
	);

	// Careers and contact.
	update_field( 'culture_statement', 'Do the best work of your career on places everyone can use.', $careers_id );
	update_field( 'speculative_text', 'If you believe public space is worth a career, introduce yourself.', $careers_id );
	update_field( 'statement', 'Every client works directly with our senior team.', $contact_id );
}

flush_rewrite_rules();
WP_CLI::success( 'Seed complete. Visit the site.' );
