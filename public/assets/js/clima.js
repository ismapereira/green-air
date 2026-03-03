(function() {
    var widget = document.getElementById('clima-widget');
    if (!widget) return;
    var baseUrl = (function() {
        var s = document.querySelector('script[src*="clima.js"]');
        return s ? s.src.replace(/assets\/js\/clima\.js.*$/, '') : '';
    }()) || (typeof BASE_URL !== 'undefined' ? BASE_URL : '');

    function renderClima(d) {
        if (d.error) {
            widget.innerHTML = '<p>' + d.error + '</p>';
            widget.classList.remove('loading');
            return;
        }
        var html = '<p><strong>' + (d.city || 'Sua região') + '</strong></p>';
        html += '<p>Temperatura: ' + (d.temp != null ? d.temp + '°C' : '-') + '</p>';
        html += '<p>Umidade: ' + (d.humidity != null ? d.humidity + '%' : '-') + '</p>';
        html += '<p>Condição: ' + (d.description || '-') + '</p>';
        if (d.aqi != null) html += '<p>Qualidade do ar (AQI): ' + d.aqi + '</p>';
        if (d.forecast && d.forecast.length) {
            html += '<p><strong>Próximas 24h:</strong></p><ul>';
            d.forecast.slice(0, 6).forEach(function(f) {
                var dt = new Date(f.dt * 1000);
                html += '<li>' + dt.getHours() + 'h: ' + f.temp + '°C - ' + (f.description || '') + '</li>';
            });
            html += '</ul>';
        }
        widget.innerHTML = html;
        widget.classList.remove('loading');
    }

    function fetchClima(params) {
        var qs = params ? '?' + new URLSearchParams(params).toString() : '';
        fetch(baseUrl + 'api/clima' + qs)
            .then(function(r) { return r.json(); })
            .then(renderClima)
            .catch(function() {
                widget.innerHTML = '<p>Não foi possível carregar o clima.</p>';
                widget.classList.remove('loading');
            });
    }

    if (!navigator.geolocation) {
        fetchClima();
        return;
    }

    widget.innerHTML = '<p class="loading">Detectando sua localização...</p>';
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            fetchClima({ lat: pos.coords.latitude, lon: pos.coords.longitude });
        },
        function() {
            widget.innerHTML = '<p class="loading">Carregando clima (cidade padrão)...</p>';
            fetchClima();
        },
        { enableHighAccuracy: true, timeout: 8000, maximumAge: 300000 }
    );
})();
