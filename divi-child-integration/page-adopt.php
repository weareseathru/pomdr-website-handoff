<?php
/**
 * Template for the Adopt page (WP slug: adopt). Ports prototype adopt.html.
 *
 * The dog grid is rendered server-side from the editable `pets` CPT (never a
 * hardcoded array), so staff edit dogs in wp-admin and this page updates
 * automatically. The hero stats are counted server-side with WP_Query; the
 * search, category filter, and sort run client-side over the rendered cards
 * (assets/js/adopt-filter.js), as progressive enhancement.
 *
 * Chrome + footer from the theme; shared look from pomdr.css (enqueued
 * site-wide). No CSS is ported here.
 */
get_header();

/**
 * Count pets whose ACF `status` checkbox includes a given Title Case value.
 * Uses a LIKE meta_query because the value is stored as a serialized array.
 */
if ( ! function_exists( 'pom_adopt_count_status' ) ) {
    function pom_adopt_count_status( $status_value ) {
        $q = new WP_Query( array(
            'post_type'      => 'pets',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'no_found_rows'  => false,
            'meta_query'     => array(
                array( 'key' => 'status', 'value' => $status_value, 'compare' => 'LIKE' ),
            ),
        ) );
        $n = (int) $q->found_posts;
        wp_reset_postdata();
        return $n;
    }
}

// Available now = status includes Adoptable.
$count_available = pom_adopt_count_status( 'Adoptable' );
// Need foster = status includes Foster Needed.
$count_foster = pom_adopt_count_status( 'Foster Needed' );

// Placeable total = currently available to place (Adoptable OR Foster Needed),
// excluding Adopted. This is the same set the grid renders.
$placeable_query = new WP_Query( array(
    'post_type'      => 'pets',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => array(
        'relation' => 'AND',
        array(
            'relation' => 'OR',
            array( 'key' => 'status', 'value' => 'Adoptable',     'compare' => 'LIKE' ),
            array( 'key' => 'status', 'value' => 'Foster Needed', 'compare' => 'LIKE' ),
        ),
        array( 'key' => 'status', 'value' => 'Adopted', 'compare' => 'NOT LIKE' ),
    ),
) );
$count_total = (int) $placeable_query->found_posts;

/**
 * Query a status group for the adopt-page tabs (Courtesy Listing, Hospice,
 * Adopted). CPT-driven like the main grid, so staff manage these in wp-admin.
 */
if ( ! function_exists( 'pom_adopt_group_query' ) ) {
    function pom_adopt_group_query( $status_value, $limit = -1 ) {
        return new WP_Query( array(
            'post_type'      => 'pets',
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => array(
                array( 'key' => 'status', 'value' => $status_value, 'compare' => 'LIKE' ),
            ),
        ) );
    }
}

// The three tab groups mirroring the live site (Other Adoptable = Courtesy
// Listing). Adopted is capped to a recent set so the happy-tail tab stays light.
$q_courtesy = pom_adopt_group_query( 'Courtesy Listing' );
$q_hospice  = pom_adopt_group_query( 'Hospice' );
$q_adopted  = pom_adopt_group_query( 'Adopted', 24 );

