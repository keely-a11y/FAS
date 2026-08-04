/**
 * HKLA front end. Vanilla JS, no dependencies, well under the 50KB budget.
 * Everything here is progressive enhancement: the site is fully usable
 * with JavaScript disabled.
 */
(function () {
	'use strict';

	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

	/* Mobile nav toggle */
	var toggle = document.querySelector('.nav-toggle');
	var nav = document.getElementById('site-nav');
	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var open = nav.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && nav.classList.contains('is-open')) {
				nav.classList.remove('is-open');
				toggle.setAttribute('aria-expanded', 'false');
				toggle.focus();
			}
		});
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
