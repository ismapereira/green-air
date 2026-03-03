(function() {
    var baseUrl = window.BASE_URL || '';
    var defaultCenter = [-23.5505, -46.6333];
    var defaultZoom = 13;

    var map = L.map('map').setView(defaultCenter, defaultZoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var markers = [];
    var userLocationMarker = null;
    var userLocationCircle = null;
    var modal = document.getElementById('tree-modal');
    var modalBody = document.getElementById('tree-modal-body');
    var modalClose = document.querySelector('.modal-close');

    function setUserLocation(lat, lng) {
        if (userLocationMarker) {
            map.removeLayer(userLocationMarker);
            userLocationMarker = null;
        }
        if (userLocationCircle) {
            map.removeLayer(userLocationCircle);
            userLocationCircle = null;
        }
        map.setView([lat, lng], 14);
        userLocationCircle = L.circle([lat, lng], {
            color: '#16a34a',
            fillColor: '#22c55e',
            fillOpacity: 0.2,
            radius: 150
        }).addTo(map);
        userLocationMarker = L.marker([lat, lng], {
            icon: L.divIcon({
                className: 'user-location-marker',
                html: '<span style="background:#16a34a;color:#fff;padding:4px 8px;border-radius:6px;font-size:11px;">Você está aqui</span>',
                iconSize: [120, 24],
                iconAnchor: [60, 12]
            })
        }).addTo(map);
    }

    function tryGeolocation() {
        if (!navigator.geolocation) {
            loadTrees();
            return;
        }
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                var lat = pos.coords.latitude;
                var lng = pos.coords.longitude;
                setUserLocation(lat, lng);
                loadTrees();
            },
            function() {
                loadTrees();
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
        );
    }

    function loadTrees() {
        var species = document.getElementById('filter-species');
        var status = document.getElementById('filter-status');
        var size = document.getElementById('filter-size');
        var address = document.getElementById('filter-address');
        var params = new URLSearchParams();
        if (species && species.value) params.set('species_id', species.value);
        if (status && status.value) params.set('status_id', status.value);
        if (size && size.value) params.set('size', size.value);
        if (address && address.value.trim()) params.set('address', address.value.trim());
        var url = baseUrl + 'api/mapa/arvores' + (params.toString() ? '?' + params.toString() : '');
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(trees) {
                markers.forEach(function(m) { map.removeLayer(m); });
                markers = [];
                trees.forEach(function(t) {
                    var lat = parseFloat(t.latitude);
                    var lng = parseFloat(t.longitude);
                    if (isNaN(lat) || isNaN(lng)) return;
                    var marker = L.marker([lat, lng]).addTo(map);
                    marker._tree = t;
                    marker.on('click', function() {
                        var tree = this._tree;
                        var html = '<h3>' + (tree.species_name || '') + '</h3>';
                        html += '<p><strong>Status:</strong> ' + (tree.status_name || '') + '</p>';
                        if (tree.size) html += '<p><strong>Tamanho:</strong> ' + tree.size + '</p>';
                        if (tree.address) html += '<p><strong>Endereço:</strong> ' + tree.address + '</p>';
                        if (tree.photo_url) html += '<img src="' + tree.photo_url + '" alt="" style="max-width:100%;border-radius:8px;">';
                        if (modalBody) modalBody.innerHTML = html;
                        if (modal) modal.removeAttribute('hidden');
                    });
                    markers.push(marker);
                });
            })
            .catch(function() { console.error('Erro ao carregar árvores'); });
    }

    if (document.getElementById('filter-apply')) {
        document.getElementById('filter-apply').addEventListener('click', loadTrees);
    }
    if (document.getElementById('btn-my-location')) {
        document.getElementById('btn-my-location').addEventListener('click', tryGeolocation);
    }
    if (modalClose) {
        modalClose.addEventListener('click', function() { if (modal) modal.setAttribute('hidden', ''); });
    }
    if (modal) {
        modal.addEventListener('click', function(e) { if (e.target === modal) modal.setAttribute('hidden', ''); });
    }

    tryGeolocation();
})();
