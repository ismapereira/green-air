/**
 * Green Air v2.0 — Map with MarkerCluster
 * XSS-safe rendering, custom markers, modal details
 */
(function() {
    var map = L.map('map', { zoomControl: false }).setView([-23.55, -46.63], 12);
    L.control.zoom({ position: 'topright' }).addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // MarkerCluster
    var markers;
    if (typeof L.markerClusterGroup === 'function') {
        markers = L.markerClusterGroup({
            maxClusterRadius: 50,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            iconCreateFunction: function(cluster) {
                var count = cluster.getChildCount();
                var size = count < 10 ? 'small' : count < 50 ? 'medium' : 'large';
                var sizes = { small: 36, medium: 44, large: 52 };
                return L.divIcon({
                    html: '<div style="background:rgba(5,150,105,0.85);color:#fff;border-radius:50%;width:' + sizes[size] + 'px;height:' + sizes[size] + 'px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;box-shadow:0 2px 8px rgba(0,0,0,0.2);border:2px solid rgba(255,255,255,0.3)">' + count + '</div>',
                    className: '',
                    iconSize: [sizes[size], sizes[size]]
                });
            }
        });
    } else {
        markers = L.layerGroup();
    }
    map.addLayer(markers);

    // Custom tree icon
    var treeIcon = L.divIcon({
        html: '<div style="background:#059669;color:#fff;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:1rem;box-shadow:0 2px 6px rgba(0,0,0,0.25);border:2px solid #fff"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8.416.223a.5.5 0 0 0-.832 0l-3 4.5A.5.5 0 0 0 5 5.5h.098L3.076 8.735A.5.5 0 0 0 3.5 9.5h.191l-1.638 3.276a.5.5 0 0 0 .447.724H7V16h2v-2.5h4.5a.5.5 0 0 0 .447-.724L12.31 9.5h.191a.5.5 0 0 0 .424-.765L10.902 5.5H11a.5.5 0 0 0 .416-.777z"/></svg></div>',
        className: '',
        iconSize: [32, 32],
        iconAnchor: [16, 32]
    });

    var userMarker = null;
    var modal = document.getElementById('tree-modal');
    var modalBody = document.getElementById('tree-modal-body');
    var modalEl = null;

    // Bootstrap modal
    if (typeof bootstrap !== 'undefined' && modal) {
        modalEl = new bootstrap.Modal(modal);
    }

    function showTreeModal(t) {
        if (!modalBody) return;
        modalBody.innerHTML = '';

        var card = document.createElement('div');
        card.className = 'text-center';

        if (t.photo) {
            var img = document.createElement('img');
            img.src = window.BASE_URL + 'uploads/trees/' + t.photo;
            img.alt = '';
            img.className = 'img-fluid rounded-3 mb-3';
            img.style.maxHeight = '250px';
            img.style.objectFit = 'cover';
            card.appendChild(img);
        }

        var h = document.createElement('h5');
        h.className = 'fw-bold';
        h.textContent = t.species_name || 'Espécie não informada';
        card.appendChild(h);

        var badge = document.createElement('span');
        badge.className = 'badge badge-status mb-2 ' + getStatusBadgeClass(t.status_name);
        badge.textContent = t.status_name || '';
        card.appendChild(badge);

        var info = document.createElement('div');
        info.className = 'text-start mt-3 small';
        if (t.address) addInfoRow(info, 'geo-alt', 'Endereço', t.address);
        if (t.size) addInfoRow(info, 'rulers', 'Tamanho', t.size);
        if (t.age_approx) addInfoRow(info, 'calendar', 'Idade', t.age_approx + ' anos');
        if (t.user_name) addInfoRow(info, 'person', 'Cadastrado por', t.user_name);
        if (t.observations) addInfoRow(info, 'chat-text', 'Obs', t.observations);
        card.appendChild(info);

        var link = document.createElement('a');
        link.href = window.BASE_URL + 'arvore/' + t.id;
        link.className = 'btn btn-success btn-sm mt-3';
        link.innerHTML = '<i class="bi bi-eye me-1"></i>Ver detalhes';
        card.appendChild(link);

        modalBody.appendChild(card);
        if (modalEl) modalEl.show();
    }

    function addInfoRow(parent, icon, label, value) {
        var row = document.createElement('div');
        row.className = 'mb-1';
        var iconEl = document.createElement('i');
        iconEl.className = 'bi bi-' + icon + ' me-2 text-success';
        row.appendChild(iconEl);
        var strong = document.createElement('strong');
        strong.textContent = label + ': ';
        row.appendChild(strong);
        row.appendChild(document.createTextNode(value));
        parent.appendChild(row);
    }

    function getStatusBadgeClass(name) {
        if (!name) return '';
        var n = name.toLowerCase();
        if (n.indexOf('saud') >= 0) return 'badge-saudavel';
        if (n.indexOf('poda') >= 0) return 'badge-poda';
        if (n.indexOf('risco') >= 0 || n.indexOf('queda') >= 0) return 'badge-risco';
        if (n.indexOf('doen') >= 0) return 'badge-doente';
        return 'bg-secondary';
    }

    function loadTrees() {
        var params = {};
        var species = document.getElementById('filter-species');
        var status = document.getElementById('filter-status');
        var size = document.getElementById('filter-size');
        var address = document.getElementById('filter-address');
        if (species && species.value) params.species_id = species.value;
        if (status && status.value) params.status_id = status.value;
        if (size && size.value) params.size = size.value;
        if (address && address.value) params.address = address.value;
        var qs = Object.keys(params).length ? '?' + new URLSearchParams(params).toString() : '';
        fetch(window.BASE_URL + 'api/mapa/arvores' + qs)
            .then(function(r) { return r.json(); })
            .then(function(trees) {
                markers.clearLayers();
                trees.forEach(function(t) {
                    if (!t.latitude || !t.longitude) return;
                    var m = L.marker([t.latitude, t.longitude], { icon: treeIcon });
                    m.on('click', function() { showTreeModal(t); });
                    markers.addLayer(m);
                });
            });
    }

    loadTrees();

    var filterBtn = document.getElementById('filter-apply');
    if (filterBtn) filterBtn.addEventListener('click', loadTrees);

    // Geolocation
    var locBtn = document.getElementById('btn-my-location');
    function goToMyLocation() {
        if (!navigator.geolocation) return;
        navigator.geolocation.getCurrentPosition(function(pos) {
            var lat = pos.coords.latitude;
            var lng = pos.coords.longitude;
            map.setView([lat, lng], 15);
            if (userMarker) map.removeLayer(userMarker);
            userMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    html: '<div style="background:#3B82F6;color:#fff;border-radius:50%;width:28px;height:28px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 10px rgba(59,130,246,0.5);border:3px solid #fff"><i class="bi bi-geo-fill" style="font-size:0.8rem"></i></div>',
                    className: '',
                    iconSize: [28, 28],
                    iconAnchor: [14, 14]
                })
            }).addTo(map).bindPopup('Você está aqui').openPopup();
        });
    }
    if (locBtn) locBtn.addEventListener('click', goToMyLocation);

    // Auto-center on load
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            map.setView([pos.coords.latitude, pos.coords.longitude], 14);
        }, function() {}, { timeout: 5000 });
    }
})();
