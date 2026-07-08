<?php
/**
 * Front page (homepage): the prototype homepage (hero + sections), ported 1:1.
 * Chrome (action bar / nav / tagline) is injected by inc/chrome.php; the footer
 * is Divi's. The adoptable-dogs row is the [pet_home] shortcode so it stays
 * backend-driven (staff edit Pets in wp-admin). Homepage layout is
 * developer-managed per the project's editability model.
 */
get_header();
$img = get_stylesheet_directory_uri() . '/assets/images';
?>
<main id="main-content">

  <!-- ===== HERO (rotating, side-arrow navigation) ===== -->
  <section class="hero" id="hero">
    <div class="hero-slide active">
      <div class="hero-image"><picture><source type="image/webp" srcset="<?php echo $img; ?>/hero-adopt.webp"><img src="<?php echo $img; ?>/hero-adopt.jpeg" alt="A gray-muzzled senior dog at rest in soft afternoon light" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 30%"></picture></div>
      <div class="hero-scrim"></div>
    </div>
    <div class="hero-slide">
      <div class="hero-image"><picture><source type="image/webp" srcset="<?php echo $img; ?>/hero-helping-paw.webp"><img src="<?php echo $img; ?>/hero-helping-paw.jpeg" alt="A volunteer walking a small dog alongside an older guardian on a quiet street" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 35%"></picture></div>
      <div class="hero-scrim"></div>
    </div>
    <div class="hero-slide">
      <div class="hero-image"><picture><source type="image/webp" srcset="<?php echo $img; ?>/hero-mission.webp"><img src="<?php echo $img; ?>/hero-mission.jpeg" alt="Two senior dogs in a sunny doorway, comfortable and at home" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 30%"></picture></div>
      <div class="hero-scrim"></div>
    </div>
    <div class="hero-slide">
      <div class="hero-image"><picture><source type="image/webp" srcset="<?php echo $img; ?>/hero-volunteer.webp"><img src="<?php echo $img; ?>/hero-volunteer.jpg" alt="Volunteers and dogs gathered for a morning at the Pacific Grove rescue center" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 40%"></picture></div>
      <div class="hero-scrim"></div>
    </div>

    <button class="hero-arrow prev" id="hero-prev" aria-label="Previous slide"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M15 18l-6-6 6-6"/></svg></button>
    <button class="hero-arrow next" id="hero-next" aria-label="Next slide"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M9 6l6 6-6 6"/></svg></button>

    <div class="hero-content">
      <div class="container">
        <div class="hero-card">
          <h1 class="hero-title" id="hero-title">
            <div>Senior dogs deserve</div>
            <div>a <em>soft place</em></div>
            <div>to land.</div>
          </h1>
          <div class="hero-ctas" id="hero-ctas">
            <a href="/adopt/" class="btn btn-primary">Adopt <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a>
            <a href="/foster/" class="btn btn-purple">Foster</a>
            <a href="/adopt/" class="btn btn-ghost">See all dogs</a>
          </div>
        </div>
      </div>
    </div>

    <div class="hero-controls">
      <div class="container">
        <div class="hero-progress" id="hero-progress">
          <button class="active" data-i="0" aria-label="Slide 1"><span class="bar"></span></button>
          <button data-i="1" aria-label="Slide 2"><span class="bar"></span></button>
          <button data-i="2" aria-label="Slide 3"><span class="bar"></span></button>
          <button data-i="3" aria-label="Slide 4"><span class="bar"></span></button>
          <button id="hero-pause" aria-label="Pause slideshow"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M6 4h4v16H6zM14 4h4v16h-4z"/></svg></button>
        </div>
      </div>
    </div>

  </section>

  <!-- ===== PROMO BANNER (staff-toggleable in WP: adoption promos, fundraisers) ===== -->
  <aside class="promo-banner" id="promo-banner" role="region" aria-label="Announcement">
    <div class="container promo-inner">
      <div class="promo-text">
        <span class="promo-label">
          <svg class="promo-paw" viewBox="0 0 64 64" aria-hidden="true"><circle cx="32" cy="32" r="32" fill="#632F88"/><g fill="#fff"><ellipse cx="14.5" cy="30" rx="5.2" ry="7" transform="rotate(-24 14.5 30)"/><ellipse cx="25" cy="19.5" rx="5.4" ry="7.4" transform="rotate(-8 25 19.5)"/><ellipse cx="39" cy="19.5" rx="5.4" ry="7.4" transform="rotate(8 39 19.5)"/><ellipse cx="49.5" cy="30" rx="5.2" ry="7" transform="rotate(24 49.5 30)"/><path d="M32 36.9 C29.3 33.8 25.3 32.7 22 34.1 C16.6 36.5 13.9 42.6 16.1 48.2 C17.8 52.7 22 56 26.8 56.9 C28.7 57.3 30.5 56.3 32 54.8 C33.5 56.3 35.3 57.3 37.2 56.9 C42 56 46.2 52.7 47.9 48.2 C50.1 42.6 47.4 36.5 42 34.1 C38.7 32.7 34.7 33.8 32 36.9 Z"/></g></svg>
          Adoption Promotion
        </span>
        <p class="promo-message">This month, senior dog adoption fees are waived for adopters 65 and older. Give a gray muzzle a soft place to land.</p>
      </div>
      <div class="promo-actions">
        <a href="/adopt/" class="btn btn-light">See adoptable dogs</a>
        <button class="promo-dismiss" type="button" aria-label="Dismiss announcement">
          <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
      </div>
    </div>
  </aside>
  <script>
    (function(){ var b=document.getElementById('promo-banner'); if(!b) return;
      if(localStorage.getItem('pomdrPromoDismissed')==='1') b.classList.add('dismissed');
      var x=b.querySelector('.promo-dismiss');
      if(x) x.addEventListener('click', function(){ b.classList.add('dismissed'); try{localStorage.setItem('pomdrPromoDismissed','1');}catch(e){} });
    })();
  </script>

  <!-- ===== PILLARS (Adopt / Donate / Volunteer) ===== -->
  <section class="section pillars">
    <div class="container">
      <div class="pillars-grid">
        <div class="reveal"><a class="pillar" href="/adopt/">
          <div style="position:absolute;inset:0;z-index:0;overflow:hidden"><picture><source type="image/webp" srcset="<?php echo $img; ?>/pillar-adopt.webp"><img src="<?php echo $img; ?>/pillar-adopt.jpeg" alt="" role="presentation" style="width:100%;height:100%;object-fit:cover;object-position:center"></picture></div>
          <div style="position:absolute;inset:0;background:linear-gradient(180deg, transparent 30%, rgba(22,32,43,0.85) 100%);z-index:1"></div>
          <div style="position:relative;z-index:2">
            <h3 class="pillar-label">Adopt</h3>
            <p>Match with a senior pup whose quiet mornings suit yours.</p>
            <span class="pillar-cta">Learn more <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
          </div>
        </a></div>
        <div class="reveal"><a class="pillar" href="/donate/">
          <div style="position:absolute;inset:0;z-index:0;overflow:hidden"><picture><source type="image/webp" srcset="<?php echo $img; ?>/pillar-donate.webp"><img src="<?php echo $img; ?>/pillar-donate.jpeg" alt="" role="presentation" style="width:100%;height:100%;object-fit:cover;object-position:center"></picture></div>
          <div style="position:absolute;inset:0;background:linear-gradient(180deg, transparent 30%, rgba(22,32,43,0.85) 100%);z-index:1"></div>
          <div style="position:relative;z-index:2">
            <h3 class="pillar-label">Donate</h3>
            <p>Your gift funds medical care, foster stipends and a lifetime promise.</p>
            <span class="pillar-cta">Learn more <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
          </div>
        </a></div>
        <div class="reveal"><a class="pillar" href="/volunteer/">
          <div style="position:absolute;inset:0;z-index:0;overflow:hidden"><picture><source type="image/webp" srcset="<?php echo $img; ?>/pillar-volunteer.webp"><img src="<?php echo $img; ?>/pillar-volunteer.jpeg" alt="" role="presentation" style="width:100%;height:100%;object-fit:cover;object-position:center"></picture></div>
          <div style="position:absolute;inset:0;background:linear-gradient(180deg, transparent 30%, rgba(22,32,43,0.85) 100%);z-index:1"></div>
          <div style="position:relative;z-index:2">
            <h3 class="pillar-label">Volunteer</h3>
            <p>Foster, transport, write bios. Every hour makes a difference.</p>
            <span class="pillar-cta">Learn more <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
          </div>
        </a></div>
      </div>
    </div>
  </section>

  <!-- ===== MISSION + PROGRAMS ===== -->
  <section class="section programs" id="mission">
    <div class="container">
      <div class="reveal">
        <div class="section-header">
          <span class="eyebrow" style="color:var(--purple)">Our Mission</span>
          <h2 class="section-title">Programs for senior dogs <em>and senior people.</em></h2>
        </div>
      </div>
      <div class="programs-grid">
        <div class="reveal"><div class="program-card">
          <div class="program-icon"><svg viewBox="0 0 24 24"><path d="M12 21s-8-5.5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 11-8 11z"/></svg></div>
          <h3>Intake and Adoptions</h3>
          <p>We meet each dog where they are, find the right foster home, and match them with a person for the rest of their days.</p>
          <ul><li>Adoptable dogs</li><li>Courtesy listings</li><li>Adoption process</li><li>Adoption events</li></ul>
          <a href="/adopt/" class="learn">Explore Intake and Adoptions</a>
        </div></div>
        <div class="reveal"><div class="program-card">
          <div class="program-icon"><svg viewBox="0 0 24 24"><circle cx="5" cy="9" r="2"/><circle cx="12" cy="5" r="2"/><circle cx="19" cy="9" r="2"/><path d="M7 17c0-3 2-5 5-5s5 2 5 5-2 4-5 4-5-1-5-4z"/></svg></div>
          <h3>Helping Paw</h3>
          <p>When a guardian is struggling, we step in with dog walking, rides to the vet, financial help, and temporary foster care, so families can stay together.</p>
          <ul><li>Walking brigade</li><li>Financial assistance</li><li>Temporary fosters</li></ul>
          <a href="/helping-paw/" class="learn">Explore Helping Paw</a>
        </div></div>
        <div class="reveal"><div class="program-card">
          <div class="program-icon"><svg viewBox="0 0 24 24"><path d="M12 2l3 6 6 1-4.5 4 1 6-5.5-3-5.5 3 1-6L3 9l6-1 3-6z"/></svg></div>
          <h3>Perpetual Care</h3>
          <p>Plan ahead with us, and we promise to love and care for your dog if you no longer can. It is a promise, not a program.</p>
          <ul><li>Placing your dog</li><li>Lifetime commitment</li><li>Planning ahead</li></ul>
          <a href="/perpetual-care-program/" class="learn">Explore Perpetual Care</a>
        </div></div>
      </div>
    </div>
  </section>

  <!-- ===== ADOPTABLES (two rows of three) ===== -->
  <section class="section adoptables" id="adopt">
    <div class="container">
      <div class="reveal">
        <div class="section-header">
          <span class="eyebrow">Adoptable Dogs</span>
          <h2 class="section-title">Find your new <em>old best friend.</em></h2>
        </div>
      </div>
      <?php echo do_shortcode("[pet_home]"); ?>
    </div>
  </section>

  <!-- ===== VIDEO ===== -->
  <section class="section video-section">
    <div class="container">
      <div class="reveal">
        <div class="section-header">
          <span class="eyebrow">Watch</span>
          <h2 class="section-title">POMDR <em>Videos.</em></h2>
        </div>
      </div>
      <!-- Real POMDR videos (ids from the live videos page). Click a card and
           it becomes the featured player; the others slide into a side rail.
           Posters come from YouTube (ytimg is an allowlisted host). -->
      <div class="video-grid reveal" id="video-grid">
        <div class="video-card" data-youtube-id="B7RQI4beRZU" data-title="A message to you">
          <img class="poster" src="https://i.ytimg.com/vi/B7RQI4beRZU/hqdefault.jpg" alt="A message to you from POMDR" loading="lazy">
          <div class="scrim"></div>
          <div class="play"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></div>
          <div class="v-title">A message to you</div>
        </div>
        <div class="video-card" data-youtube-id="HVv8rEJjnvs" data-title="The team behind every rescue">
          <img class="poster" src="https://i.ytimg.com/vi/HVv8rEJjnvs/hqdefault.jpg" alt="The POMDR team at work" loading="lazy">
          <div class="scrim"></div>
          <div class="play"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></div>
          <div class="v-title">The team behind every rescue</div>
        </div>
        <div class="video-card" data-youtube-id="KXY3ANV4V84" data-title="Grandpa Joe's story">
          <img class="poster" src="https://i.ytimg.com/vi/KXY3ANV4V84/hqdefault.jpg" alt="Grandpa Joe, a senior dog rescued by POMDR" loading="lazy">
          <div class="scrim"></div>
          <div class="play"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></div>
          <div class="v-title">Grandpa Joe's story</div>
        </div>
        <a class="video-more" href="/videos/">
          <span>See all videos</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="20" height="20"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </section>

  <!-- ===== IMPACT ===== -->
  <section class="impact" aria-labelledby="impact-title">
    <div class="container">
      <div class="reveal">
        <div class="section-header">
          <span class="eyebrow">POMDR By the Numbers</span>
          <h2 class="section-title" id="impact-title">Senior dogs and senior people, <em>helped together.</em></h2>
        </div>
      </div>
      <!-- NOTE: stat values are PLACEHOLDERS pending real totals from POMDR.
           In WP these become editable (ACF options) or dynamic counts. -->
      <div class="stats-3 reveal">
        <div class="stat-block">
          <div class="num">250<span class="sym">+</span></div>
          <div class="lbl">Seniors supported through Helping Paw</div>
        </div>
        <div class="stat-block">
          <div class="num">3,200<span class="sym">+</span></div>
          <div class="lbl">Dogs adopted into loving homes</div>
        </div>
        <div class="stat-block">
          <div class="num">1,800<span class="sym">+</span></div>
          <div class="lbl">Active volunteers</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== HAPPY TAILS ===== -->
  <section class="tails">
    <div class="container">
      <div class="reveal">
        <div class="section-header">
          <span class="eyebrow">Testimonials</span>
          <h2 class="section-title">Hear from <em>our clients.</em></h2>
        </div>
      </div>
      <div class="tails-grid">
        <div class="reveal"><div class="tail-card">
          <div class="quote-mark">"</div>
          <blockquote>Rosie walked into our home unsure, and two weeks later she was napping at my feet like she had always been here. Thank you for trusting us with her golden years.</blockquote>
          <div class="person">
            <div class="av" style="background:linear-gradient(160deg,var(--purple-100),var(--purple));display:grid;place-items:center;color:#fff;font-family:var(--font-serif);font-style:italic;font-size:22px">R</div>
            <div><strong>The Alvarez Family</strong><span>adopted Rosie · Pacific Grove, CA</span></div>
          </div>
        </div></div>
        <div class="reveal"><div class="tail-card">
          <div class="quote-mark">"</div>
          <blockquote>They did not just match us with a dog. They matched us with a companion who understood quiet mornings.</blockquote>
          <div class="person">
            <div class="av" style="background:linear-gradient(160deg,var(--blue-100),var(--blue));display:grid;place-items:center;color:#fff;font-family:var(--font-serif);font-style:italic;font-size:22px">F</div>
            <div><strong>Eleanor W.</strong><span>adopted Finn · Carmel</span></div>
          </div>
        </div></div>
        <div class="reveal"><div class="tail-card">
          <div class="quote-mark">"</div>
          <blockquote>POMDR stayed in touch long after adoption day. That is what a lifetime commitment looks like.</blockquote>
          <div class="person">
            <div class="av" style="background:linear-gradient(160deg,#e8d5c4,#8b5a2b);display:grid;place-items:center;color:#fff;font-family:var(--font-serif);font-style:italic;font-size:22px">B</div>
            <div><strong>David and Marta</strong><span>adopted Biscuit · Santa Cruz</span></div>
          </div>
        </div></div>
      </div>
      <div class="tails-more">
        <a href="/testimonials/" class="btn btn-purple">See more stories <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a>
      </div>
    </div>
  </section>

  <!-- ===== EVENTS ===== -->
  <section class="events">
    <div class="container">
      <div class="reveal">
        <div class="section-header left" style="display:flex;justify-content:space-between;align-items:flex-end;max-width:none;margin-bottom:40px">
          <div style="max-width:560px">
            <span class="eyebrow" style="color:var(--purple)">Upcoming</span>
            <h2 class="section-title">Events and <em>gatherings.</em></h2>
          </div>
          <a href="/events/" class="btn btn-outline">View full calendar</a>
        </div>
      </div>
