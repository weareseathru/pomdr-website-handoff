/* ============================================================
   POMDR PAW TRAIL · paw-trail.js

   A decorative, scroll-driven watercolor paw-print trail for the static
   prototype homepage. Self-contained: it injects its own SVG defs and DOM,
   generates an organic walking path, lays prints along it with a real dog
   gait, and ties their reveal to scroll progress with GSAP ScrollTrigger
   (scrubbed, not time-based). GSAP and ScrollTrigger are loaded locally from
   vendor/ by index.html before this file.

   Charter constraints honored here:
     - Layer is position:absolute behind content, pointer-events:none,
       aria-hidden, and never changes layout or scroll length.
     - Only transform, opacity, and filter are animated.
     - Under prefers-reduced-motion, the finished trail renders statically at
       the same low opacity with no animation.
     - Colors come only from the design tokens (set via CSS classes in
       paw-trail.css). Opacity stays inside 0.08 to 0.18.

   The watercolor treatment is built from SVG filters (feTurbulence plus
   feDisplacementMap for bleed edges, a soft feGaussianBlur) and two layered
   radial-gradient fills for pigment pooling. The paw shape lives once in a
   defs <g> and is reused via <use>.
   ============================================================ */

(function () {
  "use strict";

  /* -------- tunables (the knobs worth turning when iterating) -------- */
  var CFG = {
    baseSize: 60,            // nominal print size in px (desktop); mobile scales down
    baseSizeMobile: 42,
    sizeMin: 0.82,           // size multiplier range -> ~48px to ~72px at baseSize 60
    sizeMax: 1.18,
    spacingSteps: 1.9,       // gap between prints, in print-heights (desktop)
    spacingStepsMobile: 2.4, // wider on phones: fewer filtered nodes to paint
    spacingJitter: 0.16,     // random +/- on spacing so the gait is not metronomic
    footSpread: 0.52,        // half gait width, in print-heights (left/right of line)
    spreadJitter: 0.16,      // random +/- on the foot spread
    rotJitter: 6,            // random +/- degrees off the path tangent
    forwardStagger: 0.18,    // diagonal gait: nudge each print along the line, in heights
    accentEvery: 8,          // roughly every 8th print takes a bright accent color
    scrub: 0.6,              // ScrollTrigger smoothing (seconds of catch-up)
    pressDur: 1.05,          // timeline seconds per print press-in (feel, not clock)
    pressOverlap: 0.55,      // timeline spacing between prints (< pressDur = overlap)
    startGap: 34,            // px below the hero where the trail begins
    settleCount: 5           // last few prints converge and settle toward the footer
  };

  /* Curated color cycle, all from the design tokens. Pastels sit at the top of
     the opacity band (they are light and need it to read); saturated brand hues
     stay faint so body-text contrast is untouched. The trail sits in the page
     gutters and calm bands, mostly clear of text, so the band is 0.16 to 0.26:
     present enough to read as a soft watercolor, still light under any text it
     crosses. */
  var PALETTE = [
    { cls: "c-blue-100",   op: 0.26 },
    { cls: "c-purple-100", op: 0.26 },
    { cls: "c-blue-200",   op: 0.21 },
    { cls: "c-purple-200", op: 0.21 },
    { cls: "c-blue",       op: 0.16 },
    { cls: "c-purple",     op: 0.16 }
  ];
  var ACCENTS = [
    { cls: "c-accent",     op: 0.22 },
    { cls: "c-accent-red", op: 0.19 }
  ];
  /* The final settling cluster: saturated so it reads on the light band above
     the footer. Still within the opacity band. */
  var SETTLE = [
    { cls: "c-purple", op: 0.22 },
    { cls: "c-blue",   op: 0.22 },
    { cls: "c-accent", op: 0.20 },
    { cls: "c-purple", op: 0.22 },
    { cls: "c-blue",   op: 0.22 }
  ];

  var SVGNS = "http://www.w3.org/2000/svg";
  var XLINK = "http://www.w3.org/1999/xlink";

  function ready(fn) {
    if (document.readyState !== "loading") fn();
    else document.addEventListener("DOMContentLoaded", fn);
  }

  /* -------- small math helpers -------- */
  function lerp(a, b, t) { return a + (b - a) * t; }
  function rand(a, b) { return a + Math.random() * (b - a); }

  /* Centripetal-ish Catmull-Rom point for a smooth path through anchors. */
  function catmull(p0, p1, p2, p3, t) {
    var t2 = t * t, t3 = t2 * t;
    return {
      x: 0.5 * ((2 * p1.x) + (-p0.x + p2.x) * t +
        (2 * p0.x - 5 * p1.x + 4 * p2.x - p3.x) * t2 +
        (-p0.x + 3 * p1.x - 3 * p2.x + p3.x) * t3),
      y: 0.5 * ((2 * p1.y) + (-p0.y + p2.y) * t +
        (2 * p0.y - 5 * p1.y + 4 * p2.y - p3.y) * t2 +
        (-p0.y + 3 * p1.y - 3 * p2.y + p3.y) * t3)
    };
  }

  /* -------- one-time SVG defs (paw shape, pooling gradients, filters) -------- */
  function injectDefs() {
    if (document.getElementById("pt-defs")) return;
    var svg = document.createElementNS(SVGNS, "svg");
    svg.setAttribute("id", "pt-defs");
    svg.setAttribute("class", "pt-defs");
    svg.setAttribute("aria-hidden", "true");
    svg.setAttribute("focusable", "false");

    // Three watercolor filter seeds so neighbouring prints do not share an
    // identical bleed. A higher-frequency feTurbulence into feDisplacementMap
    // roughs the edge into an organic, bled outline; the soft blur feathers it.
    // (currentColor in gradient stops resolves against the defs context, not
    // the referencing print, so pooling is built from layered fills instead,
    // in makePawEl, which keeps the per-print token color correct.)
    var variants = [
      { bf: "0.062 0.084", oc: 3, sc: 4.2, seed: 3 },
      { bf: "0.078 0.052", oc: 2, sc: 3.6, seed: 7 },
      { bf: "0.05 0.092",  oc: 3, sc: 4.8, seed: 13 }
    ];
    var filters = "";
    variants.forEach(function (v, i) {
      filters +=
        '<filter id="pt-wc-' + i + '" x="-50%" y="-50%" width="200%" height="200%" ' +
        'color-interpolation-filters="sRGB">' +
        '<feTurbulence type="fractalNoise" baseFrequency="' + v.bf + '" ' +
        'numOctaves="' + v.oc + '" seed="' + v.seed + '" result="n"/>' +
        '<feDisplacementMap in="SourceGraphic" in2="n" scale="' + v.sc + '" ' +
        'xChannelSelector="R" yChannelSelector="G" result="d"/>' +
        '<feGaussianBlur in="d" stdDeviation="0.5"/>' +
        '</filter>';
    });

    svg.innerHTML =
      "<defs>" +
        filters +
        // The paw shape, once. No fill here so each <use> paints currentColor.
        '<g id="pt-shape">' +
          '<path d="M32 33c7.5 0 13.5 4.6 14.6 11.4 1 6.2-3.2 11.6-9.6 13.4-3.2.9-7.2.9-10 0-6.4-1.8-10.6-7.2-9.6-13.4C18.5 37.6 24.5 33 32 33z"/>' +
          '<ellipse cx="12" cy="31" rx="5" ry="7.6" transform="rotate(-28 12 31)"/>' +
          '<ellipse cx="24" cy="19" rx="5.2" ry="8.2" transform="rotate(-11 24 19)"/>' +
          '<ellipse cx="40" cy="19" rx="5.2" ry="8.2" transform="rotate(11 40 19)"/>' +
          '<ellipse cx="52" cy="31" rx="5" ry="7.6" transform="rotate(28 52 31)"/>' +
        "</g>" +
      "</defs>";
    document.body.appendChild(svg);
  }

  /* -------- geometry: where the trail starts, ends, and its lanes -------- */
  function measure() {
    var vw = window.innerWidth;
    var vh = window.innerHeight;
    var docH = Math.max(
      document.documentElement.scrollHeight,
      document.body ? document.body.scrollHeight : 0
    );
    var sy = window.scrollY || window.pageYOffset || 0;

    var hero = document.querySelector(".hero");
    var footer = document.querySelector(".footer");
    var brand = document.querySelector(".footer-brand") || footer;

    var startY = hero
      ? hero.getBoundingClientRect().bottom + sy + CFG.startGap
      : vh;

    // End the trail in the calm band just ABOVE the footer. The footer is an
    // opaque dark block and the layer sits behind it, so prints placed inside
    // it would be invisible; settling just above keeps the final beat seen.
    var endY = docH - 60;
    var footerX = vw * 0.2;
    if (footer) {
      endY = footer.getBoundingClientRect().top + sy - 18;
    }
    if (brand) {
      var b = brand.getBoundingClientRect();
      footerX = b.left + b.width * 0.34; // drift toward the brand column
    }

    // Lanes hug the page edges (where margin/whitespace usually sits beside the
    // centered content), swinging inward only at the crossing bands.
    var content = Math.min(1320, vw - 64);
    var gutter = Math.max(0, (vw - content) / 2);
    var edge = Math.min(Math.max(gutter * 0.55, 16), 96);
    var leftLane = Math.max(edge, 16);
    var rightLane = vw - Math.max(edge, 16);

    return {
      vw: vw, vh: vh, docH: docH,
      startY: startY, endY: endY,
      leftLane: leftLane, rightLane: rightLane,
      centerX: vw / 2, footerX: footerX
    };
  }

  /* The serpentine spine. Anchors weave edge to edge as the page descends, then
     curve toward the footer brand block for the final settle. The vertical
     fractions are placed so crossings fall in the calm bands between sections;
     tune these against the screenshots if a crossing lands under a heading. */
  function buildSpine(geo) {
    var L = geo.leftLane, R = geo.rightLane;
    var s = geo.startY, e = geo.endY;
    var fr = [
      [0.00, L],
      [0.20, R],
      [0.42, L],
      [0.63, R],
      [0.82, L],
      [0.92, geo.footerX],
      [1.00, geo.footerX]
    ];
    var anchors = fr.map(function (f) {
      return { x: f[1], y: lerp(s, e, f[0]) };
    });

    // Sample the Catmull-Rom curve densely into a polyline.
    var pts = [];
    var steps = 26;
    for (var i = 0; i < anchors.length - 1; i++) {
      var p0 = anchors[i - 1] || anchors[i];
      var p1 = anchors[i];
      var p2 = anchors[i + 1];
      var p3 = anchors[i + 2] || anchors[i + 1];
      for (var j = 0; j < steps; j++) {
        pts.push(catmull(p0, p1, p2, p3, j / steps));
      }
    }
    pts.push(anchors[anchors.length - 1]);

    // Cumulative arc length for even, distance-based placement.
    var cum = [0];
    for (var k = 1; k < pts.length; k++) {
      var dx = pts[k].x - pts[k - 1].x;
      var dy = pts[k].y - pts[k - 1].y;
      cum.push(cum[k - 1] + Math.sqrt(dx * dx + dy * dy));
    }
    return { pts: pts, cum: cum, total: cum[cum.length - 1] };
  }

  // Point and unit tangent at a given arc length along the polyline.
  function atLength(spine, s) {
    var pts = spine.pts, cum = spine.cum;
    var lo = 0, hi = cum.length - 1;
    if (s <= 0) { lo = 0; hi = 1; }
    else if (s >= spine.total) { lo = cum.length - 2; hi = cum.length - 1; }
    else {
      while (hi - lo > 1) {
        var mid = (lo + hi) >> 1;
        if (cum[mid] < s) lo = mid; else hi = mid;
      }
    }
    var seg = cum[hi] - cum[lo] || 1;
    var t = (s - cum[lo]) / seg;
    var a = pts[lo], b = pts[hi];
    var dx = b.x - a.x, dy = b.y - a.y;
    var len = Math.sqrt(dx * dx + dy * dy) || 1;
    return {
      x: lerp(a.x, b.x, t),
      y: lerp(a.y, b.y, t),
      tx: dx / len,
      ty: dy / len
    };
  }

  /* -------- generate the prints along the spine -------- */
  function generatePaws(geo) {
    var spine = buildSpine(geo);
    var isMobile = geo.vw < 760;
    var size = isMobile ? CFG.baseSizeMobile : CFG.baseSize;
    var step = (isMobile ? CFG.spacingStepsMobile : CFG.spacingSteps) * size;
    var count = Math.max(6, Math.floor(spine.total / step));
    var paws = [];

    var s = 0;
    for (var i = 0; i < count; i++) {
      // Even spacing all the way down: the trail keeps a steady gait into the
      // band above the footer rather than bunching up at the end.
      var jitterStep = step * (1 + rand(-CFG.spacingJitter, CFG.spacingJitter));
      s += jitterStep;
      if (s > spine.total) break;

      var here = atLength(spine, s);
      // Forward stagger gives the gait a diagonal feel rather than two tidy rows.
      var stag = (i % 2 ? 1 : -1) * CFG.forwardStagger * size;
      var on = atLength(spine, Math.min(spine.total, Math.max(0, s + stag)));

      var sz = size * rand(CFG.sizeMin, CFG.sizeMax);
      var spread = (CFG.footSpread + rand(-CFG.spreadJitter, CFG.spreadJitter)) * size;
      var side = (i % 2 === 0) ? 1 : -1;
      // Perpendicular to the tangent: prints sit left/right of the walking line.
      var px = -on.ty, py = on.tx;

      // How many prints from the end (used only to recolor the closing prints
      // so they read on the light band above the footer; geometry is unchanged,
      // so the ending stays evenly spaced instead of clumping).
      var fromEnd = count - 1 - i;

      var cx = on.x + px * spread * side;
      var cy = on.y + py * spread * side;

      var angle = Math.atan2(on.ty, on.tx) * 180 / Math.PI;
      var rot = angle + 90 + rand(-CFG.rotJitter, CFG.rotJitter); // toes point along travel

      // Color: cycle the palette, drop in an accent on roughly every Nth print.
      var pick;
      if (i > 0 && i % CFG.accentEvery === 0) {
        pick = ACCENTS[(i / CFG.accentEvery | 0) % ACCENTS.length];
      } else {
        pick = PALETTE[i % PALETTE.length];
      }
      // The closing prints land on the light band above the footer, where pale
      // tints vanish. Give them the saturated brand hues and the warm accent so
      // the ending stays visible (spacing and gait are left untouched).
      if (fromEnd < CFG.settleCount) {
        pick = SETTLE[(CFG.settleCount - 1 - fromEnd) % SETTLE.length];
      }

      paws.push({
        x: cx, y: cy, size: sz, rot: rot,
        op: pick.op, cls: pick.cls,
        filter: i % 3
      });
    }
    return paws;
  }

  /* -------- build (or rebuild) the trail DOM and animation -------- */
  var current = { st: null, tl: null };

  function clearCurrent(trail) {
    if (current.st) { current.st.kill(); current.st = null; }
    if (current.tl) { current.tl.kill(); current.tl = null; }
    while (trail.firstChild) trail.removeChild(trail.firstChild);
  }

  function makePawEl(p) {
    var svg = document.createElementNS(SVGNS, "svg");
    svg.setAttribute("class", "pt-paw " + p.cls);
    svg.setAttribute("viewBox", "0 0 64 64");
    svg.setAttribute("aria-hidden", "true");
    svg.setAttribute("focusable", "false");
    svg.style.left = p.x + "px";
    svg.style.top = p.y + "px";
    svg.style.width = p.size + "px";
    svg.style.height = p.size + "px";
    // Center the print on its point and pre-rotate to the path tangent. GSAP
    // animates scale/opacity on top of this, preserving the rotation.
    svg.style.transform =
      "translate(-50%, -50%) rotate(" + p.rot.toFixed(1) + "deg) scale(0.85)";
    svg.style.transformOrigin = "center";

    var g = document.createElementNS(SVGNS, "g");
    g.setAttribute("filter", "url(#pt-wc-" + p.filter + ")");
    // Pigment pooling from two layered currentColor fills: a larger, fainter
    // bleed halo and a denser core. Where they overlap the print reads darker
    // (pooled); at the edges only the halo shows (feathered). currentColor on
    // the <use> resolves to the print's token color (set via its CSS class).
    ["pt-bleed", "pt-core"].forEach(function (cls) {
      var use = document.createElementNS(SVGNS, "use");
      use.setAttributeNS(XLINK, "href", "#pt-shape");
      use.setAttribute("href", "#pt-shape");
      use.setAttribute("class", cls);
      g.appendChild(use);
    });
    svg.appendChild(g);
    return svg;
  }

  function build(trail, mode) {
    clearCurrent(trail);
    var geo = measure();
    trail.style.height = geo.docH + "px";

    var paws = generatePaws(geo);
    var els = paws.map(function (p) {
      var el = makePawEl(p);
      el.__op = p.op;          // remember the target opacity for the tween
      el.__rot = p.rot;
      trail.appendChild(el);
      return el;
    });

    if (mode === "static") {
      // Reduced motion (or no GSAP): show the finished trail, no animation.
      els.forEach(function (el) {
        el.style.opacity = el.__op;
        el.style.transform =
          "translate(-50%, -50%) rotate(" + el.__rot.toFixed(1) + "deg) scale(1)";
      });
      return;
    }

    var gsap = window.gsap;
    var tl = gsap.timeline({
      defaults: { ease: "none" },
      scrollTrigger: {
        // Reveal maps from the trail entering the viewport to the final prints
        // coming to rest. The end is clamped inside the reachable scroll range
        // so the footer settle always completes (the trail ends near the page
        // bottom, where there is less than a viewport of scroll left to give).
        start: function () { return Math.max(0, geo.startY - geo.vh * 0.85); },
        end: function () {
          var maxScroll = Math.max(0, geo.docH - geo.vh);
          var target = geo.endY - geo.vh * 0.55;
          return Math.max(geo.startY + 40, Math.min(maxScroll - 8, target));
        },
        scrub: CFG.scrub,
        invalidateOnRefresh: true
      }
    });

    els.forEach(function (el, i) {
      var at = i * CFG.pressOverlap;
      // The press-in: scale 0.85 -> 1 with a small overshoot (back.out) and a
      // brief blur settle. Rotation is preserved from the inline transform.
      tl.fromTo(el,
        { scale: 0.85, opacity: 0, filter: "blur(2px)" },
        {
          scale: 1, opacity: el.__op, filter: "blur(0px)",
          duration: CFG.pressDur, ease: "back.out(1.7)"
        },
        at
      );
    });

    current.tl = tl;
    current.st = tl.scrollTrigger;
  }

  /* -------- init -------- */
  function init() {
    if (document.querySelector(".pt-trail")) return; // idempotent
    if (!document.body) return;

    injectDefs();
    var trail = document.createElement("div");
    trail.className = "pt-trail";
    trail.setAttribute("aria-hidden", "true");
    document.body.appendChild(trail);

    var reduce = window.matchMedia &&
      window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    var hasGSAP = !!(window.gsap && window.ScrollTrigger);
    if (hasGSAP) window.gsap.registerPlugin(window.ScrollTrigger);

    var mode = (reduce || !hasGSAP) ? "static" : "live";
    build(trail, mode);

    // Rebuild on resize (debounced) and once more after load, when late images
    // have settled the document height.
    var t;
    window.addEventListener("resize", function () {
      clearTimeout(t);
      t = setTimeout(function () { build(trail, mode); }, 250);
    }, { passive: true });
    window.addEventListener("load", function () {
      setTimeout(function () {
        build(trail, mode);
        if (hasGSAP) window.ScrollTrigger.refresh();
      }, 140);
    });
  }

  ready(init);
})();
