(function () {
    'use strict';

    const data = window.PETA_DATA;

    const map = L.map('peta-map').setView([-7.6300, 111.5160], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    const jalanLayer = L.layerGroup().addTo(map);
    const titikLayer = L.layerGroup().addTo(map);
    const faskesLayer = L.layerGroup().addTo(map);

    data.jalur.forEach(function (j) {
        L.polyline([
            [j.asal_lat, j.asal_lng],
            [j.tujuan_lat, j.tujuan_lng],
        ], {
            color: '#adb5bd',
            weight: 2,
            dashArray: '2 4',
            opacity: 0.7,
        }).bindTooltip((j.nama_jalan || 'Jalan') + ' \u00b7 ' + j.jarak + ' m').addTo(jalanLayer);
    });

    data.titik.forEach(function (t) {
        const color = t.tipe === 'faskes' ? '#dc3545' : '#0d6efd';
        L.circleMarker([t.latitude, t.longitude], {
            radius: t.tipe === 'faskes' ? 7 : 4,
            color: color,
            fillColor: color,
            fillOpacity: t.tipe === 'faskes' ? 1 : 0.6,
            weight: 1.5,
        })
            .bindTooltip('<strong>' + t.nama + '</strong><br>' + (t.kode || ''), { direction: 'top' })
            .addTo(titikLayer);
    });

    data.faskes.forEach(function (f) {
        const color = f.warna;
        L.circleMarker([f.latitude, f.longitude], {
            radius: 10,
            color: '#fff',
            fillColor: color,
            fillOpacity: 1,
            weight: 2,
        }).bindPopup(
            '<strong>' + f.nama + '</strong><br>' +
            '<span class="badge" style="background:' + color + '">' + f.kategori + '</span>' +
            '<br><small>' + f.alamat + '</small>' +
            (f.telepon ? '<br><small><i class="bi bi-telephone"></i> ' + f.telepon + '</small>' : '') +
            '<br><a href="/faskes/' + f.id + '">Lihat detail</a>'
        ).addTo(faskesLayer);
    });

    map.addControl(L.control.layers(null, {
        'Jalur Jalan': jalanLayer,
        'Titik / Node': titikLayer,
        'Faskes': faskesLayer,
    }, { collapsed: false }));

    const bounds = L.latLngBounds(data.faskes.map(function (f) {
        return [f.latitude, f.longitude];
    }));

    if (bounds.isValid()) {
        map.fitBounds(bounds.pad(0.15));
    }
})();