/**
 * Green Air v2.0 — Main JavaScript
 * Theme toggle, AOS, toasts, photo preview, form protection
 */
document.addEventListener('DOMContentLoaded', function() {
    // ---- AOS Init ----
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 600, once: true, offset: 50 });
    }

    // ---- Dark Mode Toggle ----
    const savedTheme = localStorage.getItem('ga-theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    applyTheme(savedTheme);

    document.querySelectorAll('#theme-toggle-desktop, #theme-toggle-mobile').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var current = document.documentElement.getAttribute('data-bs-theme');
            var next = current === 'dark' ? 'light' : 'dark';
            applyTheme(next);
            localStorage.setItem('ga-theme', next);
        });
    });

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        document.querySelectorAll('#theme-toggle-desktop i, #theme-toggle-mobile i').forEach(function(icon) {
            icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
        });
    }

    // ---- Photo Upload Preview ----
    document.querySelectorAll('.photo-upload-area input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function() {
            var area = this.closest('.photo-upload-area');
            var existing = area.querySelector('.preview-img');
            if (existing) existing.remove();
            var placeholder = area.querySelector('.upload-placeholder');

            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (placeholder) placeholder.style.display = 'none';
                    var img = document.createElement('img');
                    img.className = 'preview-img';
                    img.src = e.target.result;
                    area.insertBefore(img, area.firstChild);
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                if (placeholder) placeholder.style.display = '';
            }
        });
    });

    // ---- Form Double-Submit Protection ----
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function() {
            var btn = form.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                btn.disabled = true;
                var originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Aguarde...';
                setTimeout(function() {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }, 5000);
            }
        });
    });

    // ---- Toast Helper ----
    window.gaToast = function(message, type) {
        type = type || 'success';
        var container = document.getElementById('toast-container');
        if (!container) return;
        var colors = { success: 'bg-success', error: 'bg-danger', warning: 'bg-warning text-dark', info: 'bg-info text-dark' };
        var icons = { success: 'bi-check-circle', error: 'bi-exclamation-circle', warning: 'bi-exclamation-triangle', info: 'bi-info-circle' };
        var div = document.createElement('div');
        div.className = 'toast show align-items-center text-white ' + (colors[type] || 'bg-success') + ' border-0';
        div.setAttribute('role', 'alert');
        div.innerHTML = '<div class="d-flex"><div class="toast-body d-flex align-items-center gap-2"><i class="bi ' + (icons[type] || '') + '"></i>' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
        container.appendChild(div);
        setTimeout(function() { div.classList.add('opacity-0'); setTimeout(function() { div.remove(); }, 300); }, 4000);
    };

    // ---- Notification Dropdown (Desktop + Mobile) ----
    var notifBtns = document.querySelectorAll('#desktop-notif-btn, #mobile-notif-btn');
    notifBtns.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            loadNotifications();
        });
    });

    function loadNotifications() {
        fetch(window.BASE_URL + 'api/notificacoes', { headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN || '' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!Array.isArray(data) || data.length === 0) {
                    gaToast('Nenhuma notificação no momento.', 'info');
                    return;
                }
                data.slice(0, 5).forEach(function(n) {
                    gaToast(n.title + ': ' + n.message, n.is_read ? 'info' : 'success');
                    if (!n.is_read) {
                        fetch(window.BASE_URL + 'api/notificacoes/ler/' + n.id, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN || '' }
                        });
                    }
                });
            })
            .catch(function() {});
    }
});
