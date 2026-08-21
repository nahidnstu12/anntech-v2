import './bootstrap';

const nav = document.getElementById('nav');
if (nav) {
  const onHero = document.body.dataset.hasHero === '1';

  function setNav() {
    nav.classList.toggle('is-solid', !onHero || window.scrollY > 40);
  }

  setNav();
  window.addEventListener('scroll', setNav, { passive: true });

  const toggle = document.querySelector('[data-toggle]');
  toggle?.addEventListener('click', () => {
    const open = nav.classList.toggle('is-open');
    nav.classList.add('is-solid');
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
  });
}

const heroShot = document.getElementById('hero-shot');
document.querySelectorAll('.thumb').forEach((btn) => {
  btn.addEventListener('click', () => {
    if (!heroShot) return;
    heroShot.src = btn.dataset.src;
    document.querySelectorAll('.thumb').forEach((t) => t.classList.toggle('is-on', t === btn));
  });
});
