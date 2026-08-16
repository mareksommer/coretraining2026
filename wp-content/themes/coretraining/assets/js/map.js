/**
 * Leaflet map for CORE Centrum
 */
(function () {
    'use strict';

    if (typeof L === 'undefined' || typeof coretrainingMap === 'undefined') {
        return;
    }

    var el = document.getElementById('coretraining-map');
    if (!el) {
        return;
    }

    var map = L.map(el, { scrollWheelZoom: false }).setView(
        [coretrainingMap.lat, coretrainingMap.lng],
        15
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    L.marker([coretrainingMap.lat, coretrainingMap.lng])
        .addTo(map)
        .bindPopup('<strong>' + coretrainingMap.title + '</strong><br>' + coretrainingMap.address);
})();
