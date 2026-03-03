(function() {
    var toggle = document.querySelector('.nav-toggle');
    var nav = document.querySelector('.nav-main');
    if (toggle && nav) {
        toggle.addEventListener('click', function() {
            nav.classList.toggle('open');
        });
    }
})();
