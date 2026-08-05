/**
 * HKLA front end. Vanilla JS, no dependencies, well under the 50KB budget.
 * Everything here is progressive enhancement: the site is fully usable
 * with JavaScript disabled.
 */
(function () {
	'use strict';

	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

	/* The "+" overlay menu. Full-screen when open, focus trapped, Escape
	   closes. This is the mobile navigation; a theme flag can enable it on
	   desktop too. */
	var toggle = document.querySelector('.nav-toggle');
	var nav = document.getElementById('site-nav');
	if (toggle && nav) {
		var closeMenu = function () {
			nav.classList.remove('is-open');
			document.body.classList.remove('menu-open');
			toggle.setAttribute('aria-expanded', 'false');
			toggle.focus();
		};
		toggle.addEventListener('click', function () {
			var open = nav.classList.toggle('is-open');
			document.body.classList.toggle('menu-open', open);
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			if (open) {
				var first = nav.querySelector('a');
				if (first) { first.focus(); }
			}
		});
		document.addEventListener('keydown', function (e) {
			if (!nav.classList.contains('is-open')) { return; }
			if (e.key === 'Escape') { closeMenu(); return; }
			if (e.key !== 'Tab') { return; }
			var focusables = nav.querySelectorAll('a');
			if (!focusables.length) { return; }
			var firstEl = toggle;
			var lastEl = focusables[focusables.length - 1];
			if (e.shiftKey && document.activeElement === firstEl) {
				e.preventDefault();
				lastEl.focus();
			} else if (!e.shiftKey && document.activeElement === lastEl) {
				e.preventDefault();
				firstEl.focus();
			}
		});
	}

	/* Home hero slideshow: slow crossfade, ~6s per slide. Reduced motion
	   pauses on the first frame. */
	var slides = document.querySelectorAll('.hero__slides .hero__slide');
	if (slides.length > 1 && !reducedMotion.matches) {
		var current = 0;
		slides[0].classList.add('is-current');
		setInterval(function () {
			slides[current].classList.remove('is-current');
			current = (current + 1) % slides.length;
			slides[current].classList.add('is-current');
		}, 6000);
	} else if (slides.length) {
		slides[0].classList.add('is-current');
	}

	/* Scroll reveals and the drawn-line sketch treatment.
	   Elements are only "armed" (hidden) when JS runs and motion is allowed,
	   so content is never invisible without JS or with reduced motion. */
	if (!reducedMotion.matches && 'IntersectionObserver' in window) {
		var revealables = document.querySelectorAll('.reveal');
		var sketches = document.querySelectorAll('.js-sketch');

		revealables.forEach(function (el) {
			el.classList.add('is-armed');
		});
		sketches.forEach(function (el) {
			el.classList.add('is-armed');
		});

		var onIntersect = function (entries, observer) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}
				entry.target.classList.add(
					entry.target.classList.contains('js-sketch') ? 'is-drawn' : 'is-visible'
				);
				observer.unobserve(entry.target);
			});
		};

		var observer = new IntersectionObserver(onIntersect, {
			rootMargin: '0px 0px -10% 0px',
			threshold: 0.1
		});

		revealables.forEach(function (el) {
			observer.observe(el);
		});
		sketches.forEach(function (el) {
			observer.observe(el);
		});
	}

	/* Sector filter: progressive enhancement over server-side taxonomy URLs.
	   Intercept filter clicks, fetch the target page, swap the grid, push state. */
	var filter = document.querySelector('.filter');
	var grid = document.getElementById('project-grid');
	if (filter && grid && 'fetch' in window) {
		var swapFromUrl = function (url, push) {
			grid.setAttribute('aria-busy', 'true');
			fetch(url, { headers: { 'X-Requested-With': 'fetch' } })
				.then(function (res) {
					if (!res.ok) {
						throw new Error(res.status);
					}
					return res.text();
				})
				.then(function (html) {
					var doc = new DOMParser().parseFromString(html, 'text/html');
					var newGrid = doc.getElementById('project-grid');
					var newFilter = doc.querySelector('.filter');
					if (!newGrid) {
						throw new Error('no grid');
					}
					grid.innerHTML = newGrid.innerHTML;
					if (newFilter) {
						filter.innerHTML = newFilter.innerHTML;
					}
					grid.removeAttribute('aria-busy');
					if (push) {
						window.history.pushState({ hklaFilter: true }, '', url);
					}
					var title = doc.querySelector('title');
					if (title) {
						document.title = title.textContent;
					}
				})
				.catch(function () {
					// Fall back to a normal navigation on any failure.
					window.location.assign(url);
				});
		};

		filter.addEventListener('click', function (e) {
			var link = e.target.closest('a');
			if (!link || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
				return;
			}
			e.preventDefault();
			swapFromUrl(link.href, true);
		});

		window.addEventListener('popstate', function () {
			if (document.getElementById('project-grid')) {
				swapFromUrl(window.location.href, false);
			}
		});
	}
})();
