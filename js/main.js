/* =============================================================
   FERRIER ARCHITECTURE STUDIO — Main JS
   ============================================================= */

'use strict';

/* ============================================================
   HEADER: Scroll state + hero-active state
============================================================ */
(function initHeader() {
  const header = document.getElementById('site-header');
  const hero   = document.getElementById('hero');

  function updateHeader() {
    const scrollY = window.scrollY;
    const heroBottom = hero ? hero.getBoundingClientRect().bottom + scrollY : 0;

    // Scrolled: add background
    header.classList.toggle('scrolled', scrollY > 20);

    // Hero-active: transparent white text over dark hero image
    header.classList.toggle('hero-active', scrollY < heroBottom - 80);
  }

  window.addEventListener('scroll', updateHeader, { passive: true });
  updateHeader(); // run on load
})();


/* ============================================================
   MOBILE MENU
============================================================ */
(function initMobileMenu() {
  const toggle = document.querySelector('.menu-toggle');
  const menu   = document.getElementById('mobile-menu');
  const links  = menu.querySelectorAll('.mobile-nav-link');

  function openMenu() {
    menu.classList.add('open');
    menu.setAttribute('aria-hidden', 'false');
    toggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    toggle.parentElement.parentElement.classList.add('menu-open');
  }

  function closeMenu() {
    menu.classList.remove('open');
    menu.setAttribute('aria-hidden', 'true');
    toggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    toggle.parentElement.parentElement.classList.remove('menu-open');
  }

  toggle.addEventListener('click', () => {
    const isOpen = menu.classList.contains('open');
    isOpen ? closeMenu() : openMenu();
  });

  links.forEach(link => link.addEventListener('click', closeMenu));

  // Close on Escape
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && menu.classList.contains('open')) closeMenu();
  });
})();


/* ============================================================
   HERO IMAGE — Ken Burns entrance
============================================================ */
(function initHeroImage() {
  const img = document.querySelector('.hero-img');
  if (!img) return;

  if (img.complete) {
    img.classList.add('loaded');
  } else {
    img.addEventListener('load', () => img.classList.add('loaded'));
  }
})();


/* ============================================================
   PROJECT FILTER
============================================================ */
(function initProjectFilter() {
  const btns  = document.querySelectorAll('.filter-btn');
  const cards = document.querySelectorAll('.project-card');

  btns.forEach(btn => {
    btn.addEventListener('click', () => {
      const filter = btn.dataset.filter;

      // Update button states
      btns.forEach(b => {
        b.classList.remove('active');
        b.setAttribute('aria-selected', 'false');
      });
      btn.classList.add('active');
      btn.setAttribute('aria-selected', 'true');

      // Filter cards
      cards.forEach(card => {
        const match = filter === 'all' || card.dataset.category === filter;
        card.classList.toggle('is-hidden', !match);
      });
    });
  });
})();


/* ============================================================
   SCROLL-IN REVEAL ANIMATIONS
============================================================ */
(function initReveal() {
  // Annotate elements to animate
  const targets = [
    '.intro-text',
    '.project-card',
    '.studio-text',
    '.studio-image',
    '.recognition-item',
    '.service-item',
    '.process-step',
    '.contact-info',
    '.contact-form',
    '.featured-meta',
  ];

  const elements = document.querySelectorAll(targets.join(', '));

  elements.forEach((el, i) => {
    el.classList.add('reveal');
    // Stagger siblings within the same parent
    const siblings = el.parentElement.querySelectorAll('.reveal');
    const idx = Array.from(siblings).indexOf(el);
    if (idx === 1) el.classList.add('reveal-delay-1');
    if (idx === 2) el.classList.add('reveal-delay-2');
    if (idx === 3) el.classList.add('reveal-delay-3');
  });

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

  elements.forEach(el => observer.observe(el));
})();


/* ============================================================
   SMOOTH ANCHOR SCROLL (fallback for older Safari)
============================================================ */
(function initSmoothScroll() {
  if (CSS.supports('scroll-behavior', 'smooth')) return; // native support

  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', e => {
      const target = document.querySelector(anchor.getAttribute('href'));
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
})();


/* ============================================================
   ACTIVE NAV LINK (highlight as section enters view)
============================================================ */
(function initActiveNav() {
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-link');

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      navLinks.forEach(link => {
        link.classList.toggle(
          'active',
          link.getAttribute('href') === '#' + entry.target.id
        );
      });
    });
  }, { threshold: 0.35 });

  sections.forEach(section => observer.observe(section));
})();


/* ============================================================
   CONTACT FORM — client-side validation + fake submit
============================================================ */
(function initContactForm() {
  const form = document.getElementById('contact-form');
  const note = document.getElementById('form-note');
  if (!form) return;

  form.addEventListener('submit', e => {
    e.preventDefault();

    const name    = form.name.value.trim();
    const email   = form.email.value.trim();
    const message = form.message.value.trim();

    if (!name || !email || !message) {
      note.textContent = 'Please fill in all required fields.';
      note.style.color = '#c0392b';
      return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      note.textContent = 'Please enter a valid email address.';
      note.style.color = '#c0392b';
      return;
    }

    // Simulate submit
    const btn = form.querySelector('button[type="submit"]');
    btn.textContent = 'Sending…';
    btn.disabled = true;

    setTimeout(() => {
      form.reset();
      btn.textContent = 'Send Message';
      btn.disabled = false;
      note.textContent = 'Thank you — we\'ll be in touch shortly.';
      note.style.color = '#8B7355';
    }, 1200);
  });
})();


/* ============================================================
   FOOTER YEAR
============================================================ */
(function setFooterYear() {
  const el = document.getElementById('footer-year');
  if (el) el.textContent = new Date().getFullYear();
})();