// Category filter buttons output server-side (not JS-populated). Slugs match
// the data-status values on each card.
// "Available" chip removed 2026-07-09 (leadership spec): every dog in this
// group IS available, so the chip was noise. All adoptable dogs show by
// default; the remaining chips filter for special situations.
$adopt_filters = array(
    'all'              => 'All',
    'foster-needed'    => 'Foster Needed',
    'sponsor-needed'   => 'Sponsor Needed',
    'adoption-pending' => 'Adoption Pending',
);
?>
<main id="main-content">
<style>
/* toolbar, filters, etc. already have styles. Just keep page-level. */
.hero{padding:40px 0 60px;background:radial-gradient(ellipse at 20% 0%,rgba(0,139,176,.07),transparent 55%),radial-gradient(ellipse at 80% 10%,rgba(99,47,136,.05),transparent 55%),var(--cream);border-bottom:1px solid var(--line);}
/* The headline uses the shared .page-headline size so Adopt matches the other pages. */
.hero p{font-size:19px;color:var(--ink-2);max-width:560px;margin:0;}
/* Lead copy and the three counts share one row so the dogs are closer to the top. */
.hero-row{display:grid;grid-template-columns:1.25fr 1fr;gap:44px;align-items:center;margin-top:22px;}
.hero-meta{display:flex;gap:34px;flex-wrap:wrap;padding-left:44px;border-left:1px solid var(--line);}
.hero-meta strong{font-family:var(--font-serif);font-size:34px;font-weight:300;color:var(--blue);display:block;line-height:1;margin-bottom:4px;}
.hero-meta div{font-size:16px;color:var(--ink-3);}
@media(max-width:820px){.hero-row{grid-template-columns:1fr;gap:22px;}.hero-meta{padding-left:0;border-left:none;padding-top:22px;border-top:1px solid var(--line);}}

/* ===== Adopt toolbar, reorganized into clean tiers =====
   A prominent search on its own row, the category tabs, then one row that
   carries the filter chips and the sort control together. Overrides the shared
   single-row toolbar layout from pomdr.css (this <style> loads after it). */
.toolbar-inner{display:block;padding:18px 32px 16px;}

/* Prominent, full-width search for the dog list. */
.toolbar-search{
  display:flex;align-items:center;gap:12px;
  background:var(--white);border:1.5px solid var(--line);
  border-radius:999px;padding:5px 8px 5px 20px;margin-bottom:16px;
  box-shadow:var(--shadow-sm);
  transition:border-color .2s var(--ease),box-shadow .2s var(--ease);
}
.toolbar-search:focus-within{border-color:var(--blue);box-shadow:0 0 0 4px rgba(0,139,176,.14);}
.toolbar-search > svg{color:var(--ink-3);flex-shrink:0;}
.toolbar-search input{
  flex:1;min-width:0;border:none;outline:none;background:transparent;
  font:inherit;font-size:17px;color:var(--ink);padding:13px 0;
}
.toolbar-search input::placeholder{color:var(--ink-3);}

/* Category tabs on their own row. */
.tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px;}

/* Filters and sort share one tidy row, divided from the tabs above. */
.toolbar-controls{
  display:flex;align-items:center;justify-content:space-between;
  gap:12px 18px;flex-wrap:wrap;
  border-top:1px solid var(--line);padding-top:14px;
}
.toolbar-controls .filters{display:flex;gap:8px;flex-wrap:wrap;flex:1 1 auto;margin:0;}
.toolbar-controls .sort{flex-shrink:0;margin-left:auto;}

/* ===== Status-group tabs (Our Adoptable / Other Adoptable / Hospice / Adopted).
   Placed below the toolbar, above the cards. Each tab toggles a CPT-driven
   group grid; the search/filter/sort toolbar belongs to the Adoptable group. */
.dog-tabs{display:flex;gap:4px;flex-wrap:wrap;border-bottom:2px solid var(--line);margin:34px 0 4px;}
.dog-tab{appearance:none;background:none;border:none;font:inherit;font-size:16px;font-weight:600;color:var(--ink-3);padding:12px 18px;cursor:pointer;border-bottom:3px solid transparent;margin-bottom:-2px;display:inline-flex;align-items:center;gap:9px;transition:color .2s var(--ease),border-color .2s var(--ease);}
.dog-tab:hover{color:var(--blue-700);}
.dog-tab.active{color:var(--blue-900);border-bottom-color:var(--blue-700);}
.dog-tab:focus-visible{outline:3px solid var(--blue);outline-offset:2px;border-radius:6px 6px 0 0;}
.dog-tab-count{font-size: 14px;font-weight:700;color:var(--blue-700);background:var(--blue-50);border-radius:999px;padding:2px 9px;line-height:1.5;}
.dog-tab.active .dog-tab-count{background:var(--blue-100);}
.dog-group[hidden]{display:none;}
.group-intro{font-size:17px;color:var(--ink-2);max-width:70ch;margin:26px 0 4px;line-height:1.55;}
.group-empty{grid-column:1/-1;padding:56px 40px;text-align:center;color:var(--ink-3);border:1px dashed var(--line);border-radius:var(--radius);font-size:16px;}
@media (max-width:600px){.dog-tab{padding:11px 12px;font-size: 16px;}}

