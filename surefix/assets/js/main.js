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

  /* Hero quick-enquiry mini-form (Name + Mobile) — posts to
     api/submit-lead.php and stores a real lead in the DB. */
  var qeForm = document.getElementById('quickEnquiryForm');
  if (qeForm) {
    var qeName = document.getElementById('qeName');
    var qeMobile = document.getElementById('qeMobile');
    var qeHint = document.getElementById('qeHint');
    var qeSuccess = document.getElementById('qeSuccess');
    var qeSubmitBtn = qeForm.querySelector('.hero__quick-form-submit');

    qeMobile.addEventListener('input', function () {
      qeMobile.value = qeMobile.value.replace(/\D/g, '').slice(0, 10);
    });

    qeForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var validMobile = /^[6-9]\d{9}$/.test(qeMobile.value.trim());
      if (!qeName.value.trim() || !validMobile) {
        qeHint.classList.add('is-visible');
        return;
      }
      qeHint.classList.remove('is-visible');
      qeSubmitBtn.disabled = true;

      var base = window.SUREFIX_SITE_URL || '';
      fetch(base + '/api/submit-lead.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          source: 'quick_enquiry',
          name: qeName.value.trim(),
          mobile: qeMobile.value.trim(),
          website: qeForm.querySelector('[name="website"]') ? qeForm.querySelector('[name="website"]').value : ''
        })
      }).then(function (res) { return res.json(); }).then(function (data) {
        if (data.success) {
          qeForm.hidden = true;
          qeSuccess.hidden = false;
        } else {
          qeHint.textContent = data.error || 'Something went wrong — please call us instead.';
          qeHint.classList.add('is-visible');
        }
      }).catch(function () {
        qeHint.textContent = 'Could not reach the server — please call us instead.';
        qeHint.classList.add('is-visible');
      }).finally(function () {
        qeSubmitBtn.disabled = false;
      });
    });
  }

  /* Contact page form — posts to api/submit-lead.php as a 'contact' lead. */
  var cfForm = document.getElementById('contactForm');
  if (cfForm) {
    var cfName = document.getElementById('cf-name');
    var cfMobile = document.getElementById('cf-mobile');
    var cfEmail = document.getElementById('cf-email');
    var cfMessage = document.getElementById('cf-message');
    var cfHint = document.getElementById('cfHint');
    var cfSuccess = document.getElementById('cfSuccess');
    var cfSubmitBtn = cfForm.querySelector('button[type="submit"]');

    cfMobile.addEventListener('input', function () {
      cfMobile.value = cfMobile.value.replace(/\D/g, '').slice(0, 10);
    });

    function showContactError(msg) {
      cfHint.textContent = msg;
      cfHint.hidden = false;
    }

    cfForm.addEventListener('submit', function (e) {
      e.preventDefault();
      cfHint.hidden = true;

      if (!cfName.value.trim()) { showContactError('Please enter your name.'); return; }
      if (!/^[6-9]\d{9}$/.test(cfMobile.value.trim())) { showContactError('Please enter a valid 10-digit mobile number.'); return; }
      if (cfEmail.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(cfEmail.value.trim())) { showContactError('Please enter a valid email address.'); return; }

      cfSubmitBtn.disabled = true;
      var base = window.SUREFIX_SITE_URL || '';
      fetch(base + '/api/submit-lead.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          source: 'contact',
          name: cfName.value.trim(),
          mobile: cfMobile.value.trim(),
          email: cfEmail.value.trim(),
          message: cfMessage.value.trim(),
          website: cfForm.querySelector('[name="website"]') ? cfForm.querySelector('[name="website"]').value : ''
        })
      }).then(function (res) { return res.json(); }).then(function (data) {
        if (data.success) {
          cfForm.hidden = true;
          cfSuccess.hidden = false;
        } else {
          showContactError(data.error || 'Something went wrong — please call us instead.');
        }
      }).catch(function () {
        showContactError('Could not reach the server — please call us instead.');
      }).finally(function () {
        cfSubmitBtn.disabled = false;
      });
    });
  }

  /* Reviews carousel — dots + autoscroll (mobile only; desktop shows the
     static 3-col grid, where scrollWidth ~= clientWidth so this is a no-op) */
  var testiGrid = document.getElementById('testiGrid');
  var testiDots = document.getElementById('testiDots');
  if (testiGrid && testiDots) {
    var testiCards = testiGrid.querySelectorAll('.testi-card');
    var dots = testiDots.querySelectorAll('.testi-dot');
    var testiIndex = 0;
    var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function isTestiCarouselActive() {
      return testiGrid.scrollWidth > testiGrid.clientWidth + 10;
    }
    // Scrolls only the carousel's own horizontal scroll position — never
    // use scrollIntoView() here, since it also scrolls ancestor containers
    // (including the page itself) to bring an off-screen card into view,
    // which is what caused the homepage to auto-scroll down to Reviews on
    // its own once the autoscroll timer fired.
    function goToTestiCard(i) {
      var card = testiCards[i];
      if (!card) return;
      var target = card.offsetLeft - (testiGrid.clientWidth - card.offsetWidth) / 2;
      testiGrid.scrollTo({ left: Math.max(target, 0), behavior: 'smooth' });
    }

    dots.forEach(function (dot) {
      dot.addEventListener('click', function () {
        pauseTestiAutoScroll();
        goToTestiCard(parseInt(dot.dataset.index, 10));
      });
    });

    var scrollTimer;
    testiGrid.addEventListener('scroll', function () {
      clearTimeout(scrollTimer);
      scrollTimer = setTimeout(function () {
        var center = testiGrid.scrollLeft + testiGrid.clientWidth / 2;
        var closest = 0, closestDist = Infinity;
        testiCards.forEach(function (card, i) {
          var dist = Math.abs((card.offsetLeft + card.offsetWidth / 2) - center);
          if (dist < closestDist) { closestDist = dist; closest = i; }
        });
        testiIndex = closest;
        dots.forEach(function (dot, i) { dot.classList.toggle('is-active', i === closest); });
      }, 100);
    }, { passive: true });

    var testiAutoTimer = null, testiResumeTimer = null;
    function startTestiAutoScroll() {
      clearInterval(testiAutoTimer);
      if (reducedMotion) return;
      testiAutoTimer = setInterval(function () {
        if (!isTestiCarouselActive()) return;
        testiIndex = (testiIndex + 1) % testiCards.length;
        goToTestiCard(testiIndex);
      }, 4000);
    }
    function pauseTestiAutoScroll() {
      clearInterval(testiAutoTimer);
      clearTimeout(testiResumeTimer);
      testiResumeTimer = setTimeout(startTestiAutoScroll, 6000);
    }
    testiGrid.addEventListener('pointerdown', pauseTestiAutoScroll, { passive: true });
    testiGrid.addEventListener('touchstart', pauseTestiAutoScroll, { passive: true });

    // Only autoscroll while the carousel is actually on screen — besides
    // being wasted motion otherwise, this is a second line of defence
    // against the section auto-scrolling itself into view on page load.
    if ('IntersectionObserver' in window) {
      var testiVisibilityIO = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) startTestiAutoScroll();
          else { clearInterval(testiAutoTimer); clearTimeout(testiResumeTimer); }
        });
      }, { threshold: 0.4 });
      testiVisibilityIO.observe(testiGrid);
    } else {
      startTestiAutoScroll();
    }
  }

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

  /* Scroll reveal — reveal-once. Toggling in-view on and off as elements
     crossed the intersection threshold caused visible flicker for anything
     sitting near the fold (e.g. .page-hero__cta), since a tiny scroll
     wiggle at the boundary flips isIntersecting back and forth rapidly.
     Elements stay revealed permanently once shown, and are unobserved so
     they can't flicker again. */
  var revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          io.unobserve(entry.target);
        }
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
