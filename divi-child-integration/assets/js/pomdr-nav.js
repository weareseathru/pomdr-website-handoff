/*
 * POMDR nav supplement
 *
 * Loaded on every page on top of Divi's own header/menu. This file is a
 * progressive enhancement only. The core navigation is Divi's, which works
 * without JS.
 *
 * What it does today:
 *   - Adds a "scrolled" class to the Divi header once the page scrolls past a
 *     small threshold, so the header can gain a shadow / tighten (matches the
 *     prototype nav behavior). Honors prefers-reduced-motion by still toggling
 *     the class (the class change is not itself motion; CSS decides transitions).
 *
 * What is deliberately deferred until the local Divi mirror exists:
 *   - Wiring POMDR mobile-drawer affordances onto Divi's mobile menu markup.
 *     We do not yet know Divi's exact menu DOM on this install, and guessing
 *     risks breaking the working menu. Build that against the mirror.
 *
 * The canonical nav model (labels, order, submenu structure) lives in the
 * prototype at pomdr-website/project/pomdr-layout.js and should be mirrored
 * into the Divi menu via Appearance > Menus, not recreated in JS.
 */
(function () {
	'use strict';

	// Divi's main header. Fall back to a generic banner if the id ever changes.
	var header =
		document.getElementById('main-header') ||
		document.querySelector('header[role="banner"], .et-l--header, header');

	if (!header) {
		return;
	}

	var THRESHOLD = 16;
	var ticking = false;

	function update() {
		if (window.scrollY > THRESHOLD) {
			header.classList.add('scrolled');
		} else {
			header.classList.remove('scrolled');
		}
		ticking = false;
	}

	function onScroll() {
		if (!ticking) {
			window.requestAnimationFrame(update);
			ticking = true;
		}
	}

	window.addEventListener('scroll', onScroll, { passive: true });
	update();
})();
