<?php
/**
 * POMDR site chrome: action bar, two-row nav, centered tagline, clickable logo,
 * mobile drawer. Injected on wp_body_open; Divi's Theme Builder header is
 * hidden by pomdr-chrome.css. Developer-managed (not staff-editable) per the
 * project's editability model. The nav model mirrors the prototype
 * (pomdr-website/project/pomdr-layout.js).
 */

defined('ABSPATH') || exit;

function pomdr_url($slug) {
    return esc_url(home_url('/' . ltrim($slug, '/')));
}

function pomdr_paw_badge() {
    // The brand purple circle paw (matches assets/images/paw-badge-purple.svg,
    // vectorized from the provided PNG): white paw on the purple disc.
    return '<svg class="paw-badge" viewBox="0 0 64 64" aria-hidden="true"><circle cx="32" cy="32" r="32" fill="#632F88"/><g fill="#fff" transform="translate(5.6 5.2) scale(2.2)"><ellipse cx="3.7" cy="11.6" rx="2.8" ry="3.7" transform="rotate(-20 3.7 11.6)"/><ellipse cx="8.7" cy="5.9" rx="3.1" ry="4.3" transform="rotate(-7 8.7 5.9)"/><ellipse cx="15.3" cy="5.9" rx="3.1" ry="4.3" transform="rotate(7 15.3 5.9)"/><ellipse cx="20.3" cy="11.6" rx="2.8" ry="3.7" transform="rotate(20 20.3 11.6)"/><path d="M12 10.7 C8.6 10.7 5.2 13.4 5.2 17.1 C5.2 19.9 7.2 21.9 9.5 21.9 C10.6 21.9 11.3 21.1 12 21.1 C12.7 21.1 13.4 21.9 14.5 21.9 C16.8 21.9 18.8 19.9 18.8 17.1 C18.8 13.4 15.4 10.7 12 10.7 Z"/></g></svg>';
}


