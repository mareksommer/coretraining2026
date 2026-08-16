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

    /* Stats count-up */
    var statsRoot = document.querySelector('[data-stats]');
    if (statsRoot) {
        var counters = statsRoot.querySelectorAll('[data-count]');
        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function formatCount(value) {
            return String(Math.round(value));
        }

        function setFinal(el) {
            var target = parseFloat(el.getAttribute('data-count')) || 0;
            var suffix = el.getAttribute('data-suffix') || '';
            el.textContent = formatCount(target) + suffix;
        }

        function animateCount(el) {
            var target = parseFloat(el.getAttribute('data-count')) || 0;
            var suffix = el.getAttribute('data-suffix') || '';
            var duration = target >= 500 ? 1600 : 1200;
            var start = null;

            function frame(now) {
                if (start === null) {
                    start = now;
                }
                var t = Math.min((now - start) / duration, 1);
                // easeOutCubic
                var eased = 1 - Math.pow(1 - t, 3);
                el.textContent = formatCount(target * eased) + suffix;
                if (t < 1) {
                    requestAnimationFrame(frame);
                } else {
                    setFinal(el);
                }
            }

            requestAnimationFrame(frame);
        }

        function run() {
            if (statsRoot.classList.contains('is-counted')) {
                return;
            }
            statsRoot.classList.add('is-counted');
            counters.forEach(function (el) {
                if (reducedMotion) {
                    setFinal(el);
                } else {
                    animateCount(el);
                }
            });
        }

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            run();
                            observer.disconnect();
                        }
                    });
                },
                { threshold: 0.35 }
            );
            observer.observe(statsRoot);
        } else {
            run();
        }
    }
})();
