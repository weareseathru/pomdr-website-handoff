/*
 * POMDR chrome behavior: mobile drawer toggle + nav scroll state.
 * Progressive enhancement for the header injected by inc/chrome.php. The links
 * work without JS; this only powers the mobile drawer and the scrolled shadow.
 */
(function () {
  var nav = document.getElementById('site-nav');

  // Scrolled state (tighten/shadow), matching the prototype.
  var onScroll = function () { if (nav) nav.classList.toggle('scrolled', window.scrollY > 16); };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // Mobile drawer.
  var toggle = document.querySelector('.nav-toggle');
  var mobile = document.getElementById('nav-mobile');
  if (toggle && mobile) {
    var setMobile = function (open) {
      mobile.classList.toggle('open', open);
      toggle.classList.toggle('open', open);
      toggle.setAttribute('aria-expanded', String(open));
      mobile.hidden = !open;
    };
    toggle.addEventListener('click', function () {
      setMobile(!mobile.classList.contains('open'));
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && mobile.classList.contains('open')) { setMobile(false); toggle.focus(); }
    });
    // Close the drawer when a link is tapped.
    mobile.addEventListener('click', function (e) {
      if (e.target.closest('a')) setMobile(false);
    });
  }

  /* ---- Nav dropdowns: click-to-open (no hover-only), Esc and outside-click
     close, one open at a time. ---- */
  (function () {
    var carets = document.querySelectorAll(".nav-caret");
    if (!carets.length) return;
    function closeAll(except) {
      carets.forEach(function (c) {
        if (c === except) return;
        c.setAttribute("aria-expanded", "false");
        var d = c.parentElement.querySelector(".nav-drop");
        if (d) d.hidden = true;
      });
    }
    carets.forEach(function (c) {
      c.addEventListener("click", function (e) {
        e.stopPropagation();
        var d = c.parentElement.querySelector(".nav-drop");
        var open = c.getAttribute("aria-expanded") === "true";
        closeAll(c);
        c.setAttribute("aria-expanded", open ? "false" : "true");
        if (d) d.hidden = open;
      });
    });
    document.addEventListener("click", function (e) {
      if (!e.target.closest(".nav-item.has-drop")) closeAll(null);
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape") closeAll(null);
    });
  })();

})();