<?php
      // Real upcoming events from the Events CPT, soonest first. Past events
      // drop off automatically. Staff edit these in wp-admin > Events.
      $home_ev = new WP_Query(array(
        'post_type'      => 'events',
        'posts_per_page' => 5,
        'meta_key'       => 'event_start',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => array(array(
          'key' => 'event_start', 'value' => current_time('Y-m-d') . ' 00:00:00',
          'compare' => '>=', 'type' => 'DATETIME',
        )),
      ));
      $home_events = array();
      if ($home_ev->have_posts()) {
        while ($home_ev->have_posts()) { $home_ev->the_post();
          $eid = get_the_ID();
          $raw = (string) get_post_meta($eid, 'event_start', true);
          $ts  = $raw ? strtotime($raw) : 0;
          $end = trim((string) get_field('event_end', $eid));
          $home_events[] = array(
            'title' => get_the_title(),
            'ts'    => $ts,
            'type'  => trim((string) get_field('event_type', $eid)),
            'time'  => $ts ? date('g:i a', $ts) . ($end !== '' ? ' to ' . $end : '') : '',
          );
        }
        wp_reset_postdata();
      }
      if (!empty($home_events)) :
        $feat = $home_events[0];
        // The list shows the events after the featured one, so nothing repeats.
        $rows = array_slice($home_events, 1, 4);
      ?>
      <div class="events-layout">
        <div class="reveal">
          <div class="event-list">
            <?php foreach ($rows as $ev) : ?>
            <a class="event-row" href="<?php echo esc_url(home_url('/events/')); ?>">
              <div class="event-date"><div class="month"><?php echo esc_html($ev['ts'] ? date('M', $ev['ts']) : ''); ?></div><div class="day"><?php echo esc_html($ev['ts'] ? date('d', $ev['ts']) : ''); ?></div></div>
              <div><div class="event-title"><?php echo esc_html($ev['title']); ?></div><div class="event-meta"><?php if ($ev['time']) : ?><span><?php echo esc_html($ev['time']); ?></span><?php endif; ?><?php if ($ev['type']) : ?><span><?php echo esc_html($ev['type']); ?></span><?php endif; ?></div></div>
              <div class="arrow-sm"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></div>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="reveal">
          <div class="events-feature">
            <div style="position:absolute;inset:0;z-index:0;background:linear-gradient(150deg,var(--purple-400),var(--blue-900))"></div>
            <div class="tag">Featured<?php echo $feat['ts'] ? ' &middot; ' . esc_html(date('M j', $feat['ts'])) : ''; ?></div>
            <h3><?php echo esc_html($feat['title']); ?></h3>
            <div class="details"><?php if ($feat['time']) : ?><span><?php echo esc_html($feat['time']); ?></span><?php endif; ?><?php if ($feat['type']) : ?><span><?php echo esc_html($feat['type']); ?></span><?php endif; ?></div>
            <a href="<?php echo esc_url(home_url('/events/')); ?>" class="btn btn-light" style="width:fit-content">See details <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a>
          </div>
        </div>
      </div>
      <?php else : ?>
      <div class="reveal"><p class="lead">No events are on the calendar right now. Call (831) 718-9122 or check our Facebook for the latest.</p></div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ===== NEWSLETTER ===== -->
  <section class="newsletter">
    <div class="container">
      <div>
        <span class="eyebrow">Stay in the loop</span>
        <h2>Sweet stories, happy tails and <em>good news</em> in your inbox.</h2>
        <p>One thoughtful email a month. Adoption updates, events, and a little sunshine from the pups.</p>
      </div>
      <?php
      // The signup itself is the LGL newsletter form on /mailing-list/ (the
      // same form the live site uses). This teaser sends people there rather
      // than pretending to collect an email and discarding it.
      ?>
      <div class="newsletter-form">
        <div class="nform-row">
          <a class="btn btn-light" style="min-height:52px;display:inline-flex;align-items:center;gap:8px" href="<?php echo esc_url( home_url('/mailing-list/') ); ?>">Join the mailing list <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a>
        </div>
        <div class="fine">One thoughtful email a month. Unsubscribe anytime.</div>
      </div>
    </div>
  </section>

</main>
<?php get_footer();
