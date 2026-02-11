/*
 * Quantity stepper enhancements for WooCommerce.
 * Adds +/- buttons around any input.qty and keeps values in sync.
 * Dependency-free and safe to run multiple times.
 */
(function () {
  function toNumber(v, fallback) {
    var n = parseFloat(String(v));
    return Number.isFinite(n) ? n : fallback;
  }

  function triggerEvents(input) {
    try {
      input.dispatchEvent(new Event('input', { bubbles: true }));
      input.dispatchEvent(new Event('change', { bubbles: true }));
    } catch (e) {
      // Very old browsers fallback
      var evt = document.createEvent('Event');
      evt.initEvent('change', true, true);
      input.dispatchEvent(evt);
    }
  }

  function step(input, direction) {
    var stepVal = toNumber(input.getAttribute('step'), 1);
    if (!Number.isFinite(stepVal) || stepVal <= 0) stepVal = 1;

    var minVal = input.getAttribute('min');
    minVal = minVal === null || minVal === '' ? null : toNumber(minVal, null);
    var maxVal = input.getAttribute('max');
    maxVal = maxVal === null || maxVal === '' ? null : toNumber(maxVal, null);

    var cur = toNumber(input.value, 0);
    var next = cur + (direction * stepVal);
    if (minVal !== null) next = Math.max(minVal, next);
    if (maxVal !== null) next = Math.min(maxVal, next);

    // Respect browser validity when possible.
    input.value = String(next);
    triggerEvents(input);
  }

  function enhanceQuantity(quantityEl) {
    if (!quantityEl || quantityEl.dataset.lmQtyEnhanced === '1') return;
    var input = quantityEl.querySelector('input.qty');
    if (!input) return;

    // Build wrapper.
    var wrap = document.createElement('div');
    wrap.className = 'lm-qty-wrap';

    var minus = document.createElement('button');
    minus.type = 'button';
    minus.className = 'lm-qty-btn lm-qty-minus';
    minus.setAttribute('aria-label', 'Decrease quantity');
    minus.textContent = '−';

    var plus = document.createElement('button');
    plus.type = 'button';
    plus.className = 'lm-qty-btn lm-qty-plus';
    plus.setAttribute('aria-label', 'Increase quantity');
    plus.textContent = '+';

    // Move input into wrapper.
    var parent = input.parentNode;
    wrap.appendChild(minus);
    wrap.appendChild(input);
    wrap.appendChild(plus);

    // Replace the original placement (keep quantityEl in DOM so Woo hooks still work).
    parent.appendChild(wrap);

    minus.addEventListener('click', function () {
      step(input, -1);
    });
    plus.addEventListener('click', function () {
      step(input, 1);
    });

    // If user types manually, normalize to min.
    input.addEventListener('blur', function () {
      var minVal = input.getAttribute('min');
      minVal = minVal === null || minVal === '' ? null : toNumber(minVal, null);
      var cur = toNumber(input.value, 0);
      if (minVal !== null && cur < minVal) {
        input.value = String(minVal);
        triggerEvents(input);
      }
    });

    quantityEl.dataset.lmQtyEnhanced = '1';
  }

  function run(root) {
    var scope = root || document;
    var quantities = scope.querySelectorAll('.quantity');
    for (var i = 0; i < quantities.length; i++) {
      enhanceQuantity(quantities[i]);
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    run(document);

    // WooCommerce updates fragments (mini cart, cart totals) via AJAX.
    document.body.addEventListener('updated_wc_div', function () {
      run(document);
    });
    document.body.addEventListener('wc_fragments_loaded', function () {
      run(document);
    });
    document.body.addEventListener('wc_fragments_refreshed', function () {
      run(document);
    });
  });
})();
