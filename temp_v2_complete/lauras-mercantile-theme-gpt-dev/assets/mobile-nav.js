(function () {
  var toggle = document.querySelector('.lm-mobile-toggle');
  var drawer = document.getElementById('lm-mobile-drawer');
  var overlay = document.querySelector('.lm-mobile-overlay');
  if (!toggle || !drawer || !overlay) return;

  function setOpen(open) {
    drawer.classList.toggle('is-open', open);
    overlay.hidden = !open;
    drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.documentElement.classList.toggle('lm-no-scroll', open);
  }

  toggle.addEventListener('click', function () {
    setOpen(!drawer.classList.contains('is-open'));
  });

  document.addEventListener('click', function (e) {
    var el = e.target;
    if (!el) return;
    if (el.matches('[data-lm-close="1"]')) setOpen(false);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') setOpen(false);
  });
})();