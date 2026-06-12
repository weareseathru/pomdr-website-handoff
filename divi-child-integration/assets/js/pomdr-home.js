/* POMDR homepage behavior (hero carousel, video facade, favorite hearts).
   Extracted from the prototype. Nav/reveal are handled elsewhere (Divi nav,
   a11y.js). Guards mean absent elements simply no-op. */

(function () {
  "use strict";
  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Nav: scrolled state + mobile toggle */
  var nav = document.getElementById('site-nav');
  var onScroll = function () { if (nav) nav.classList.toggle('scrolled', window.scrollY > 16); };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
  var toggle = document.querySelector('.nav-toggle');
  var mobile = document.getElementById('nav-mobile');
  if (toggle && mobile) {
    var setMobile = function (open) {
      mobile.classList.toggle('open', open);
      toggle.classList.toggle('open', open);
      toggle.setAttribute('aria-expanded', String(open));
      mobile.hidden = !open;
    };
    toggle.addEventListener('click', function () { setMobile(!mobile.classList.contains('open')); });
    mobile.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { setMobile(false); }); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && mobile.classList.contains('open')) { setMobile(false); toggle.focus(); }
    });
  }

  /* Scroll-reveal is handled by the shared single utility in a11y.js
     (loaded below), so it is not duplicated here. */

  /* Favorite hearts (persisted) */
  var likes;
  try { likes = new Set(JSON.parse(localStorage.getItem('pomdr-likes') || '[]')); } catch (_) { likes = new Set(); }
  document.querySelectorAll('.heart').forEach(function (btn) {
    var name = btn.getAttribute('data-name');
    if (likes.has(name)) btn.classList.add('active');
    btn.addEventListener('click', function (e) {
      e.preventDefault(); e.stopPropagation();
      if (likes.has(name)) { likes.delete(name); btn.classList.remove('active'); }
      else { likes.add(name); btn.classList.add('active'); }
      try { localStorage.setItem('pomdr-likes', JSON.stringify([].concat(Array.from(likes)))); } catch (_) {}
    });
  });

  /* Hero rotator (side arrows navigate) */
  var SLIDES = [
    { tag: "Adoption · Senior dogs, ready to love",
      title: "<div>Senior dogs deserve</div><div>a <em>soft place</em></div><div>to land.</div>",
      sub: "Calm, gentle, ready to love again. The gray-muzzled companions waiting for their next chapter.",
      ctas: '<a href="adopt.html" class="btn btn-primary">Adopt a Dog <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a><a href="adopt.html" class="btn btn-ghost">See all dogs</a>' },
    { tag: "Helping Paw · Support for senior guardians",
      title: "<div>Helping seniors</div><div>and their dogs stay</div><div><em>together</em> longer.</div>",
      sub: "Walking, vet rides, financial assistance, and temporary fosters, so guardians and their dogs never have to say goodbye too soon.",
      ctas: '<a href="helping-paw.html" class="btn btn-primary">Helping Paw Program <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a><a href="helping-paw.html" class="btn btn-ghost">Request support</a>' },
    { tag: "Our Mission · Since 2009",
      title: "<div>A lifetime</div><div><em>commitment</em>,</div><div>every time.</div>",
      sub: "Every dog in our care is ours for life. If a placement does not work, for any reason, ever, they come home to us.",
      ctas: '<a href="about.html" class="btn btn-primary">Our Mission <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a><a href="about.html" class="btn btn-ghost">How we help</a>' },
    { tag: "Foster · Donate · Volunteer",
      title: "<div>Be the reason</div><div>a <em>gray muzzle</em></div><div>finds home.</div>",
      sub: "Foster a dog. Make a gift. Walk a senior pup. Three ways to change a life. Pick the one that fits yours.",
      ctas: '<a href="foster.html" class="btn btn-primary">Foster <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a><a href="donate.html" class="btn btn-purple">Donate</a><a href="volunteer.html" class="btn btn-ghost">Volunteer</a>' }
  ];
  var heroSlides = document.querySelectorAll('.hero-slide');
  var dots = document.querySelectorAll('#hero-progress button');
  var elTag = document.getElementById('hero-tag-text');
  var elTitle = document.getElementById('hero-title');
  var elSub = document.getElementById('hero-sub');
  var elCtas = document.getElementById('hero-ctas');
  var elCur = document.getElementById('hero-cur');
  var idx = 0, timer = null, paused = false;
  var pad = function (n) { return (n < 10 ? '0' : '') + n; };
  function setSlide(i) {
    idx = (i + SLIDES.length) % SLIDES.length;
    heroSlides.forEach(function (s, k) { s.classList.toggle('active', k === idx); });
    dots.forEach(function (d, k) { d.classList.toggle('active', k === idx); });
    var s = SLIDES[idx];
    if (elTag) elTag.textContent = s.tag;
    if (elTitle) elTitle.innerHTML = s.title;
    if (elSub) elSub.textContent = s.sub;
    if (elCtas) elCtas.innerHTML = s.ctas;
    if (elCur) elCur.textContent = pad(idx + 1);
  }
  function schedule() { if (timer) clearTimeout(timer); if (paused || reduceMotion) return; timer = setTimeout(function () { setSlide(idx + 1); schedule(); }, 6000); }
  if (heroSlides.length) {
    dots.forEach(function (d) { d.addEventListener('click', function () { setSlide(parseInt(d.getAttribute('data-i'), 10)); schedule(); }); });
    var prev = document.getElementById('hero-prev'), next = document.getElementById('hero-next'), pause = document.getElementById('hero-pause');
    if (prev) prev.addEventListener('click', function () { setSlide(idx - 1); schedule(); });
    if (next) next.addEventListener('click', function () { setSlide(idx + 1); schedule(); });
    if (pause) pause.addEventListener('click', function () {
      paused = !paused;
      pause.setAttribute('aria-label', paused ? 'Play slideshow' : 'Pause slideshow');
      pause.innerHTML = paused
        ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M5 3l14 9-14 9z"/></svg>'
        : '<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M6 4h4v16H6zM14 4h4v16h-4z"/></svg>';
      schedule();
    });
    schedule();
  }

  /* YouTube video facade: load the iframe only on click (fast first paint),
     play inline, privacy-friendly via youtube-nocookie. */
  document.querySelectorAll('.video-card').forEach(function (card) {
    card.setAttribute('role', 'button');
    card.setAttribute('tabindex', '0');
    var play = function () {
      var id = card.getAttribute('data-youtube-id');
      if (!id || id === 'PLACEHOLDER') return;
      var ifr = document.createElement('iframe');
      ifr.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0';
      ifr.setAttribute('allow', 'autoplay; encrypted-media; picture-in-picture; fullscreen');
      ifr.setAttribute('allowfullscreen', '');
      ifr.setAttribute('title', card.getAttribute('data-title') || 'POMDR video');
      card.innerHTML = '';
      card.appendChild(ifr);
    };
    card.addEventListener('click', play);
    card.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); play(); } });
  });

  /* Paw trail: size to the full page, reveal each paw on scroll */
  var trail = document.querySelector('.paw-trail');
  if (trail) {
    var syncHeight = function () {
      var h = Math.max(document.documentElement.scrollHeight, document.body ? document.body.scrollHeight : 0);
      trail.style.height = h + 'px';
    };
    syncHeight();
    window.addEventListener('resize', syncHeight, { passive: true });
    window.addEventListener('load', function () { setTimeout(syncHeight, 200); setTimeout(syncHeight, 900); });
    var paws = trail.querySelectorAll('.paw, .dog-cutout');
    if (!('IntersectionObserver' in window)) {
      paws.forEach(function (el) { el.classList.add('in'); });
    } else {
      var pio = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); pio.unobserve(e.target); } });
      }, { rootMargin: '0px 0px -8% 0px' });
      paws.forEach(function (el) { pio.observe(el); });
    }
  }
})();