function pomdr_render_chrome() {
    $home = esc_url(home_url('/'));
    $logo = esc_url(get_stylesheet_directory_uri() . '/assets/images/logo-horizontal.png');
    $paw  = pomdr_paw_badge();
    ?>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <div class="action-bar" id="action-bar">
      <div class="container action-bar-inner">
        <p class="action-bar-tag">Helping senior dogs and senior people since 2009</p>
        <div class="action-bar-actions">
          <a href="<?php echo pomdr_url('adopt'); ?>" class="ab-btn ab-adopt">Adopt</a>
          <a href="<?php echo pomdr_url('volunteer'); ?>" class="ab-btn ab-volunteer">Volunteer</a>
        </div>
      </div>
    </div>
    <nav class="nav" id="site-nav" aria-label="Primary">
      <div class="container">
        <div class="nav-inner">
          <a href="<?php echo $home; ?>" class="logo" aria-label="Peace of Mind Dog Rescue, home">
            <img src="<?php echo $logo; ?>" alt="Peace of Mind Dog Rescue" class="logo-img" width="600" height="133">
          </a>
          <div class="nav-tagline" aria-hidden="true">
            <span class="nav-tagline-1">Helping Senior Dogs and Senior People</span>
            <span class="nav-tagline-2"><?php echo $paw; ?><span class="nav-since">SINCE 2009</span><?php echo $paw; ?></span>
          </div>
          <div class="nav-menu">
            <?php
            // Same top-level items as before; each opens a dropdown of the
            // site's existing pages (no new sections). Dropdowns are
            // click-to-open (no hover-only per the accessibility charter).
            // Five dropdowns plus Donate (2026-08-01 round two): Helping Paw is
            // its own section, and the two big menus (Dogs, About) lay out in
            // two columns. Entries with an empty url render as section headers;
            // a 'cols' key (array of columns) renders a wide two-column panel.
            $pomdr_nav = array(
                'main' => array(
                    array( 'label' => 'Dogs', 'url' => 'adopt', 'cols' => array(
                        array(
                            array( 'Adopt', '' ),
                            array( 'Adoptable Dogs', 'adopt' ),
                            array( 'Adoption Process', 'process' ),
                            array( 'Why a Senior Dog', 'why' ),
                            array( 'Courtesy Listings', 'courtesy-listings' ),
                            array( 'Hospice Dogs', 'hospice' ),
                            array( 'Happy Tails', 'adopted' ),
                        ),
                        array(
                            array( 'Foster', '' ),
                            array( 'About Fostering', 'fostering' ),
                            array( 'Dogs Needing Foster', 'foster-needs' ),
                            array( 'Surrender', '' ),
                            array( 'Placing Your Dog', 'surrender' ),
                            array( 'Intake Questionnaire', 'intake-questionnaire' ),
                            array( 'Perpetual Care', 'perpetual-care-program' ),
                            array( 'Perpetual Care FAQ', 'perpetual-care-faq' ),
                        ),
                    ) ),
                    array( 'label' => 'Volunteer', 'url' => 'volunteer', 'items' => array(
                        array( 'Volunteering', 'volunteer' ),
                        array( 'Volunteer Application', 'volunteer-application' ),
                    ) ),
                    array( 'label' => 'Helping Paw', 'url' => 'helping-paw', 'items' => array(
                        array( 'About Helping Paw', 'helping-paw' ),
                        array( 'Apply for Assistance', 'helping-paw-application' ),
                        array( "Max's Helping Paws Fund", 'maxs-fund' ),
                    ) ),
                    array( 'label' => "What's Happening", 'url' => 'events', 'items' => array(
                        array( 'Fundraisers and Special Events', 'events/#whats-happening' ),
                        array( 'Adoption Events', 'events/#adoption-events' ),
                        array( 'News and Updates', 'news' ),
                    ) ),
                    array( 'label' => 'About', 'url' => 'about', 'cols' => array(
                        array(
                            array( 'Who We Are', '' ),
                            array( 'Our Story', 'about' ),
                            array( 'Our Culture', 'about/culture' ),
                            array( 'Testimonials', 'testimonials' ),
                            array( 'Videos', 'videos' ),
                            array( 'In the Media', 'media' ),
                            array( 'Jobs', 'jobs' ),
                        ),
                        array(
                            array( 'Visit and Connect', '' ),
                            array( 'Bauer Center', 'bauer-center' ),
                            array( 'Vet Clinic', 'clinic' ),
                            array( 'Benefit Shop', 'benefit-shop' ),
                            array( 'Resources', 'recources' ),
                            array( 'Mailing List', 'mailing-list' ),
                            array( 'Contact', 'mailto:info@pomdr.org' ),
                        ),
                    ) ),
                ),
            );
            foreach ( array( 'main' ) as $row ) :

            ?>
            <div class="nav-row nav-row--<?php echo esc_attr( $row ); ?>">
              <?php foreach ( $pomdr_nav[ $row ] as $item ) :
                  $href = ( 0 === strpos( $item['url'], 'mailto:' ) ) ? $item['url'] : pomdr_url( $item['url'] );
                  // Normalize: single-column menus use 'items'; wide menus use 'cols'.
                  $drop_cols = isset( $item['cols'] ) ? $item['cols'] : ( ! empty( $item['items'] ) ? array( $item['items'] ) : array() );
                  $has_drop  = ! empty( $drop_cols );
              ?>
              <div class="nav-item<?php echo $has_drop ? ' has-drop' : ''; ?>">
                <a href="<?php echo esc_url( $href ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                <?php if ( $has_drop ) : ?>
                <button type="button" class="nav-caret" aria-expanded="false" aria-label="<?php echo esc_attr( 'Open ' . $item['label'] . ' menu' ); ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="nav-drop<?php echo count( $drop_cols ) > 1 ? ' nav-drop--wide' : ''; ?>" hidden>
                  <?php foreach ( $drop_cols as $col ) : ?>
                  <div class="dd-col">
                    <?php foreach ( $col as $sub ) : ?>
                    <?php if ( '' === $sub[1] ) : // section header inside the dropdown ?>
                    <span class="dd-section"><?php echo esc_html( $sub[0] ); ?></span>
                    <?php else :
                        $sub_href = ( 0 === strpos( $sub[1], 'mailto:' ) ) ? $sub[1] : pomdr_url( $sub[1] );
                    ?>
                    <a href="<?php echo esc_url( $sub_href ); ?>"><?php echo esc_html( $sub[0] ); ?></a>
                    <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
          </div>
          <a href="<?php echo pomdr_url('donate'); ?>" class="nav-donate">Donate</a>
          <button class="nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="nav-mobile"><span></span><span></span><span></span></button>
        </div>
      </div>
      <div class="nav-mobile" id="nav-mobile" hidden>
        <?php foreach ( array( 'main' ) as $row ) : ?>
          <?php foreach ( $pomdr_nav[ $row ] as $item ) :
              $href = ( 0 === strpos( $item['url'], 'mailto:' ) ) ? $item['url'] : pomdr_url( $item['url'] );
          ?>
          <a class="m-top" href="<?php echo esc_url( $href ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
          <?php
          // Flatten wide (multi-column) menus for the drawer.
          $m_items = isset( $item['cols'] ) ? array_merge( ...$item['cols'] ) : ( $item['items'] ?? array() );
          foreach ( $m_items as $sub ) :
              if ( $sub[1] === $item['url'] || '' === $sub[1] ) { continue; } // skip self-links and section headers
              $sub_href = ( 0 === strpos( $sub[1], 'mailto:' ) ) ? $sub[1] : pomdr_url( $sub[1] );
          ?>
          <a class="m-sub" href="<?php echo esc_url( $sub_href ); ?>"><?php echo esc_html( $sub[0] ); ?></a>
          <?php endforeach; ?>
          <?php endforeach; ?>
        <?php endforeach; ?>
        <a href="<?php echo pomdr_url('donate'); ?>" class="m-cta">Donate</a>
      </div>
      </div>
    </nav>
    <?php
}
add_action('wp_body_open', 'pomdr_render_chrome', 5);

