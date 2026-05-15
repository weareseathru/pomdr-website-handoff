/* ============================================================
   POMDR Shared Layout
   Injects identical nav + footer into every page.
   Usage: <body data-page="adopt"> ... <script src="pomdr-layout.js"></script>

   Source of truth:
   - Nav structure: INVENTORY.md section 2 (19-item spec + captured nav)
   - Footer identity: INVENTORY.md section 4 + global CLAUDE.md
   - Voice rules: VOICE.md
   ============================================================ */

(function () {
  // ---- NAV STRUCTURE ----
  // Top-level items + 2-level sub-menus, mirroring the captured staging nav
  // while honoring the 19-item HANDOFF spec. Items without dedicated pages
  // resolve to stub HTMLs created in this session.
  const NAV_ITEMS = [
    { id: "home",        label: "Home",         href: "index.html" },
    {
      id: "about", label: "About", href: "about.html",
      children: [
        { id: "about-team",     label: "Our Team",       href: "about.html#team" },
        { id: "media",          label: "In the Media",   href: "media.html" },
        { id: "videos",         label: "Videos",         href: "videos.html" },
        { id: "testimonials",   label: "Testimonials",   href: "testimonials.html" },
        { id: "jobs",           label: "Job Openings",   href: "jobs.html" },
      ],
    },
    {
      id: "adopt", label: "Adopt", href: "adopt.html",
      children: [
        { id: "adopt-list",     label: "Adoptable Dogs",     href: "adopt.html" },
        { id: "process",        label: "Our Adoption Process", href: "process.html" },
        { id: "courtesy",       label: "Courtesy Listings",  href: "courtesy-listings.html" },
        { id: "hospice",        label: "Hospice",            href: "hospice.html" },
        { id: "adopted",        label: "Recently Adopted",   href: "adopted.html" },
        { id: "events",         label: "Adoption Events",    href: "events.html" },
      ],
    },
    { id: "foster",      label: "Foster",       href: "foster.html" },
    {
      id: "volunteer", label: "Volunteer", href: "volunteer.html",
      children: [
        { id: "volunteer-opps", label: "Volunteer Opportunities", href: "volunteer.html" },
        { id: "volunteer-app",  label: "Volunteer Application",   href: "volunteer-application.html" },
      ],
    },
    {
      id: "helping-paw", label: "Helping Paw", href: "helping-paw.html",
      children: [
        { id: "helping-paw-main", label: "Helping Paw Program", href: "helping-paw.html" },
        { id: "resources",        label: "Resources",           href: "resources.html" },
      ],
    },
    {
      id: "surrender", label: "Surrender", href: "surrender.html",
      children: [
        { id: "surrender-main",   label: "Placing Your Dog",    href: "surrender.html" },
        { id: "perpetual-care",   label: "Lifetime Care",       href: "perpetual-care-program.html" },
      ],
    },
    { id: "benefit-shop", label: "Benefit Shop", href: "benefit-shop.html" },
    { id: "contact",      label: "Contact",      href: "contact.html" },
  ];

  const page = document.body.getAttribute("data-page") || "";
  // Detect depth: profile pages live in /dog/ and need ../ prefix.
  const inSubdir = location.pathname.includes("/dog/");
  const rel = (href) => {
    if (href.startsWith("http") || href.startsWith("mailto:") || href.startsWith("tel:")) return href;
    // Preserve hash-only anchors as-is.
    if (href.startsWith("#")) return href;
    return inSubdir ? "../" + href : href;
  };

  const isActive = (item) => {
    if (page === item.id) return true;
    if (item.children) return item.children.some(c => page === c.id);
    return false;
  };

  const renderTopItem = (item) => {
    const active = isActive(item) ? ' class="active"' : '';
    const ariaCurrent = page === item.id ? ' aria-current="page"' : '';
    if (!item.children) {
      return `
        <div class="nav-item">
          <a href="${rel(item.href)}"${active}${ariaCurrent}>${item.label}</a>
        </div>`;
    }
    return `
      <div class="nav-item has-children">
        <a href="${rel(item.href)}"${active}${ariaCurrent} aria-haspopup="true" aria-expanded="false">${item.label}<span class="nav-chevron"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></span></a>
        <div class="nav-dropdown" role="menu">
          ${item.children.map(c => `<a href="${rel(c.href)}" role="menuitem"${page === c.id ? ' aria-current="page" class="active"' : ''}>${c.label}</a>`).join("")}
        </div>
      </div>`;
  };

  // Mobile menu: flat list with section headers for grouped items.
  const renderMobileItems = () => {
    const lines = [];
    for (const item of NAV_ITEMS) {
      const activeAttr = page === item.id ? ' class="active"' : '';
      if (!item.children) {
        lines.push(`<a href="${rel(item.href)}"${activeAttr}>${item.label}</a>`);
        continue;
      }
      lines.push(`<a href="${rel(item.href)}"${activeAttr}>${item.label}</a>`);
      for (const c of item.children) {
        const cActive = page === c.id ? ' class="m-child active"' : ' class="m-child"';
        lines.push(`<a href="${rel(c.href)}"${cActive}>${c.label}</a>`);
      }
    }
    return lines.join("");
  };

  // ---- SKIP LINK ----
  const skipHTML = `<a href="#main" class="skip-link">Skip to main content</a>`;

  // ---- NAV ----
  const navHTML = `
    <nav class="nav" id="site-nav" aria-label="Primary">
      <div class="container">
        <div class="nav-inner">
          <a href="${rel("index.html")}" class="logo" aria-label="Peace of Mind Dog Rescue, home">
            <div class="logo-mark">P</div>
            <div class="logo-text">
              <span class="name">Peace of Mind</span>
              <span class="sub">Dog Rescue</span>
            </div>
          </a>
          <div class="nav-links">
            ${NAV_ITEMS.map(renderTopItem).join("")}
            <a href="${rel("donate.html")}" class="nav-cta ${page === 'donate' ? 'active' : ''}">
              Donate
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </a>
            <button class="nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="nav-mobile">
              <span></span><span></span><span></span>
            </button>
          </div>
        </div>
      </div>
      <div class="nav-mobile" id="nav-mobile" hidden>
        ${renderMobileItems()}
        <a href="${rel("donate.html")}" class="m-cta">Donate</a>
      </div>
    </nav>
  `;

  // ---- FOOTER ----
  // Authoritative identity values per global CLAUDE.md and INVENTORY.md.
  // Phone (831) 718-9122, EIN 27-1154816, three addresses, hours placeholder.
  const footerHTML = `
    <footer class="footer">
      <div class="container">
        <div class="footer-top">
          <div class="footer-brand">
            <div class="logo">
              <div class="logo-mark" style="background:white;color:var(--blue)">P</div>
              <div class="logo-text">
                <span class="name" style="color:white">Peace of Mind</span>
                <span class="sub" style="color:rgba(255,255,255,.55)">Dog Rescue</span>
              </div>
            </div>
            <p>A 501(c)(3) nonprofit rescuing senior dogs and helping seniors and their dogs stay together longer. Serving the Central Coast of California since 2009.</p>
            <div class="footer-contact">
              <div><a href="tel:+18317189122">(831) 718-9122</a></div>
              <div><a href="mailto:info@peaceofminddogrescue.org">info@peaceofminddogrescue.org</a></div>
            </div>
            <div class="socials">
              <a href="https://www.facebook.com/peaceofminddogrescue" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9v-2.9h2.5v-2.2c0-2.5 1.5-3.9 3.7-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6v1.9h2.7l-.4 2.9h-2.3v7A10 10 0 0 0 22 12z"/></svg></a>
              <a href="https://www.instagram.com/peaceofminddogrescue" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.2c3.2 0 3.6 0 4.8.1 1.2.1 1.8.2 2.2.4.6.2 1 .5 1.5 1s.8.9 1 1.5c.2.4.4 1 .4 2.2.1 1.2.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 1.2-.2 1.8-.4 2.2-.2.6-.5 1-1 1.5s-.9.8-1.5 1c-.4.2-1 .4-2.2.4-1.2.1-1.6.1-4.8.1s-3.6 0-4.8-.1c-1.2-.1-1.8-.2-2.2-.4a4 4 0 0 1-1.5-1 4 4 0 0 1-1-1.5c-.2-.4-.4-1-.4-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.8c.1-1.2.2-1.8.4-2.2.2-.6.5-1 1-1.5s.9-.8 1.5-1c.4-.2 1-.4 2.2-.4 1.2-.1 1.6-.1 4.8-.1zm0 1.8c-3.1 0-3.5 0-4.7.1-1.1.1-1.7.2-2.1.4-.5.2-.9.5-1.3.9s-.6.8-.9 1.3c-.2.4-.3 1-.4 2.1-.1 1.2-.1 1.6-.1 4.7s0 3.5.1 4.7c.1 1.1.2 1.7.4 2.1.2.5.5.9.9 1.3s.8.6 1.3.9c.4.2 1 .3 2.1.4 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c1.1-.1 1.7-.2 2.1-.4.5-.2.9-.5 1.3-.9s.6-.8.9-1.3c.2-.4.3-1 .4-2.1.1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c-.1-1.1-.2-1.7-.4-2.1a3.5 3.5 0 0 0-.9-1.3 3.5 3.5 0 0 0-1.3-.9c-.4-.2-1-.3-2.1-.4-1.2-.1-1.6-.1-4.7-.1zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 1.8a3.2 3.2 0 1 0 0 6.4 3.2 3.2 0 0 0 0-6.4zm5.2-3.4a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4z"/></svg></a>
              <a href="https://www.youtube.com/@peaceofminddogrescue" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M23 7.2a2.8 2.8 0 0 0-2-2C19.2 4.7 12 4.7 12 4.7s-7.2 0-9 .5a2.8 2.8 0 0 0-2 2A29 29 0 0 0 .5 12 29 29 0 0 0 1 16.8a2.8 2.8 0 0 0 2 2c1.8.5 9 .5 9 .5s7.2 0 9-.5a2.8 2.8 0 0 0 2-2A29 29 0 0 0 23.5 12 29 29 0 0 0 23 7.2zM9.7 15.4V8.6l6.2 3.4z"/></svg></a>
            </div>
          </div>
          <div>
            <h5>Adopt</h5>
            <ul>
              <li><a href="${rel("adopt.html")}">Available Dogs</a></li>
              <li><a href="${rel("process.html")}">Adoption Process</a></li>
              <li><a href="${rel("events.html")}">Adoption Events</a></li>
              <li><a href="${rel("courtesy-listings.html")}">Courtesy Listings</a></li>
              <li><a href="${rel("hospice.html")}">Hospice</a></li>
              <li><a href="${rel("adopted.html")}">Recently Adopted</a></li>
            </ul>
          </div>
          <div>
            <h5>Get Involved</h5>
            <ul>
              <li><a href="${rel("donate.html")}">Donate</a></li>
              <li><a href="${rel("foster.html")}">Foster</a></li>
              <li><a href="${rel("volunteer.html")}">Volunteer</a></li>
              <li><a href="${rel("helping-paw.html")}">Helping Paw Program</a></li>
              <li><a href="${rel("benefit-shop.html")}">Benefit Shop</a></li>
              <li><a href="${rel("events.html")}">Events</a></li>
            </ul>
          </div>
          <div>
            <h5>About</h5>
            <ul>
              <li><a href="${rel("about.html")}">Our Story</a></li>
              <li><a href="${rel("about.html")}#team">Team</a></li>
              <li><a href="${rel("media.html")}">In the Media</a></li>
              <li><a href="${rel("testimonials.html")}">Testimonials</a></li>
              <li><a href="${rel("jobs.html")}">Job Openings</a></li>
              <li><a href="${rel("contact.html")}">Contact</a></li>
              <li><a href="${rel("surrender.html")}">Placing Your Dog</a></li>
            </ul>
          </div>
          <div class="footer-locations">
            <h5>Visit Us</h5>
            <ul class="footer-addresses">
              <li>
                <strong>Patricia J. Bauer Center</strong>
                <span>615 Forest Ave</span>
                <span>Pacific Grove, CA 93950</span>
              </li>
              <li>
                <strong>Boand Veterinary Clinic</strong>
                <span>1251 10th St</span>
                <span>Monterey, CA</span>
              </li>
              <li>
                <strong>Benefit Shop</strong>
                <span>223 Grand Ave</span>
                <span>Pacific Grove, CA</span>
              </li>
            </ul>
            <p class="footer-hours">Hours by appointment. Call (831) 718-9122 to arrange a visit.</p>
          </div>
        </div>
        <div class="footer-bottom">
          <span>&copy; ${new Date().getFullYear()} Peace of Mind Dog Rescue. 501(c)(3) nonprofit. EIN 27-1154816.</span>
          <div class="footer-links-row">
            <a href="${rel("privacy.html")}">Privacy</a>
            <a href="${rel("terms.html")}">Terms</a>
            <a href="${rel("contact.html")}">Contact</a>
          </div>
        </div>
      </div>
    </footer>
  `;

  // ---- INJECT ----
  // Skip link first.
  if (!document.querySelector(".skip-link")) {
    document.body.insertAdjacentHTML("afterbegin", skipHTML);
  }

  // Ensure the skip-link has a target. Pages may explicitly wrap their content
  // in <main id="main">; if not, promote the first content section.
  if (!document.getElementById("main")) {
    const target = document.querySelector("body > header, body > section, body > main");
    if (target) target.id = "main";
  }

  // Replace existing nav if present, otherwise prepend.
  const existingNav = document.querySelector("nav.nav");
  if (existingNav) existingNav.outerHTML = navHTML;
  else {
    const skip = document.querySelector(".skip-link");
    if (skip) skip.insertAdjacentHTML("afterend", navHTML);
    else document.body.insertAdjacentHTML("afterbegin", navHTML);
  }

  const existingFooter = document.querySelector("footer.footer");
  if (existingFooter) existingFooter.outerHTML = footerHTML;
  else document.body.insertAdjacentHTML("beforeend", footerHTML);

  // ---- BEHAVIOR ----
  // Scroll state
  const navEl = document.getElementById("site-nav");
  const onScroll = () => {
    if (!navEl) return;
    if (window.scrollY > 16) navEl.classList.add("scrolled");
    else navEl.classList.remove("scrolled");
  };
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  // Mobile toggle
  const toggle = document.querySelector(".nav-toggle");
  const mobile = document.getElementById("nav-mobile");
  if (toggle && mobile) {
    toggle.addEventListener("click", () => {
      const open = mobile.classList.toggle("open");
      toggle.classList.toggle("open", open);
      toggle.setAttribute("aria-expanded", String(open));
      mobile.hidden = !open;
    });
    // Close on link click.
    mobile.querySelectorAll("a").forEach(a => a.addEventListener("click", () => {
      mobile.classList.remove("open");
      toggle.classList.remove("open");
      toggle.setAttribute("aria-expanded", "false");
      mobile.hidden = true;
    }));
    // Close on Escape.
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && mobile.classList.contains("open")) {
        mobile.classList.remove("open");
        toggle.classList.remove("open");
        toggle.setAttribute("aria-expanded", "false");
        mobile.hidden = true;
        toggle.focus();
      }
    });
  }

  // Submenu hover/focus reveal handled by CSS, but expose aria-expanded
  // state on the parent anchor for screen readers.
  document.querySelectorAll(".nav-item.has-children").forEach(group => {
    const trigger = group.querySelector(":scope > a");
    if (!trigger) return;
    const setExpanded = (val) => trigger.setAttribute("aria-expanded", String(val));
    group.addEventListener("mouseenter", () => setExpanded(true));
    group.addEventListener("mouseleave", () => setExpanded(false));
    group.addEventListener("focusin", () => setExpanded(true));
    group.addEventListener("focusout", (e) => {
      if (!group.contains(e.relatedTarget)) setExpanded(false);
    });
  });

  // ---- DOG GALLERY LIGHTBOX ----
  // Vanilla-JS lightbox. Activates on any .dog-gallery present on the page.
  // Designed for 1:1 swap to @wordpress/interactivity later.
  const galleries = document.querySelectorAll(".dog-gallery");
  if (galleries.length) {
    const overlay = document.createElement("div");
    overlay.className = "dog-lightbox";
    overlay.setAttribute("role", "dialog");
    overlay.setAttribute("aria-modal", "true");
    overlay.setAttribute("aria-label", "Photo viewer");
    overlay.innerHTML = `
      <button type="button" class="dog-lightbox-prev" aria-label="Previous photo">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
      </button>
      <img alt="" />
      <button type="button" class="dog-lightbox-next" aria-label="Next photo">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
      </button>
      <button type="button" class="dog-lightbox-close" aria-label="Close photo viewer">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
      </button>
      <div class="dog-lightbox-counter" aria-live="polite"></div>
    `;
    document.body.appendChild(overlay);
    const imgEl = overlay.querySelector("img");
    const counterEl = overlay.querySelector(".dog-lightbox-counter");

    let currentList = [];
    let currentIndex = 0;
    let lastFocus = null;

    const show = (idx) => {
      if (!currentList.length) return;
      currentIndex = (idx + currentList.length) % currentList.length;
      const photo = currentList[currentIndex];
      imgEl.src = photo.src;
      imgEl.alt = photo.alt || "";
      counterEl.textContent = `${currentIndex + 1} / ${currentList.length}`;
    };

    const open = (list, idx) => {
      currentList = list;
      lastFocus = document.activeElement;
      overlay.classList.add("is-open");
      document.body.style.overflow = "hidden";
      show(idx);
      overlay.querySelector(".dog-lightbox-close").focus();
    };

    const close = () => {
      overlay.classList.remove("is-open");
      document.body.style.overflow = "";
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    };

    galleries.forEach(gallery => {
      const buttons = Array.from(gallery.querySelectorAll("button"));
      const list = buttons.map(btn => {
        const img = btn.querySelector("img");
        return { src: btn.dataset.full || (img && img.src) || "", alt: (img && img.alt) || "" };
      });
      buttons.forEach((btn, i) => {
        btn.addEventListener("click", () => open(list, i));
      });
    });

    overlay.querySelector(".dog-lightbox-close").addEventListener("click", close);
    overlay.querySelector(".dog-lightbox-prev").addEventListener("click", () => show(currentIndex - 1));
    overlay.querySelector(".dog-lightbox-next").addEventListener("click", () => show(currentIndex + 1));
    overlay.addEventListener("click", (e) => { if (e.target === overlay) close(); });
    document.addEventListener("keydown", (e) => {
      if (!overlay.classList.contains("is-open")) return;
      if (e.key === "Escape") close();
      else if (e.key === "ArrowLeft") show(currentIndex - 1);
      else if (e.key === "ArrowRight") show(currentIndex + 1);
      else if (e.key === "Tab") {
        // Trap focus inside the overlay.
        const focusables = overlay.querySelectorAll("button");
        const first = focusables[0];
        const last = focusables[focusables.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });
  }
})();
