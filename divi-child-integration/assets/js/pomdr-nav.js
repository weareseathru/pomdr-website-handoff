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

  /* ---- Nav dropdowns: hover to open on a mouse, click/tap and keyboard
     everywhere (never hover-only), Esc and outside-click close, one open at a
     time. ---- */
  (function () {
    var items = document.querySelectorAll(".nav-item.has-drop");
    if (!items.length) return;

    var caret = function (item) { return item.querySelector(".nav-caret"); };
    var drop  = function (item) { return item.querySelector(".nav-drop"); };

    function open(item) {
      var c = caret(item), d = drop(item);
      if (c) c.setAttribute("aria-expanded", "true");
      if (d) d.hidden = false;
    }
    function close(item) {
      var c = caret(item), d = drop(item);
      if (c) c.setAttribute("aria-expanded", "false");
      if (d) d.hidden = true;
    }
    function closeAll(except) {
      items.forEach(function (it) { if (it !== except) close(it); });
    }

    // Only wire hover on devices that genuinely hover with a fine pointer, so
    // touch users are not stuck opening a menu they meant to tap through.
    var canHover = window.matchMedia && window.matchMedia("(hover: hover) and (pointer: fine)").matches;

    items.forEach(function (item) {
      var c = caret(item), hideTimer;

      if (c) c.addEventListener("click", function (e) {
        e.stopPropagation();
        var isOpen = c.getAttribute("aria-expanded") === "true";
        closeAll(item);
        if (isOpen) { close(item); } else { open(item); }
      });

      if (canHover) {
        item.addEventListener("mouseenter", function () { clearTimeout(hideTimer); closeAll(item); open(item); });
        item.addEventListener("mouseleave", function () { hideTimer = setTimeout(function () { close(item); }, 140); });
      }

      // Keyboard: close when focus leaves the whole item (tabbing past it).
      item.addEventListener("focusout", function (e) {
        if (!item.contains(e.relatedTarget)) close(item);
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
