(function () {
    'use strict';

    if (typeof L === 'undefined') {
        alert('Pustaka peta (Leaflet) gagal dimuat. Periksa koneksi/muat ulang halaman.');
        return;
    }

    const faskes = window.APP_DATA.faskes || [];

    const map = L.map('recommendation-map').setView([
        window.APP_DATA.default_lat,
        window.APP_DATA.default_lng,
    ], 14);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    let faskesLayer = L.layerGroup().addTo(map);
    let routeLayer = L.layerGroup().addTo(map);
    let startMarker = null;

    const el = (id) => document.getElementById(id);

    function initFaskesMarkers() {
        faskesLayer.clearLayers();
        faskes.forEach(function (f) {
            const icon = L.divIcon({
                html: '<div style="background:' + f.warna + ';border-radius:50%;width:14px;height:14px;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4);"></div>',
                className: '',
                iconSize: [14, 14],
                iconAnchor: [7, 7],
            });
            const marker = L.marker([f.latitude, f.longitude], { icon: icon });
            marker.bindPopup(
                '<strong>' + f.nama + '</strong><br>' +
                '<span class="badge" style="background:' + f.warna + '">' + f.kategori + '</span>' +
                '<br><small>' + f.alamat + '</small>' +
                '<br><a href="/faskes/' + f.id + '">Lihat detail</a>'
            );
            marker.addTo(faskesLayer);
        });
    }

    function formatDistance(m) {
        const value = parseFloat(m);
        if (isNaN(value)) return '-';
        if (value >= 1000) return (value / 1000).toFixed(2).replace('.', ',') + ' km';
        return Math.round(value) + ' m';
    }

    function setStart(lat, lng, label) {
        if (!startMarker) {
            startMarker = L.marker([lat, lng], {
                draggable: true,
                icon: L.divIcon({
                    html: '<div style="background:#0d6efd;border-radius:50% 50% 50% 0;width:22px;height:22px;transform:rotate(-45deg);"></div>',
                    className: '',
                    iconSize: [22, 22],
                    iconAnchor: [11, 11],
                }),
            }).addTo(map);
            startMarker.on('dragend', function () {
                const p = startMarker.getLatLng();
                syncStartInputs(p.lat, p.lng);
            });
        } else {
            startMarker.setLatLng([lat, lng]);
        }

        el('latitude').value = parseFloat(lat).toFixed(7);
        el('longitude').value = parseFloat(lng).toFixed(7);

        if (label) {
            startMarker.bindPopup('<strong>Lokasi Awal: ' + label + '</strong>').openPopup();
        }
    }

    function syncStartInputs(lat, lng) {
        el('latitude').value = parseFloat(lat).toFixed(7);
        el('longitude').value = parseFloat(lng).toFixed(7);
        routeLayer.clearLayers();
    }

    function setMethod(method) {
        document.querySelectorAll('#group-lokasi .btn').forEach(function (btn) {
            btn.classList.toggle('active', btn.dataset.method === method);
        });

        el('label-titik').classList.toggle('d-none', method !== 'titik');
        el('titik_id').classList.toggle('d-none', method !== 'titik');

        if (method === 'map') {
            map.on('click', onMapClick);
        } else {
            map.off('click', onMapClick);
        }

        if (method === 'geo') {
            let geoDone = false;
            const finish = function () {
                geoDone = true;
                clearTimeout(watchdog);
            };
            const pakaiIP = function () {
                finish();
                fetch('https://ipapi.co/json/')
                    .then(function (r) {
                        return r.json();
                    })
                    .then(function (data) {
                        const lat = parseFloat(data.latitude);
                        const lng = parseFloat(data.longitude);
                        if (isNaN(lat) || isNaN(lng) || !data.country_code) {
                            throw new Error('Data lokasi IP tidak valid');
                        }
                        setStart(lat, lng, 'Lokasi Saya (perkiraan via IP)');
                        map.setView([lat, lng], 15);
                    })
                    .catch(function () {
                        pesanLokasi('Lokasi tidak dapat diperoleh (GPS & IP terblokir).');
                    });
            };

            if (!navigator.geolocation || !window.isSecureContext) {
                pakaiIP();
                return;
            }

            // Watchdog: jika browser tidak memanggil callback sama sekali
            // (mis. prompt izin dibiarkan/diabaikan), fallback ke lokasi via IP.
            const watchdog = setTimeout(function () {
                if (geoDone) {
                    return;
                }
                pakaiIP();
            }, 15000);

            navigator.geolocation.getCurrentPosition(function (pos) {
                finish();
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                setStart(lat, lng, 'Gunakan Lokasi Saya');
                map.setView([lat, lng], 15);
            }, function () {
                pakaiIP();
            }, { enableHighAccuracy: false, timeout: 10000, maximumAge: 5000 });
        }
    }

    function onMapClick(e) {
        setStart(e.latlng.lat, e.latlng.lng, 'Klik peta');
        routeLayer.clearLayers();
    }

    function pesanLokasi(teks) {
        let pesan = teks + '\nSilakan pilih lokasi secara manual pada peta.';
        if (!window.isSecureContext) {
            pesan = pesan + '\n\nData Browser: ' + location.origin + '\nCatatan: Browser hanya mengizinkan lokasi pada HTTPS atau localhost (127.0.0.1/localhost).';
        }
        alert(pesan);
        setMethod('map');
    }

    el('group-lokasi').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn[data-method]');
        if (btn) setMethod(btn.dataset.method);
    });

    el('titik_id').addEventListener('change', function () {
        const opt = this.selectedOptions[0];
        if (!opt || !opt.value) return;
        setStart(opt.dataset.lat, opt.dataset.lng, opt.text);
    });

    el('latitude').addEventListener('input', syncFromInputs);
    el('longitude').addEventListener('input', syncFromInputs);

    function syncFromInputs() {
        const lat = parseFloat(el('latitude').value);
        const lng = parseFloat(el('longitude').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            setStart(lat, lng);
        }
    }

    function drawRoute(path, userLat, userLng, faskesLat, faskesLng) {
        routeLayer.clearLayers();

        const coords = [];

        if (userLat !== undefined && userLng !== undefined && !isNaN(userLat) && !isNaN(userLng)) {
            coords.push([userLat, userLng]);
        }

        if (path && path.length) {
            path.forEach(function (p) {
                coords.push([p.latitude, p.longitude]);
            });
        }

        if (faskesLat !== undefined && faskesLng !== undefined && !isNaN(faskesLat) && !isNaN(faskesLng)) {
            coords.push([faskesLat, faskesLng]);
        }

        if (coords.length < 2) {
            return;
        }

        L.polyline(coords, { color: '#198754', weight: 5, opacity: 0.9 }).addTo(routeLayer);

        const start = coords[0];
        const end = coords[coords.length - 1];

        L.circleMarker([start[0], start[1]], {
            radius: 7,
            color: '#0d6efd',
            fillColor: '#0d6efd',
            fillOpacity: 1,
        }).bindTooltip('Lokasi User', { direction: 'top' }).addTo(routeLayer);

        coords.forEach(function (c, i) {
            if (i === 0 || i === coords.length - 1) {
                return;
            }

            L.circleMarker([c[0], c[1]], {
                radius: 5,
                color: '#198754',
                fillColor: '#198754',
                fillOpacity: 1,
                weight: 1,
            }).bindTooltip('Node ' + i, { direction: 'top' }).addTo(routeLayer);
        });

        L.circleMarker([end[0], end[1]], {
            radius: 8,
            color: '#dc3545',
            fillColor: '#dc3545',
            fillOpacity: 1,
        }).bindTooltip('Faskes', { direction: 'top' }).addTo(routeLayer);

        map.fitBounds(L.latLngBounds(coords), { padding: [30, 30] });
    }

    function renderResults(data) {
        const hasil = el('hasil');
        if (!data.results || data.results.length === 0) {
            hasil.innerHTML = '<p class="text-muted small mb-0">Maaf, rute menuju faskes tidak ditemukan pada jaringan jalan yang tersedia.</p>';
            return;
        }

        const heading = document.createElement('div');
        heading.className = 'text-center fw-bold h6 text-uppercase mb-2';
        heading.textContent = 'Faskes Terdekat';
        hasil.appendChild(heading);

        const ul = document.createElement('div');
        ul.className = 'list-group list-group-flush';

        data.results.forEach(function (r, index) {
            const f = r.faskes;
            const item = document.createElement('div');
            item.className = 'list-group-item result-item' + (index === 0 ? ' first' : '');

            const details = [];
            details.push('<span class="fw-bold">Jarak:</span>&nbsp;' + formatDistance(r.total_distance_m));
            if (f.alamat) {
                details.push('<span class="fw-bold">Alamat:</span>&nbsp;' + f.alamat);
            }
            if (f.telepon) {
                details.push('<span class="fw-bold">Telepon:</span>&nbsp;' + f.telepon);
            }

            item.innerHTML =
                '<div class="d-flex gap-2 align-items-start">' +
                '<span class="result-marker" style="background:' + f.kategori.warna + '">' + (index + 1) + '</span>' +
                '<div class="flex-grow-1">' +
                '<div class="fw-semibold">' + (index + 1) + '. ' + f.nama + '</div>' +
                '<div class="small text-muted mb-1">' + details.join('<br>') + '</div>' +
                '<div class="d-flex gap-2">' +
                '<a href="/faskes/' + f.id + '" class="btn btn-sm btn-outline-primary">Lihat Detail</a>' +
                '<button type="button" class="btn btn-sm btn-success" data-rute="' + index + '">Lihat Rute</button>' +
                '</div>' +
                '</div>' +
                '</div>';

            item.addEventListener('click', function (e) {
                if (e.target.closest('a,button')) {
                    return;
                }
                showRoute(index, data.results, item);
            });

            const btnRute = item.querySelector('[data-rute]');
            btnRute.addEventListener('click', function (e) {
                e.stopPropagation();
                showRoute(index, data.results, item);
            });

            ul.appendChild(item);
        });

        hasil.appendChild(ul);

        const meta = document.createElement('div');
        meta.className = 'small text-muted mt-2 text-center';
        meta.textContent = 'Urut dari jarak terkecil. ' + data.count + ' faskes dihitung via Dijkstra.';
        hasil.appendChild(meta);

        showRoute(0, data.results, document.querySelector('#hasil .result-item'));
    }

    function showRoute(index, results, item) {
        if (!results[index]) {
            return;
        }
        const r = results[index];
        const f = r.faskes;
        const userLat = parseFloat(el('latitude').value);
        const userLng = parseFloat(el('longitude').value);

        drawRoute(
            r.path,
            isNaN(userLat) ? undefined : userLat,
            isNaN(userLng) ? undefined : userLng,
            f.latitude,
            f.longitude
        );

        document.querySelectorAll('#hasil .result-item').forEach(function (x) {
            x.classList.remove('first');
        });
        if (item) {
            item.classList.add('first');
        }
    }

    el('form-rekomendasi').addEventListener('submit', function (e) {
        e.preventDefault();

        const lat = parseFloat(el('latitude').value);
        const lng = parseFloat(el('longitude').value);

        if (isNaN(lat) || isNaN(lng)) {
            alert('Tentukan lokasi awal terlebih dahulu (klik peta, lokasi saya, atau pilih titik).');
            return;
        }

        const btn = el('btn-cari');
        btn.disabled = true;
        el('status-loading').classList.remove('d-none');

        const payload = {
            latitude: lat,
            longitude: lng,
            kategori_faskes_id: el('kategori_faskes_id').value || null,
        };

        fetch('/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(payload),
        })
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                if (data.message) {
                    alert(data.message);
                    el('hasil').innerHTML = '<p class="text-muted small mb-0">' + data.message + '</p>';
                    return;
                }
                renderResults(data);
            })
            .catch(function () {
                alert('Terjadi kesalahan pada server. Pastikan MySQL/Apache berjalan.');
            })
            .finally(function () {
                btn.disabled = false;
                el('status-loading').classList.add('d-none');
            });
    });

    initFaskesMarkers();
    setMethod('map');
})();