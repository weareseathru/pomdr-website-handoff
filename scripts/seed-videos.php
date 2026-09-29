<?php
/**
 * One-time seeder: move the Videos page's formerly-hardcoded list into the
 * `videos` post type so staff can edit them in wp-admin. Idempotent and safe to
 * re-run: it matches on title and skips videos that already exist, so it will
 * not create duplicates.
 *
 * Run inside WordPress:
 *   wp eval-file scripts/seed-videos.php
 *
 * The source list is the exact set (ids, captions, credits) that lived in
 * page-videos.php, captured from the live site on 2026-07-08.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function pomdr_seed_videos() {
	$videos = array(
		array( 'id' => 'B7RQI4beRZU', 'title' => 'A message to you', 'year' => '2026', 'caption' => 'Every day POMDR staff and volunteers provide the tender loving care and gentle reassurance to dogs in need. Our message to the dogs and to our supporters is that “every little thing is going to be alright.” With your support we can keep that promise over and over again. This video features the song “Three Little Birds” as sung by Clay and Kelsy and by Bob Marley', 'credits' => 'Created by Monica Rua Advisory Council/Co-Founder, 2026' ),
		array( 'id' => 'HVv8rEJjnvs', 'title' => 'The team behind every rescue', 'year' => '2025', 'caption' => 'Behind every dog we help and every person we help is a team of staff, volunteers, donors and adopters who make magic happen! Take a chance on POMDR so we can change the lives of more senior people and senior dogs. This video features the song “Take a Chance on Me” as sung by ABBA.', 'credits' => 'Created by Monica Rua Advisory Council/Co-Founder, 2025' ),
		array( 'url' => 'https://www.byutv.org/7ec2d2ab-f1b1-4bb3-ae80-c42feb4c1294/making-good-peace-of-mind-dog-rescue', 'title' => 'Making Good visits POMDR', 'year' => '2024', 'caption' => 'Kirby from the Making Good TV show travels to California to volunteer with POMDR for several days. As Kirby serves people in need, he learns just how big of an impact the dogs have on their guardians\' wellness.', 'credits' => 'Created by Making Good, July 2024' ),
		array( 'id' => 'RYBCJViyzoA', 'title' => 'A safety net for our community', 'year' => '2024', 'caption' => 'POMDR is a safety net for our community so senior dogs and senior people are not alone. Meet many of the dogs and people we were able to help in 2023. This video features the song “You Will Be Found” as sung by Ben Platt.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2024' ),
		array( 'id' => 'AvBqjCb7nWk', 'title' => 'Stormy\'s rescue', 'year' => '2023', 'caption' => 'POMDR was able to rescue Stormy, a blind Chihuahua mix, after he was caught in a storm drain. Meet many of the other dogs and people we were able to help in 2022. This video features the song “Unchained Melody” as sung by Lykke Li.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2023' ),
		array( 'url' => 'https://www.cnn.com/2022/07/28/us/dog-rescue-aging-california-seniors-cnnheroes/index.html', 'title' => 'CNN Heroes features POMDR', 'year' => '2022', 'caption' => 'POMDR is honored to have our work recognized by CNN. Watch their video that highlights stories from our Rescue, Adoption and Helping Paw Programs as well as POMDR\'s origin story.', 'credits' => 'Created by CNN, 2022' ),
		array( 'id' => 'luw0HHlvkX8', 'title' => 'Meeting the moment', 'year' => '2022', 'caption' => 'POMDR has met the challenges of the many changes facing our community and the planet over the last few years. We are proud to be a nimble organization that can change with the times to best serve the most vulnerable members of our community. Meet some of the dogs, cats and people we helped in 2021. This video features the song “Landslide” by Fleetwood Mac.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2022' ),
		array( 'id' => 'KXY3ANV4V84', 'title' => 'Grandpa Joe\'s story', 'year' => '2022', 'caption' => 'Grandpa Joe came to POMDR in terrible condition. He couldn\'t walk and he was vision impaired, thin, and withdrawn. Named after the character in Charlie and the Chocolate Factory, our Grandpa Joe was able to cash in his golden ticket too. With plenty of TLC, vet care, physical therapy, and by making a connection with his foster family, Grandpa Joe summoned the will to live and thrive. Watch his amazing transformation. This video features the song “(I\'ve Got a) Golden Ticket)” as sung by Jack Alberston & Peter Ostrum.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2022' ),
		array( 'id' => '-6TGhCCp7_A', 'title' => 'More help than ever', 'year' => '2021', 'caption' => 'POMDR was called to help more people and animals than ever in 2020 due to the pandemic, rampant unemployment and financial hardship throughout our community, and devastating California wildfires. With the support of our amazing supporters, we were able to step up and help those in need. Meet Sunny Delight, the last rescue of 2020 and find out how her story went from heartbreaking to heartwarming. This video features the song “Angel by the Wings” by Sia.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2021' ),
		array( 'id' => '44qw2-oGN-g', 'title' => 'Dapper\'s new life', 'year' => '2020', 'caption' => 'Meet Dapper. Dapper endured years of neglect and cruelty. He had been living in a tiny enclosure, outside, for most of his life with little human contact or care. He was finally confiscated by animal control when he was 16 years old and transferred into the care of POMDR. Dapper has endured the toughest of circumstances and persevered. He teaches us all to never give up. His story has a truly happy ending. Watch the video to witness his transformation. This video features the song “Don\'t Give Up” by Peter Gabriel with Kate Bush.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2020' ),
		array( 'id' => '5oLQgv5LHa0', 'title' => 'Shelter in place, together', 'year' => '2020', 'caption' => 'On March 19, 2020, the state of California was ordered to shelter-in-place due to the COVID-19 pandemic. The result was POMDR became busier than we had ever been. Volunteer applications doubled, adoption applications tripled, Helping Paw applications quadrupled and we rose to the challenge. We are grateful to be able to continue to serve those in our community who are most vulnerable. This video features the song “Rise Up” as sung by Andra Day.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2020' ),
		array( 'id' => 'cHtsIDd8qOE', 'title' => 'Building the vet clinic', 'year' => '2020', 'caption' => 'Follow the journey of the building of the POMDR vet clinic as well as Autumn\'s story. Autumn had been hit by a car and had a broken pelvis and a mangled leg. She was one of the first patients treated at the POMDR Boand Clinic. The clinic will enable POMDR to treat thousands of dogs in the years to come. This video features the song “You\'re Still the One” as sung by Shania Twain.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2020' ),
		array( 'id' => 'kZs_WpiGuZo', 'title' => 'Ten years of POMDR', 'year' => '2019', 'caption' => 'Watch a retrospective celebrating POMDR\'s first ten years including milestones, highlights, a thank you to our kind and generous volunteers and supporters, and some special words from the dogs! This video features the song “Kind and Generous” by Natalie Merchant and “Walking on Sunshine” by Katrina and the Waves. It debuted at our Fourth Annual Lucky Dog Gala, in March 2019.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2019' ),
		array( 'id' => 'aW35AiQG55g', 'title' => 'From intake to adoption', 'year' => '2018', 'caption' => 'Follow the stories of several dogs as they come into our program, receive TLC in a foster home, and get adopted by their forever families. Also, meet a few of the dogs who were able to stay with their guardians thanks to our Helping Paw Program. This video features the song “The Story” by Brandi Carlile. It debuted at our Third Annual Lucky Dog Gala, in March 2018.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2018' ),
		array( 'id' => 'm-D8-cr42pk', 'title' => 'Why POMDR was started', 'year' => '2017', 'caption' => 'Hear more about why POMDR was started and what volunteers and supporters are saying about our mission. Includes special commentary by Dina Eastwood and Debra Couch as well as footage of many wonderful senior dogs! This video was produced by Trucksis Enterprises, Inc. Media Productions.', 'credits' => 'Created by TEI, Inc., 2017' ),
		array( 'id' => 'Hw1tse6XLAc', 'title' => 'Lives touched by POMDR', 'year' => '2017', 'caption' => 'Meet several of the dogs and people whose lives were touched by POMDR. This video highlights many of the dog who were surrendered to POMDR and the dogs POMDR brought in from animal shelters. You will also meet several Helping Paw clients who were able to keep their pets with a little assistance from POMDR. This heartwarming video features the song “Lean on Me” by Bill Withers. It debuted at our Second Annual Lucky Dog Gala, in March 2017.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2017' ),
		array( 'id' => '8PJiCjgQqqM', 'title' => 'Lucky and Patches', 'year' => '2016', 'caption' => 'Meet Lucky and Patches, two of the Lucky Dogs who came to POMDR, as well as many other dogs who found their happy homes. This heartwarming video features the song “Fix You” by Coldplay. It was shown at our First Annual Lucky Dog Gala, in March 2016.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2016' ),
		array( 'id' => 'P_uVOvoMUo4', 'title' => 'As told by the dogs', 'year' => '2014', 'caption' => 'Learn about POMDR and what we do as told by the dogs and people we\'ve helped and some of the people who make it all happen. This fun video features the song “I\'m a Believer” by Neil Diamond. It was first shown at our Ulti-Mutt Appetizer Party, in December 2014.', 'credits' => 'Created by Monica Rua Board President/Co-Founder, 2014' ),
	);

	// Index existing videos by lowercased title so re-runs do not duplicate.
	$existing = array();
	foreach ( get_posts( array( 'post_type' => 'videos', 'post_status' => 'any', 'posts_per_page' => -1 ) ) as $p ) {
		$existing[ strtolower( trim( $p->post_title ) ) ] = $p->ID;
	}

	$created = 0; $skipped = 0;
	foreach ( $videos as $i => $v ) {
		$key = strtolower( trim( $v['title'] ) );
		if ( isset( $existing[ $key ] ) ) { $skipped++; continue; }

		$id = wp_insert_post( array(
			'post_type'   => 'videos',
			'post_status' => 'publish',
			'post_title'  => $v['title'],
			'menu_order'  => $i, // preserves the original newest-first order as a tiebreak
		) );
		if ( is_wp_error( $id ) || ! $id ) { continue; }

		update_field( 'youtube_id',    isset( $v['id'] ) ? $v['id'] : '', $id );
		update_field( 'external_url',  isset( $v['url'] ) ? $v['url'] : '', $id );
		update_field( 'video_year',    $v['year'], $id );
		update_field( 'video_caption', $v['caption'], $id );
		update_field( 'video_credits', $v['credits'], $id );
		$created++;
	}

	return array( 'created' => $created, 'skipped' => $skipped, 'total' => count( $videos ) );
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	$r = pomdr_seed_videos();
	WP_CLI::log( json_encode( $r ) );
}