/* Slim results count above the grid. */
.results-head{padding:30px 0 20px;}

@media (max-width:600px){
  .toolbar-inner{padding:14px 20px 12px;}
  .toolbar-controls{align-items:flex-start;}
  .toolbar-controls .sort{margin-left:0;}
}
</style>

<section class="hero">
  <div class="container hero-inner">
    <h1 class="page-headline">Adoptable Dogs</h1>
    <p class="page-narrative">Find the dogs looking for their <em>forever</em> people.</p>
    <div class="hero-row">
      <p>Although we specialize in senior dogs, we also get younger dogs surrendered to us from senior guardians. Adoptable dogs are available to meet by appointment at our Pacific Grove center.</p>
      <div class="hero-meta">
        <div><strong id="count-total"><?php echo esc_html( $count_total ); ?></strong>Adoptable dogs</div>
        <div><strong id="count-available"><?php echo esc_html( $count_available ); ?></strong>Available now</div>
        <div><strong id="count-foster"><?php echo esc_html( $count_foster ); ?></strong>Need foster</div>
      </div>
    </div>
  </div>
</section>

<div class="toolbar">
  <div class="container toolbar-inner">
    <div class="toolbar-search">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3-3"/></svg>
      <input id="search-input" type="search" aria-label="Search dogs by name or breed" placeholder="Search dogs by name or breed&hellip;" />
    </div>
    <div class="toolbar-controls">
      <div class="filters" id="filters">
        <?php foreach ( $adopt_filters as $slug => $label ) : ?>
          <button class="filter<?php echo ( 'all' === $slug ) ? ' active' : ''; ?>" type="button" data-filter="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></button>
        <?php endforeach; ?>
      </div>
      <div class="sort">
        <label for="sort-select">Sort by</label>
        <select id="sort-select">
          <option value="default">Newest</option>
          <option value="age-asc">Age &middot; youngest</option>
          <option value="age-desc">Age &middot; oldest</option>
          <option value="weight-asc">Size &middot; smallest</option>
          <option value="weight-desc">Size &middot; largest</option>
        </select>
      </div>
    </div>
  </div>
</div>

