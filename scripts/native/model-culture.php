<?php
/**
 * Content model + D4 emission for the Culture page (pilot, static class).
 * Copy is verbatim from page-culture.php (the sidecar is the copy source of
 * truth). Returns the full D4 shortcode document for the page body.
 */

defined( 'ABSPATH' ) || exit;

function pom_native_culture() {

	/* Header */
	$doc = pom_emit_page_header( array(
		'headline'  => 'Our Culture',
		'narrative' => 'Kindness, run like a <em>professional</em>.',
		'lead'      => 'Behind every rescued dog is a team that shows up with warmth, honesty, and care. This is what it feels like to work and volunteer at Peace of Mind Dog Rescue.',
		'ctas'      => array(
			array( 'text' => 'Join our team', 'url' => '/jobs/', 'class' => 'btn-purple' ),
			array( 'text' => 'Volunteer with us', 'url' => '/volunteer/', 'class' => 'btn-outline' ),
		),
		'media'     => array(
			'src' => pom_theme_asset( 'assets/images/hero-volunteer.jpg' ),
			'alt' => 'POMDR volunteers together at an event',
		),
	) );

	/* Values section: intro text + 7 value cards in a 2-col grid. The card
	   grid keeps its design-system markup inside one text module (the
	   stylesheet owns the layout); each card is plain HTML the VB text
	   editor can edit. */
	$values = array(
		array( 'Feedback', 'To be open to positive and constructive feedback from supervisors, co-workers, volunteer, and general public in an effort to take advantage of all opportunities to grow and improve.' ),
		array( 'Best Practices', 'To continually strive to explore best practices and ways to improve our programs, policies, procedures and organization effectiveness.' ),
		array( 'Positive Attitude', 'To maintain a solution oriented, optimistic outlook on our ability to make a difference.' ),
		array( 'Solution-driven', 'To strive for creative solutions to any challenges that we may face.' ),
		array( 'Responsibility', 'To agree that it is everyone&#8217;s responsibility to create a positive experience for volunteers, guardian surrenders, donors, adopters and the general public whether or not what they need is in our job description.' ),
		array( 'Professional', 'To hold ourselves to the utmost standards of professionalism in appearance and attitude at all times.' ),
		array( 'Volunteer and Donor Appreciation', 'To express our sincere appreciation for our volunteers and donors in every interaction.' ),
	);

	$cards = '';
	foreach ( $values as $i => $v ) {
		$n      = $i + 1;
		$cards .= '<div class="value-card"><div class="vk">' . $n . '</div><h3>' . $v[0] . '</h3><p>' . $v[1] . '</p></div>';
	}

	$values_intro = '<span class="eyebrow purple">What we believe</span>'
		. '<h2 class="section-title">Values we <em>live</em>, not just post.</h2>'
		. '<p class="section-lead">Since 2009, POMDR has grown from a small group of volunteers into a trusted Central Coast institution. The way we treat dogs, adopters, donors, and each other has stayed the same the whole way through.</p>';

	$doc .= pom_d4_section(
		array( 'admin_label' => 'Values', 'module_class' => 'section' ),
		pom_d4_row( array( 'admin_label' => 'Values intro' ),
			pom_d4_column( '4_4', array(),
				pom_d4_text( array( 'admin_label' => 'Values intro' ), $values_intro )
				. pom_d4_text( array( 'admin_label' => 'Value cards', 'module_class' => 'values-grid-wrap' ), '<div class="values-grid">' . $cards . '</div>' )
			)
		)
	);

	/* People band: two columns on the cream wash. */
	$people_left = '<span class="eyebrow purple">The people</span>'
		. '<h2>A team that <em>cares for each other.</em></h2>'
		. '<p>Our work asks a lot, emotionally and practically. So we build a workplace that gives back, flexible, supportive, and grounded in a shared purpose everyone can feel.</p>'
		. '<p>Staff and volunteers come from every walk of life. What they have in common is steadiness, a sense of humor on the hard days, and a deep belief that older dogs and older people deserve more than they often get.</p>'
		. '<p>Whether you join as an employee or give a few hours a week, you will find colleagues who notice your effort and have your back.</p>';

	$people_right = '<div class="belong-card"><span class="eyebrow purple">Ways to belong</span><ul class="belong-list">'
		. '<li><strong>Staff roles.</strong> Mission-driven work with a team that values balance and respect.</li>'
		. '<li><strong>Volunteers.</strong> Walk dogs, foster, drive, photograph, or lend a skill. Every hour matters.</li>'
		. '<li><strong>Fosters.</strong> Open your home and give a senior dog comfort while we find their person.</li>'
		. '<li><strong>Donors and sponsors.</strong> Fund the care that makes all of this possible.</li>'
		. '</ul></div>';

	$doc .= pom_d4_section(
		array( 'admin_label' => 'The people', 'module_class' => 'section section-cream' ),
		pom_d4_row( array( 'admin_label' => 'People band', 'module_class' => 'people-band' ),
			pom_d4_column( '1_2', array(),
				pom_d4_text( array( 'admin_label' => 'Team story' ), $people_left )
				. pom_d4_button( 'About our organization', '/about/', array( 'module_class' => 'btn btn-purple', 'admin_label' => 'Button: About' ) )
			)
			. pom_d4_column( '1_2', array(),
				pom_d4_text( array( 'admin_label' => 'Ways to belong' ), $people_right )
			)
		)
	);

	/* Why it works */
	$why = '<span class="eyebrow purple">Why it works</span>'
		. '<h2 class="section-title">Warmth and <em>rigor</em>, in equal measure.</h2>'
		. '<p class="section-lead">We are tender with the dogs and disciplined with the work. Medical records are thorough, applications are read personally, and follow-up is real. A loving culture and a well-run rescue are not opposites. They depend on each other.</p>'
		. '<p class="lead">If that sounds like the kind of place you want to spend your time, there is a seat for you here.</p>';

	$doc .= pom_d4_section(
		array( 'admin_label' => 'Why it works', 'module_class' => 'section' ),
		pom_d4_row( array( 'admin_label' => 'Why it works' ),
			pom_d4_column( '4_4', array(), pom_d4_text( array( 'admin_label' => 'Why it works' ), $why ) )
		)
	);

	/* CTA strip */
	$cta_head = '<h2 class="serif">Bring your <em>good heart</em> to work.</h2>';
	$cta_foot = '<p class="cta-contact">Reach us at <a href="tel:8317189122">(831) 718-9122</a> or <a href="mailto:info@pomdr.org">info@pomdr.org</a>.</p>';

	$doc .= pom_d4_section(
		array( 'admin_label' => 'CTA strip', 'module_class' => 'cta-strip cta-strip-purple' ),
		pom_d4_row( array( 'admin_label' => 'CTA' ),
			pom_d4_column( '4_4', array(),
				pom_d4_text( array( 'admin_label' => 'CTA heading' ), $cta_head )
				. pom_d4_button( 'Join our team', '/jobs/', array( 'module_class' => 'btn btn-purple', 'admin_label' => 'Button: Jobs' ) )
				. pom_d4_button( 'Volunteer with us', '/volunteer/', array( 'module_class' => 'btn btn-outline', 'admin_label' => 'Button: Volunteer' ) )
				. pom_d4_text( array( 'admin_label' => 'Contact line' ), $cta_foot )
			)
		)
	);

	return $doc;
}