/**
 * Site footer (ports the prototype footer). Rendered on wp_footer; Divi's
 * Theme Builder footer is hidden by pomdr-chrome.css. Includes the clickable
 * contact link that opens an email to info@pomdr.org.
 */
function pomdr_render_footer() {
    $home = esc_url(home_url('/'));
    ?>
    <footer class="footer" id="site-footer">
      <div class="container">
        <div class="footer-main">
          <div class="footer-brand">
            <a href="<?php echo $home; ?>" class="logo footer-logo" aria-label="Peace of Mind Dog Rescue, home">
              <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-horizontal.png' ); ?>" alt="Peace of Mind Dog Rescue" class="footer-logo-img" width="600" height="133">
            </a>
            <p class="footer-tagline">Helping senior dogs and senior people since 2009.</p>
          </div>
          <nav class="footer-cols" aria-label="Footer">
            <div class="footer-col">
              <h2>Adopt</h2>
              <ul>
                <li><a href="<?php echo pomdr_url('adopt'); ?>">Available Dogs</a></li>
                <li><a href="<?php echo pomdr_url('process'); ?>">Adoption Process</a></li>
                <li><a href="<?php echo pomdr_url('events/#adoption-events'); ?>">Adoption Events</a></li>
                <li><a href="<?php echo pomdr_url('foster-needs'); ?>">Foster a Dog</a></li>
              </ul>
            </div>
            <div class="footer-col">
              <h2>Get Involved</h2>
              <ul>
                <li><a href="<?php echo pomdr_url('donate'); ?>">Donate</a></li>
                <li><a href="<?php echo pomdr_url('volunteer'); ?>">Volunteer</a></li>
                <li><a href="<?php echo pomdr_url('helping-paw'); ?>">Helping Paw</a></li>
                <li><a href="<?php echo pomdr_url('surrender'); ?>">Placing Your Dog</a></li>
              </ul>
            </div>
            <div class="footer-col">
              <h2>About</h2>
              <ul>
                <li><a href="<?php echo pomdr_url('about'); ?>">Our Story</a></li>
                <li><a href="<?php echo pomdr_url('about'); ?>#team">Team</a></li>
                <li><a href="mailto:info@pomdr.org">Contact</a></li>
                <li><a href="<?php echo pomdr_url('donate'); ?>">Ways to Give</a></li>
              </ul>
            </div>
          </nav>
        </div>
        <div class="footer-contact footer-contact--row">
          <a href="tel:8317189122">(831) 718-9122</a>
          <a href="mailto:info@pomdr.org">info@pomdr.org</a>
          <span>615 Forest Avenue, Pacific Grove, CA 93950</span>
        </div>
        <div class="footer-bottom">
          <div class="footer-legal">
            <span>&copy; <?php echo esc_html( date('Y') ); ?> Peace of Mind Dog Rescue. 501(c)(3) nonprofit serving Monterey, Santa Cruz and San Benito counties.</span>
            <span>EIN 27-1154816</span>
          </div>
          <div class="footer-bottom-right">
            <div class="footer-legal-links">
              <a href="<?php echo pomdr_url('privacy'); ?>">Privacy</a>
              <a href="<?php echo pomdr_url('terms'); ?>">Terms</a>
              <a href="mailto:info@pomdr.org">Contact</a>
            </div>
            <div class="socials">
              <a href="https://www.instagram.com/peace.of.mind.dog.rescue/" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.2c3.2 0 3.6 0 4.8.1 1.2.1 1.9.2 2.3.4.6.2 1 .5 1.5 1s.8.9 1 1.5c.2.4.3 1.1.4 2.3.1 1.2.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 1.2-.2 1.9-.4 2.3-.2.6-.5 1-1 1.5s-.9.8-1.5 1c-.4.2-1.1.3-2.3.4-1.2.1-1.6.1-4.8.1s-3.6 0-4.8-.1c-1.2-.1-1.9-.2-2.3-.4-.6-.2-1-.5-1.5-1s-.8-.9-1-1.5c-.2-.4-.3-1.1-.4-2.3C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.8c.1-1.2.2-1.9.4-2.3.2-.6.5-1 1-1.5s.9-.8 1.5-1c.4-.2 1.1-.3 2.3-.4C8.4 2.2 8.8 2.2 12 2.2zm0 3.1a4.9 4.9 0 1 1 0 9.8 4.9 4.9 0 0 1 0-9.8zm0 8.1a3.2 3.2 0 1 0 0-6.4 3.2 3.2 0 0 0 0 6.4zm5-8.3a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2z"/></svg></a>
              <a href="https://www.facebook.com/POMDR/" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 21v-8h2.7l.4-3.2h-3.1V7.7c0-.9.3-1.6 1.6-1.6h1.7V3.2c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.4-4 4v2.7H7.6V13h2.8v8h3.1z"/></svg></a>
              <a href="https://www.youtube.com/user/PeaceOfMindDogRescue" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.2c-.2-.9-.9-1.6-1.8-1.8C18.3 5 12 5 12 5s-6.3 0-7.8.4c-.9.2-1.6.9-1.8 1.8C2 8.7 2 12 2 12s0 3.3.4 4.8c.2.9.9 1.6 1.8 1.8 1.5.4 7.8.4 7.8.4s6.3 0 7.8-.4c.9-.2 1.6-.9 1.8-1.8.4-1.5.4-4.8.4-4.8s0-3.3-.4-4.8zM10 15V9l5.2 3L10 15z"/></svg></a>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <?php
}
add_action('wp_footer', 'pomdr_render_footer', 5);
