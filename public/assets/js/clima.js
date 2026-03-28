/**
 * Green Air v2.0 — Climate Widget (Expanded)
 * Renders temperature, humidity, AQI, wind, UV, pollutants, forecast
 */
function renderClimaWidget(containerId, data) {
    var c = document.getElementById(containerId);
    if (!c) return;

    if (data.error) {
        c.innerHTML = '<div class="alert alert-warning mb-0"><i class="bi bi-cloud-slash me-2"></i>' + escapeHtml(data.error) + '</div>';
        return;
    }

    var windDirNames = ['N','NNE','NE','ENE','E','ESE','SE','SSE','S','SSO','SO','OSO','O','ONO','NO','NNO'];
    var windDir = data.wind_deg != null ? windDirNames[Math.round(data.wind_deg / 22.5) % 16] : '';

    var sunrise = data.sunrise ? new Date(data.sunrise * 1000).toLocaleTimeString('pt-BR', {hour:'2-digit',minute:'2-digit'}) : '--';
    var sunset = data.sunset ? new Date(data.sunset * 1000).toLocaleTimeString('pt-BR', {hour:'2-digit',minute:'2-digit'}) : '--';

    var html = '<div class="row g-2 mb-3">';

    // Temp card
    html += '<div class="col-6 col-md-3"><div class="clima-card h-100">';
    html += '<div class="clima-icon">' + (data.icon ? '<img src="https://openweathermap.org/img/wn/' + data.icon + '.png" alt="" width="40">' : '<i class="bi bi-thermometer-half"></i>') + '</div>';
    html += '<span class="clima-value">' + (data.temp != null ? data.temp + '°C' : '--') + '</span>';
    html += '<span class="clima-label">' + escapeHtml(data.description || 'Temperatura') + '</span>';
    html += '<small class="text-muted d-block mt-1">Sensação: ' + (data.feels_like != null ? data.feels_like + '°C' : '--') + '</small>';
    html += '</div></div>';

    // Humidity
    html += '<div class="col-6 col-md-3"><div class="clima-card h-100">';
    html += '<div class="clima-icon"><i class="bi bi-droplet-fill" style="color:#3B82F6"></i></div>';
    html += '<span class="clima-value">' + (data.humidity != null ? data.humidity + '%' : '--') + '</span>';
    html += '<span class="clima-label">Umidade</span>';
    html += '<small class="text-muted d-block mt-1">Pressão: ' + (data.pressure || '--') + ' hPa</small>';
    html += '</div></div>';

    // Wind
    html += '<div class="col-6 col-md-3"><div class="clima-card h-100">';
    html += '<div class="clima-icon"><i class="bi bi-wind" style="color:#6366F1"></i></div>';
    html += '<span class="clima-value">' + (data.wind_speed != null ? data.wind_speed + ' km/h' : '--') + '</span>';
    html += '<span class="clima-label">Vento ' + windDir + '</span>';
    html += '<small class="text-muted d-block mt-1">Visib: ' + (data.visibility != null ? data.visibility + ' km' : '--') + '</small>';
    html += '</div></div>';

    // AQI
    html += '<div class="col-6 col-md-3"><div class="clima-card h-100">';
    html += '<div class="clima-icon"><i class="bi bi-lungs-fill" style="color:' + (data.aqi_color || '#6b7280') + '"></i></div>';
    html += '<span class="clima-value" style="color:' + (data.aqi_color || '#6b7280') + '">' + (data.aqi_label || 'N/A') + '</span>';
    html += '<span class="clima-label">Qualidade do Ar</span>';
    var aqiPct = data.aqi ? Math.max(10, Math.min(100, (data.aqi / 5) * 100)) : 0;
    html += '<div class="aqi-bar"><div class="aqi-bar-fill" style="width:' + aqiPct + '%;background:' + (data.aqi_color || '#6b7280') + '"></div></div>';
    html += '</div></div>';

    html += '</div>';

    // Extra metrics row
    html += '<div class="row g-2 mb-3">';
    html += '<div class="col-6"><div class="clima-card py-2 h-100"><div class="d-flex align-items-center justify-content-center gap-2"><i class="bi bi-sunrise" style="color:#F59E0B;font-size:1.2rem"></i><div><span class="clima-value" style="font-size:1rem">' + sunrise + '</span><span class="clima-label">Nascer do sol</span></div></div></div></div>';
    html += '<div class="col-6"><div class="clima-card py-2 h-100"><div class="d-flex align-items-center justify-content-center gap-2"><i class="bi bi-sunset" style="color:#F97316;font-size:1.2rem"></i><div><span class="clima-value" style="font-size:1rem">' + sunset + '</span><span class="clima-label">Pôr do sol</span></div></div></div></div>';
    html += '</div>';

    // Pollutants
    if (data.pollutants) {
        html += '<h6 class="section-title"><i class="bi bi-lungs"></i> Poluentes</h6>';
        html += '<div class="pollutant-grid mb-3">';
        var pols = [
            {k:'pm2_5',n:'PM2.5',u:'µg/m³'},
            {k:'pm10',n:'PM10',u:'µg/m³'},
            {k:'no2',n:'NO₂',u:'µg/m³'},
            {k:'o3',n:'O₃',u:'µg/m³'},
            {k:'so2',n:'SO₂',u:'µg/m³'},
            {k:'co',n:'CO',u:'µg/m³'}
        ];
        pols.forEach(function(p) {
            html += '<div class="pollutant-item"><span class="p-val">' + (data.pollutants[p.k] != null ? data.pollutants[p.k] : '--') + '</span><span class="p-name">' + p.n + '</span></div>';
        });
        html += '</div>';
    }

    // Forecast 24h
    if (data.forecast && data.forecast.length > 0) {
        html += '<h6 class="section-title"><i class="bi bi-clock-history"></i> Próximas Horas</h6>';
        html += '<div class="forecast-scroll mb-3">';
        data.forecast.forEach(function(f) {
            var time = new Date(f.dt * 1000).toLocaleTimeString('pt-BR', {hour:'2-digit',minute:'2-digit'});
            html += '<div class="forecast-item">';
            html += '<div class="fc-time">' + time + '</div>';
            html += '<div class="fc-icon"><img src="https://openweathermap.org/img/wn/' + f.icon + '.png" alt="" width="32"></div>';
            html += '<div class="fc-temp">' + f.temp + '°</div>';
            if (f.pop > 0) html += '<div class="text-primary" style="font-size:0.7rem"><i class="bi bi-cloud-rain-fill me-1"></i>' + f.pop + '%</div>';
            html += '</div>';
        });
        html += '</div>';
    }

    // Daily Forecast
    if (data.daily_forecast && data.daily_forecast.length > 0) {
        html += '<h6 class="section-title"><i class="bi bi-calendar3"></i> Previsão 5 Dias</h6>';
        html += '<div class="forecast-scroll">';
        var dayNames = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
        data.daily_forecast.forEach(function(d) {
            var dt = new Date(d.date + 'T12:00:00');
            var dayName = dayNames[dt.getDay()];
            html += '<div class="forecast-item" style="min-width:90px">';
            html += '<div class="fc-time fw-bold">' + dayName + '</div>';
            html += '<div class="fc-icon"><img src="https://openweathermap.org/img/wn/' + d.icon + '.png" alt="" width="32"></div>';
            html += '<div class="fc-temp">' + d.temp_max + '° <small class="text-muted">' + d.temp_min + '°</small></div>';
            if (d.pop > 0) html += '<div class="text-primary" style="font-size:0.7rem"><i class="bi bi-cloud-rain-fill me-1"></i>' + d.pop + '%</div>';
            html += '</div>';
        });
        html += '</div>';
    }

    c.innerHTML = html;
}

function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function fetchClimaWidget(containerId, baseUrl) {
    var c = document.getElementById(containerId);
    if (!c) return;

    // Skeleton loading
    c.innerHTML = '<div class="row g-2">' +
        '<div class="col-6 col-md-3"><div class="skeleton" style="height:100px"></div></div>'.repeat(4) +
        '</div>';

    function doFetch(params) {
        var qs = params ? '?' + new URLSearchParams(params).toString() : '';
        fetch(baseUrl + 'api/clima' + qs)
            .then(function(r) { return r.json(); })
            .then(function(data) { renderClimaWidget(containerId, data); })
            .catch(function() { c.innerHTML = '<div class="alert alert-warning mb-0"><i class="bi bi-cloud-slash me-2"></i>Erro ao carregar dados climáticos.</div>'; });
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(pos) { doFetch({ lat: pos.coords.latitude, lon: pos.coords.longitude }); },
            function() { doFetch(); },
            { timeout: 8000 }
        );
    } else {
        doFetch();
    }
}
