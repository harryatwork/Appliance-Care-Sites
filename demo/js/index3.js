(function () {
  'use strict';

  /* Preloader */
  window.addEventListener('load', function () {
    var pre = document.getElementById('preloader');
    if (pre) setTimeout(function () { pre.classList.add('hidden'); }, 300);
  });

  /* Sticky navbar background on scroll */
  var navbar = document.getElementById('navbar');
  window.addEventListener('scroll', function () {
    var y = window.scrollY;
    if (navbar) navbar.classList.toggle('scrolled', y > 30);
    var backTop = document.getElementById('backTop');
    if (backTop) backTop.classList.toggle('show', y > 700);
  }, { passive: true });

  /* Mobile drawer */
  var navToggle = document.getElementById('navToggle');
  var drawer = document.getElementById('drawer');
  var overlay = document.getElementById('drawerOverlay');
  var drawerClose = document.getElementById('drawerClose');

  function openDrawer() {
    drawer.classList.add('open');
    overlay.classList.add('open');
    navToggle.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  function closeDrawer() {
    drawer.classList.remove('open');
    overlay.classList.remove('open');
    navToggle.classList.remove('active');
    document.body.style.overflow = '';
  }
  if (navToggle) navToggle.addEventListener('click', function () {
    drawer.classList.contains('open') ? closeDrawer() : openDrawer();
  });
  if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
  if (overlay) overlay.addEventListener('click', closeDrawer);
  document.querySelectorAll('.drawer-link').forEach(function (link) {
    link.addEventListener('click', closeDrawer);
  });

  /* FAQ accordion */
  document.querySelectorAll('.faq-item').forEach(function (item) {
    var q = item.querySelector('.faq-q');
    var a = item.querySelector('.faq-a');
    if (item.classList.contains('open')) a.style.maxHeight = a.scrollHeight + 'px';
    q.addEventListener('click', function () {
      var isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item.open').forEach(function (other) {
        other.classList.remove('open');
        other.querySelector('.faq-a').style.maxHeight = null;
      });
      if (!isOpen) {
        item.classList.add('open');
        a.style.maxHeight = a.scrollHeight + 'px';
      }
    });
  });

  /* Scroll reveal — reversible (matches the cyclical behavior used on index.html) */
  var revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        entry.target.classList.toggle('in-view', entry.isIntersecting);
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });
    revealEls.forEach(function (el) { io.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('in-view'); });
  }

  /* Counter animation */
  var stats = document.querySelectorAll('.stat__num');
  function animateCount(el) {
    var target = parseInt(el.dataset.count, 10);
    var suffix = el.querySelector('span');
    var suffixHTML = suffix ? suffix.outerHTML : '';
    var duration = 1300;
    var startTime = null;
    function step(ts) {
      if (!startTime) startTime = ts;
      var progress = Math.min((ts - startTime) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);
      el.innerHTML = Math.floor(eased * target) + suffixHTML;
      if (progress < 1) requestAnimationFrame(step);
      else el.innerHTML = target + suffixHTML;
    }
    requestAnimationFrame(step);
  }
  if ('IntersectionObserver' in window && stats.length) {
    var statIO = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCount(entry.target);
          statIO.unobserve(entry.target);
        }
      });
    }, { threshold: 0.4 });
    stats.forEach(function (s) { statIO.observe(s); });
  }

  /* Back to top */
  var backTop = document.getElementById('backTop');
  if (backTop) backTop.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  /* Smooth anchor scrolling with sticky-header offset */
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var id = a.getAttribute('href');
      if (id.length > 1) {
        var target = document.querySelector(id);
        if (target) {
          e.preventDefault();
          var top = target.getBoundingClientRect().top + window.pageYOffset - 90;
          window.scrollTo({ top: top, behavior: 'smooth' });
        }
      }
    });
  });
})();
