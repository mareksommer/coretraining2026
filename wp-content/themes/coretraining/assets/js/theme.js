/**
 * CoreTraining theme — UI scripts
 */
(function () {
    'use strict';

    /* Mobile navigation */
    var toggle = document.querySelector('.site-header__toggle');
    var nav = document.querySelector('.site-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var isOpen = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    /* Reference carousel */
    document.querySelectorAll('[data-carousel]').forEach(function (carousel) {
        var track = carousel.querySelector('[data-carousel-track]');
        var prev = carousel.querySelector('[data-carousel-prev]');
        var next = carousel.querySelector('[data-carousel-next]');

        if (!track || !prev || !next) {
            return;
        }

        function getScrollAmount() {
            var slide = track.querySelector('.carousel__slide');
            return slide ? slide.offsetWidth : track.clientWidth;
        }

        prev.addEventListener('click', function () {
            track.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
        });

        next.addEventListener('click', function () {
            track.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
        });
    });
})();
