/* ============================================================
   POMDR Accessibility utility · a11y.js

   One small, dependency-free script loaded on every page (index.html links
   it directly; pomdr-layout.js injects it on the shared-layout pages). It
   does two things:

   1. Renders the floating accessibility toggle (bottom-right) that turns the
      opt-in senior comfort mode on or off, and remembers the choice.
   2. Drives the single scroll-reveal utility with one IntersectionObserver,
      fully disabled under prefers-reduced-motion.

   The visual treatment lives in a11y.css. This file only handles state and
   behavior. Core content never depends on it (progressive enhancement).
   ============================================================ */
(function () {
  "use strict";
  if (window.__pomdrA11y) return;            // run once per page
  window.__pomdrA11y = true;

  // Flag JS availability so CSS only hides .reveal content when this script
  // will actually reveal it (progressive enhancement: no JS, no hidden text).
  document.documentElement.classList.add("js");

  var STORAGE_KEY = "pomdr-a11y";
  var root = document.documentElement;
  var reduceMotion = window.matchMedia
    && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ---- Senior mode state ---- */
  function isOn() {
    try { return localStorage.getItem(STORAGE_KEY) === "senior"; } catch (e) { return false; }
  }
  function apply(on) {
    if (on) root.setAttribute("data-a11y", "senior");
    else root.removeAttribute("data-a11y");
  }
  // Apply the saved preference as early as this script runs.
  apply(isOn());

  /* ---- Floating toggle ---- */
  function buildToggle() {
    if (document.querySelector(".a11y-fab")) return;

    var live = document.createElement("div");
    live.className = "sr-only";
    live.setAttribute("aria-live", "polite");
    live.style.cssText = "position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap;";

    var btn = document.createElement("button");
    btn.type = "button";
    btn.className = "a11y-fab";
    // Icon (universal accessibility mark) is decorative; the text label is the
    // accessible name, so the icon is paired with visible text.
    btn.innerHTML =
      '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">' +
      '<path d="M12 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4zm9 5.5c0 .6-.4 1-1 1-2 0-3.9-.4-5-.7V14l2.2 6.4a1 1 0 0 1-1.9.7L13.4 16h-2.8l-1.9 5.1a1 1 0 0 1-1.9-.7L9 14V7.8c-1.1.3-3 .7-5 .7a1 1 0 0 1 0-2c2.3 0 4.6-.7 5.3-.9a5 5 0 0 1 1.7-.3h0a5 5 0 0 1 1.7.3c.7.2 3 .9 5.3.9a1 1 0 0 1 1 1z"/>' +
      '</svg>' +
      '<span class="a11y-fab__label"></span>';

    function sync() {
      var on = isOn();
      btn.setAttribute("aria-pressed", on ? "true" : "false");
      btn.querySelector(".a11y-fab__label").textContent = on
        ? "Larger text: on"
        : "Larger text";
      btn.setAttribute(
        "aria-label",
        on ? "Turn off larger text and spacing" : "Turn on larger text and spacing"
      );
    }

    btn.addEventListener("click", function () {
      var next = !isOn();
      try { localStorage.setItem(STORAGE_KEY, next ? "senior" : "off"); } catch (e) {}
      apply(next);
      sync();
      live.textContent = next
        ? "Larger text and spacing turned on."
        : "Larger text and spacing turned off.";
    });

    sync();
    document.body.appendChild(live);
    document.body.appendChild(btn);
  }

  /* ---- Single scroll-reveal utility ---- */
  function initReveal() {
    var items = document.querySelectorAll(".reveal");
    if (!items.length) return;
    if (reduceMotion || !("IntersectionObserver" in window)) {
      // No motion: show everything immediately.
      items.forEach(function (el) { el.classList.add("in"); });
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          e.target.classList.add("in");
          io.unobserve(e.target);
        }
      });
    }, { rootMargin: "0px 0px -10% 0px" });
    items.forEach(function (el) { io.observe(el); });

    // Safety net: content must NEVER stay hidden. If anything goes wrong with
    // the observer (or a future CSS change), every reveal is forced visible
    // after a few seconds. The animation is decorative; the content is not.
    setTimeout(function () {
      items.forEach(function (el) { el.classList.add("in"); });
    }, 4000);
  }

  /* ---- Deploy canary ---- */
  // The design tokens stylesheet sets --pom-build. If it is missing, the
  // design layer did not load (the silent failure mode of the 2026-09-01
  // deploy). Logged-in staff get a visible banner; visitors only a console
  // error, never UI.
  function checkBuild() {
    var v = "";
    try {
      v = getComputedStyle(document.documentElement)
        .getPropertyValue("--pom-build").trim();
    } catch (e) { return; }
    if (v) return;
    if (window.console && console.error) {
      console.error("POMDR: design CSS not loaded (missing --pom-build). Theme deploy is likely incomplete.");
    }
    if (document.body && document.body.classList.contains("admin-bar")) {
      var warn = document.createElement("div");
      warn.setAttribute("role", "alert");
      warn.style.cssText = "position:fixed;top:32px;left:0;right:0;z-index:99999;background:#a4372f;color:#fff;padding:10px 16px;font:700 15px/1.4 sans-serif;text-align:center;";
      warn.textContent = "POMDR deploy check: the design stylesheets are not loading on this page. The theme deploy is likely incomplete. Only logged-in users see this message.";
      document.body.appendChild(warn);
    }
  }

  function start() {
    buildToggle();
    initReveal();
    checkBuild();
  }
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", start);
  } else {
    start();
  }
})();
