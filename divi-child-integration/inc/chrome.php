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
    return '<svg class="paw-badge" viewBox="0 0 32 32" aria-hidden="true"><circle cx="16" cy="16" r="16" fill="#632F88"/><g fill="#fff"><ellipse cx="10.5" cy="13" rx="2" ry="2.5"/><ellipse cx="14.7" cy="10.6" rx="2" ry="2.5"/><ellipse cx="18.3" cy="10.6" rx="2" ry="2.5"/><ellipse cx="22" cy="13.4" rx="2" ry="2.5"/><path d="M16.2 15.4c-3 0-5.4 2.2-5.4 4.7 0 1.8 1.5 2.8 3.2 2.8.9 0 1.5-.5 2.2-.5s1.3.5 2.2.5c1.7 0 3.2-1 3.2-2.8 0-2.5-2.4-4.7-5.4-4.7z"/></g></svg>';
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
          <a href="<?php echo pomdr_url('donate'); ?>" class="ab-btn ab-donate">Donate</a>
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
            <div class="nav-row nav-row--primary">
              <div class="nav-item"><a href="<?php echo pomdr_url('adopt'); ?>">Adopt</a></div>
              <div class="nav-item"><a href="<?php echo pomdr_url('foster'); ?>">Foster</a></div>
              <div class="nav-item"><a href="<?php echo pomdr_url('donate'); ?>">Donate</a></div>
              <div class="nav-item"><a href="<?php echo pomdr_url('volunteer'); ?>">Volunteer</a></div>
              <div class="nav-item"><a href="<?php echo pomdr_url('helping-paw'); ?>">Helping Paw</a></div>
            </div>
            <div class="nav-row nav-row--secondary">
              <div class="nav-item"><a href="<?php echo $home; ?>">Home</a></div>
              <div class="nav-item"><a href="<?php echo pomdr_url('about'); ?>">About</a></div>
              <div class="nav-item"><a href="<?php echo pomdr_url('surrender'); ?>">Surrender</a></div>
              <div class="nav-item"><a href="<?php echo pomdr_url('benefit-shop'); ?>">Benefit Shop</a></div>
              <div class="nav-item"><a href="<?php echo pomdr_url('contact'); ?>">Contact</a></div>
            </div>
          </div>
          <button class="nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="nav-mobile"><span></span><span></span><span></span></button>
        </div>
      </div>
      <div class="nav-mobile" id="nav-mobile" hidden>
        <a href="<?php echo $home; ?>">Home</a>
        <a href="<?php echo pomdr_url('adopt'); ?>">Adopt</a>
        <a href="<?php echo pomdr_url('foster'); ?>">Foster</a>
        <a href="<?php echo pomdr_url('donate'); ?>">Donate</a>
        <a href="<?php echo pomdr_url('volunteer'); ?>">Volunteer</a>
        <a href="<?php echo pomdr_url('helping-paw'); ?>">Helping Paw</a>
        <a href="<?php echo pomdr_url('about'); ?>">About</a>
        <a href="<?php echo pomdr_url('surrender'); ?>">Surrender</a>
        <a href="<?php echo pomdr_url('benefit-shop'); ?>">Benefit Shop</a>
        <a href="<?php echo pomdr_url('contact'); ?>">Contact</a>
        <a href="<?php echo pomdr_url('donate'); ?>" class="m-cta">Donate</a>
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
              <div class="logo-mark">P</div>
              <div class="logo-text"><div class="name">Peace of Mind</div><div class="sub">Dog Rescue</div></div>
            </a>
            <p class="footer-tagline">Helping senior dogs and senior people since 2009.</p>
            <p class="footer-mission">A 501(c)(3) nonprofit serving Monterey, Santa Cruz and San Benito counties.</p>
            <div class="footer-contact">
              <a href="tel:8317189122">(831) 718-9122</a>
              <a href="mailto:info@pomdr.org">info@pomdr.org</a>
              <span>615 Forest Avenue, Pacific Grove, CA 93950</span>
            </div>
          </div>
          <nav class="footer-cols" aria-label="Footer">
            <div class="footer-col">
              <h2>Adopt</h2>
              <ul>
                <li><a href="<?php echo pomdr_url('adopt'); ?>">Available Dogs</a></li>
                <li><a href="<?php echo pomdr_url('process'); ?>">Adoption Process</a></li>
                <li><a href="<?php echo pomdr_url('events'); ?>">Adoption Events</a></li>
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
        <div class="footer-bottom">
          <div class="footer-legal">
            <span>&copy; <?php echo esc_html( date('Y') ); ?> Peace of Mind Dog Rescue. 501(c)(3) nonprofit.</span>
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
