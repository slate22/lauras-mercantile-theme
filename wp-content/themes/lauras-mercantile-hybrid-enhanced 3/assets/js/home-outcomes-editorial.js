(() => {
  // Homepage-only: converts the existing React outcomes grid into the locked editorial triptych.
  // Safe: no dependencies, no mutation outside the outcomes section.

  const SELECTOR = '.lm-outcomes';

  function prunePanel(panel) {
    // Remove illustration/media wrappers and any extra UI lines.
    panel.querySelectorAll('.lm-outcome-media, .lm-outcome-micro').forEach((n) => n.remove());

    // Keep ONLY: eyebrow (outcome title), title (emotional promise), cta.
    const keep = new Set(['lm-outcome-eyebrow', 'lm-outcome-title', 'lm-outcome-cta']);
    Array.from(panel.children).forEach((child) => {
      const cls = child.classList;
      if (!cls) return;
      const ok = Array.from(cls).some((c) => keep.has(c));
      if (!ok) child.remove();
    });
  }

  function apply() {
    const section = document.querySelector(SELECTOR);
    if (!section) return false;

    // Remove explicit funnel guidance line.
    const kicker = section.querySelector('.lm-kicker');
    if (kicker) kicker.remove();

    // Add a hook class for scoped CSS.
    section.classList.add('lm-outcomes--editorial');

    // Prune each panel.
    section.querySelectorAll('.lm-outcome-panel').forEach(prunePanel);

    return true;
  }

  // React renders after DOMContentLoaded; use a short observer with a hard stop.
  function boot() {
    if (apply()) return;

    const root = document.getElementById('lm-react-root') || document.body;
    const obs = new MutationObserver(() => {
      if (apply()) obs.disconnect();
    });
    obs.observe(root, { childList: true, subtree: true });

    // Hard stop after 8s to avoid lingering observers.
    window.setTimeout(() => obs.disconnect(), 8000);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot, { once: true });
  } else {
    boot();
  }
})();
