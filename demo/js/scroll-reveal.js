(function () {
  'use strict';

  var els = document.querySelectorAll('.wow');
  if (!els.length) return;

  function toMs(attr) {
    if (!attr) return 0;
    var n = parseFloat(attr);
    if (attr.indexOf('ms') > -1) return n;
    return n * 1000;
  }

  els.forEach(function (el) {
    var delay = toMs(el.getAttribute('data-wow-delay'));
    var duration = toMs(el.getAttribute('data-wow-duration'));
    el._revealDelay = delay ? delay + 'ms' : '0ms';
    if (duration) el.style.transitionDuration = duration + 'ms';
  });

  if (!('IntersectionObserver' in window)) {
    els.forEach(function (el) { el.classList.add('reveal-in'); });
    return;
  }

  // Toggles on every crossing — reveals with its staggered delay when
  // scrolling toward it, hides immediately (no delay) when scrolling away,
  // so the cycle repeats each time an element enters/leaves the viewport.
  var io = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        var el = entry.target;
        if (entry.isIntersecting) {
          el.style.transitionDelay = el._revealDelay;
          el.classList.add('reveal-in');
        } else {
          el.style.transitionDelay = '0ms';
          el.classList.remove('reveal-in');
        }
      });
    },
    { threshold: 0.15, rootMargin: '0px 0px -60px 0px' }
  );

  els.forEach(function (el) { io.observe(el); });
})();
