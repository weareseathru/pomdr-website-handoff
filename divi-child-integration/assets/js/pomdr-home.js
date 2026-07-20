(function () {
  "use strict";
  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Nav scrolled-state and the mobile drawer are handled site-wide by
     pomdr-nav.js. They are intentionally NOT bound here: binding the same
     .nav-toggle twice made the drawer open then instantly close on the
     homepage. Scroll-reveal is likewise handled once by a11y.js. */

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
      ctas: '<a href="/adopt/" class="btn btn-ghost">Adopt <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a><a href="/foster/" class="btn btn-ghost">Foster</a><a href="/adopt/" class="btn btn-ghost">See all dogs</a>' },
    { tag: "Helping Paw · Support for senior guardians",
      title: "<div>Helping seniors</div><div>and their dogs stay</div><div><em>together</em> longer.</div>",
      sub: "Walking, vet rides, financial assistance, and temporary fosters, so guardians and their dogs never have to say goodbye too soon.",
      ctas: '<a href="/adopt/" class="btn btn-ghost">Adopt <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a><a href="/foster/" class="btn btn-ghost">Foster</a><a href="/adopt/" class="btn btn-ghost">See all dogs</a>' },
    { tag: "Our Mission · Since 2009",
      title: "<div>A lifetime</div><div><em>commitment</em>,</div><div>every time.</div>",
      sub: "Every dog in our care is ours for life. If a placement does not work, for any reason, ever, they come home to us.",
      ctas: '<a href="/adopt/" class="btn btn-ghost">Adopt <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a><a href="/foster/" class="btn btn-ghost">Foster</a><a href="/adopt/" class="btn btn-ghost">See all dogs</a>' },
    { tag: "Foster · Donate · Volunteer",
      title: "<div>Be the reason</div><div>a <em>gray muzzle</em></div><div>finds home.</div>",
      sub: "Foster a dog. Make a gift. Walk a senior pup. Three ways to change a life. Pick the one that fits yours.",
      ctas: '<a href="/adopt/" class="btn btn-ghost">Adopt <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a><a href="/foster/" class="btn btn-ghost">Foster</a><a href="/adopt/" class="btn btn-ghost">See all dogs</a>' }
  ];
  var heroSlides = document.querySelectorAll('.hero-slide');
  var dots = document.querySelectorAll('#hero-progress button[data-i]');
  var elTitle = document.getElementById('hero-title');
  var elCtas = document.getElementById('hero-ctas');
  var elCur = document.getElementById('hero-cur');
  var idx = 0, timer = null, paused = false;
  var pad = function (n) { return (n < 10 ? '0' : '') + n; };
  function setSlide(i) {
    idx = (i + SLIDES.length) % SLIDES.length;
    heroSlides.forEach(function (s, k) { s.classList.toggle('active', k === idx); });
    dots.forEach(function (d, k) { d.classList.toggle('active', k === idx); });
    var s = SLIDES[idx];
    if (elTitle) elTitle.innerHTML = s.title;
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
        ? '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M5 3l14 9-14 9z"/></svg>'
        : '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M6 4h4v16H6zM14 4h4v16h-4z"/></svg>';
      schedule();
    });
    schedule();
  }

  /* YouTube videos: click a card and it becomes the featured player (a stage
     is inserted at the head of the grid, the other cards slide into a side
     rail). Iframes only load on click, privacy-friendly via youtube-nocookie. */
  var videoGrid = document.getElementById('video-grid');
  if (videoGrid) {
    var playIn = function (card) {
      var id = card.getAttribute('data-youtube-id');
      if (!id || id === 'PLACEHOLDER') return;
      var stage = videoGrid.querySelector('.video-stage');
      if (!stage) {
        stage = document.createElement('div');
        stage.className = 'video-stage';
        videoGrid.insertBefore(stage, videoGrid.firstChild);
        videoGrid.classList.add('has-player');
      }
      stage.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0"' +
        ' allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen' +
        ' title="' + (card.getAttribute('data-title') || 'POMDR video') + '"></iframe>';
      videoGrid.querySelectorAll('.video-card').forEach(function (c) {
        c.classList.toggle('now-playing', c === card);
      });
      stage.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    };
    videoGrid.querySelectorAll('.video-card').forEach(function (card) {
      card.setAttribute('role', 'button');
      card.setAttribute('tabindex', '0');
      card.addEventListener('click', function () { playIn(card); });
      card.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); playIn(card); }
      });
    });
  }

  /* Happy Tails hover (lift, grow, reverse to purple) is handled purely in CSS. */

  /* The paw trail is handled by its own module (paw-trail.js), loaded below. */
})();
