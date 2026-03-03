(function() {
    var form = document.getElementById('tree-form');
    var latInput = document.getElementById('input-latitude');
    var lngInput = document.getElementById('input-longitude');
    var addressInput = document.getElementById('input-address');
    var statusEl = document.getElementById('geo-status');
    var submitBtn = document.getElementById('submit-tree');

    function setStatus(msg, ok) {
        if (statusEl) statusEl.textContent = msg;
        if (statusEl) statusEl.style.color = ok ? '#15803d' : '#6b7280';
    }

    function reverseGeocode(lat, lng, callback) {
        var url = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18&addressdetails=1';
        fetch(url, { headers: { 'Accept-Language': 'pt-BR' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var addr = data.address || {};
                var parts = [addr.road, addr.suburb, addr.neighbourhood, addr.city, addr.state].filter(Boolean);
                callback(parts.length ? parts.join(', ') : data.display_name || '');
            })
            .catch(function() { callback(''); });
    }

    if (!navigator.geolocation) {
        setStatus('Seu navegador não suporta geolocalização. Preencha o endereço manualmente.', false);
        if (submitBtn) submitBtn.disabled = false;
        return;
    }

    setStatus('Obtendo localização...', false);
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            var lat = pos.coords.latitude;
            var lng = pos.coords.longitude;
            if (latInput) latInput.value = lat;
            if (lngInput) lngInput.value = lng;
            setStatus('Localização obtida: ' + lat.toFixed(5) + ', ' + lng.toFixed(5), true);
            if (addressInput && !addressInput.value) {
                reverseGeocode(lat, lng, function(addr) {
                    if (addr) addressInput.value = addr;
                });
            }
            if (submitBtn) submitBtn.disabled = false;
        },
        function(err) {
            setStatus('Não foi possível obter a localização. Permita o acesso ao GPS ou preencha o endereço manualmente.', false);
            if (submitBtn) submitBtn.disabled = false;
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
    );
})();
