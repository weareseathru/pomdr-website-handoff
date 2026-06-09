/*
 * POMDR gallery lightbox
 *
 * Progressive enhancement for the dog photo gallery on single pet pages.
 * Markup is emitted by single-pets.php:
 *
 *   <div class="dog-gallery">
 *     <button class="gallery-thumb" data-full="..." data-caption="...">
 *       <img src="(medium)" alt="...">
 *     </button>
 *     ...
 *   </div>
 *
 * Without JS the medium thumbnails are still visible and the buttons are
 * inert, so the page is fully usable. With JS, clicking a thumb opens an
 * accessible overlay showing the full image. Closes on the close button,
 * Escape, or a backdrop click, and restores focus to the thumb that opened it.
 */
(function () {
	'use strict';

	var thumbs = document.querySelectorAll('.dog-gallery .gallery-thumb');
	if (!thumbs.length) {
		return;
	}

	var lastFocused = null;
	var overlay = null;
	var imageEl = null;
	var captionEl = null;
	var closeBtn = null;

	function buildOverlay() {
		overlay = document.createElement('div');
		overlay.className = 'pomdr-lightbox';
		overlay.setAttribute('role', 'dialog');
		overlay.setAttribute('aria-modal', 'true');
		overlay.setAttribute('aria-label', 'Photo viewer');
		overlay.hidden = true;

		var figure = document.createElement('figure');
		figure.className = 'pomdr-lightbox__figure';

		imageEl = document.createElement('img');
		imageEl.className = 'pomdr-lightbox__img';
		imageEl.alt = '';

		captionEl = document.createElement('figcaption');
		captionEl.className = 'pomdr-lightbox__caption';

		closeBtn = document.createElement('button');
		closeBtn.type = 'button';
		closeBtn.className = 'pomdr-lightbox__close';
		closeBtn.setAttribute('aria-label', 'Close photo viewer');
		closeBtn.innerHTML = '×'; // multiplication sign as an X glyph

		figure.appendChild(imageEl);
		figure.appendChild(captionEl);
		overlay.appendChild(closeBtn);
		overlay.appendChild(figure);
		document.body.appendChild(overlay);

		closeBtn.addEventListener('click', close);
		overlay.addEventListener('click', function (e) {
			if (e.target === overlay) {
				close();
			}
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && !overlay.hidden) {
				close();
			}
		});
	}

	function open(thumb) {
		if (!overlay) {
			buildOverlay();
		}
		lastFocused = thumb;
		imageEl.src = thumb.getAttribute('data-full') || thumb.querySelector('img').src;
		var caption = thumb.getAttribute('data-caption') || '';
		imageEl.alt = caption;
		captionEl.textContent = caption;
		captionEl.hidden = !caption;
		overlay.hidden = false;
		document.documentElement.classList.add('pomdr-lightbox-open');
		closeBtn.focus();
	}

	function close() {
		if (!overlay || overlay.hidden) {
			return;
		}
		overlay.hidden = true;
		document.documentElement.classList.remove('pomdr-lightbox-open');
		if (lastFocused) {
			lastFocused.focus();
		}
	}

	Array.prototype.forEach.call(thumbs, function (thumb) {
		thumb.addEventListener('click', function () {
			open(thumb);
		});
	});
})();
