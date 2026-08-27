// =========================================================
// RIPPLE EFFECT ON BUTTONS
// =========================================================
document.addEventListener('click', function (e) {
    var btn = e.target.closest('button');
    if (!btn) return;

    var ripple = document.createElement('span');
    ripple.classList.add('ripple');

    var rect = btn.getBoundingClientRect();
    var size = Math.max(rect.width, rect.height);
    ripple.style.width  = ripple.style.height = size + 'px';
    ripple.style.left   = (e.clientX - rect.left - size / 2) + 'px';
    ripple.style.top    = (e.clientY - rect.top  - size / 2) + 'px';

    btn.appendChild(ripple);
    ripple.addEventListener('animationend', function () { ripple.remove(); });
});

// =========================================================
// ACTIVE SIDEBAR LINK HIGHLIGHT
// =========================================================
(function () {
    var links = document.querySelectorAll('.sidebar a');
    var current = window.location.pathname.split('/').pop();
    links.forEach(function (link) {
        var href = link.getAttribute('href');
        if (href === current) {
            link.classList.add('active');
        }
    });
})();

// =========================================================
// NUMBER COUNTER ANIMATION (dashboard .number elements)
// =========================================================
(function () {
    function animateCount(el) {
        var target = parseFloat(el.textContent.replace(/,/g, ''));
        if (isNaN(target)) return;
        var isFloat = el.textContent.includes('.');
        var start = 0;
        var duration = 900;
        var startTime = null;

        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = Math.min((timestamp - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            var value = start + (target - start) * eased;
            el.textContent = isFloat
                ? value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                : Math.round(value).toLocaleString('en-US');
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                animateCount(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });

    document.querySelectorAll('.card .number, .finance-card h2').forEach(function (el) {
        observer.observe(el);
    });
})();

// =========================================================
// TABLE ROW STAGGER ANIMATION
// =========================================================
(function () {
    var rows = document.querySelectorAll('tbody tr');
    rows.forEach(function (row, i) {
        row.style.animationDelay = (i * 0.05) + 's';
    });
})();