<section class="grid-section">
  <div class="container">

    <!-- Status-group tabs: below the toolbar, above the cards. Each toggles a
         CPT-driven group. Mirrors the live site (Other Adoptable = Courtesy). -->
    <div class="dog-tabs" role="tablist" aria-label="Dog groups">
      <button class="dog-tab active" type="button" role="tab" aria-selected="true" aria-controls="group-adoptable" id="tab-adoptable" data-group="adoptable">Our Adoptable Dogs <span class="dog-tab-count"><?php echo esc_html( $count_total ); ?></span></button>
      <button class="dog-tab" type="button" role="tab" aria-selected="false" aria-controls="group-courtesy" id="tab-courtesy" data-group="courtesy">Other Adoptable Dogs <span class="dog-tab-count"><?php echo esc_html( (int) $q_courtesy->found_posts ); ?></span></button>
      <button class="dog-tab" type="button" role="tab" aria-selected="false" aria-controls="group-hospice" id="tab-hospice" data-group="hospice">Hospice Care <span class="dog-tab-count"><?php echo esc_html( (int) $q_hospice->found_posts ); ?></span></button>
      <button class="dog-tab" type="button" role="tab" aria-selected="false" aria-controls="group-adopted" id="tab-adopted" data-group="adopted">Adopted Dogs <span class="dog-tab-count"><?php echo esc_html( (int) $q_adopted->found_posts ); ?></span></button>
    </div>

    <!-- Adoptable (default). The toolbar search/filter/sort applies to this group. -->
    <div class="dog-group" data-group="adoptable" id="group-adoptable" role="tabpanel" aria-labelledby="tab-adoptable">
      <div class="results-head">
        <div class="results-count serif" id="results-count"><?php echo esc_html( $count_total === 1 ? '1 dog' : $count_total . ' dogs' ); ?></div>
      </div>
      <div class="dogs-grid" id="dogs-grid">
        <?php
        if ( $placeable_query->have_posts() ) :
            while ( $placeable_query->have_posts() ) :
                $placeable_query->the_post();
                echo pom_render_dog_card( get_the_ID() );
            endwhile;
        else :
            echo '<div class="group-empty">No adoptable dogs are listed right now. Please check back soon.</div>';
        endif;
        wp_reset_postdata();
        ?>
      </div>
    </div>

    <?php
    // The three secondary groups share one renderer. Each is CPT-driven, so
    // staff manage them in wp-admin by setting a dog's status.
    $pom_groups = array(
        'courtesy' => array(
            'query' => $q_courtesy,
            'intro' => 'Dogs listed as a courtesy for other rescues and private guardians. These dogs are not in POMDR care; the listing helps them find homes.',
            'empty' => 'No courtesy listings right now. Please check back soon.',
        ),
        'hospice'  => array(
            'query' => $q_hospice,
            'intro' => 'Sanctuary dogs in POMDR hospice care. Their medical or age needs mean they live out their days safe and loved with us, rather than being adopted out.',
            'empty' => 'No hospice dogs are listed right now.',
        ),
        'adopted'  => array(
            'query' => $q_adopted,
            'intro' => 'A few of our recent happy tails. Senior dogs who found their people and a soft place to land.',
            'empty' => 'Recently adopted dogs will appear here soon.',
        ),
    );
    foreach ( $pom_groups as $gkey => $g ) :
        $gq = $g['query'];
        ?>
        <div class="dog-group" data-group="<?php echo esc_attr( $gkey ); ?>" id="group-<?php echo esc_attr( $gkey ); ?>" role="tabpanel" aria-labelledby="tab-<?php echo esc_attr( $gkey ); ?>" hidden>
          <p class="group-intro"><?php echo esc_html( $g['intro'] ); ?></p>
          <div class="dogs-grid">
            <?php
            if ( $gq->have_posts() ) :
                while ( $gq->have_posts() ) :
                    $gq->the_post();
                    echo pom_render_dog_card( get_the_ID() );
                endwhile;
            else :
                echo '<div class="group-empty">' . esc_html( $g['empty'] ) . '</div>';
            endif;
            wp_reset_postdata();
            ?>
          </div>
        </div>
    <?php endforeach; ?>

  </div>
</section>

<script>
(function () {
  var tabs = Array.prototype.slice.call(document.querySelectorAll('.dog-tab'));
  var groups = Array.prototype.slice.call(document.querySelectorAll('.dog-group'));
  var toolbar = document.querySelector('.toolbar');
  if (!tabs.length) return;
  function activate(tab) {
    tabs.forEach(function (t) {
      var on = t === tab;
      t.classList.toggle('active', on);
      t.setAttribute('aria-selected', on ? 'true' : 'false');
      t.tabIndex = on ? 0 : -1;
    });
    var g = tab.getAttribute('data-group');
    groups.forEach(function (grp) { grp.hidden = grp.getAttribute('data-group') !== g; });
    if (toolbar) { toolbar.style.display = (g === 'adoptable') ? '' : 'none'; }
  }
  tabs.forEach(function (tab, i) {
    tab.addEventListener('click', function () { activate(tab); });
    tab.addEventListener('keydown', function (e) {
      var idx = i;
      if (e.key === 'ArrowRight') { idx = (i + 1) % tabs.length; }
      else if (e.key === 'ArrowLeft') { idx = (i - 1 + tabs.length) % tabs.length; }
      else { return; }
      e.preventDefault();
      tabs[idx].focus();
      activate(tabs[idx]);
    });
  });
})();
</script>

</main>
<?php get_footer();
