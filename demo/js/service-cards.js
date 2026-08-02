(function () {
  'use strict';

  var cards = document.querySelectorAll('.services-one__list');
  if (!cards.length) return;

  if (!('IntersectionObserver' in window)) {
    cards.forEach(function (card) { card.classList.add('is-visible'); });
    return;
  }

  cards.forEach(function (card, i) {
    card.dataset.revealDelay = (i % 8) * 80 + 'ms';
  });

  var io = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        var card = entry.target;
        if (entry.isIntersecting) {
          card.style.transitionDelay = card.dataset.revealDelay;
          card.classList.add('is-visible');
        } else {
          card.style.transitionDelay = '0ms';
          card.classList.remove('is-visible');
        }
      });
    },
    { threshold: 0.15, rootMargin: '0px 0px -60px 0px' }
  );

  cards.forEach(function (card) { io.observe(card); });
})();
