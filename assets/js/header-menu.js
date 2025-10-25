// document.addEventListener('DOMContentLoaded', () => {
//   const burger = document.querySelector('.burger_menu_icon');
//   const close  = document.querySelector('.close_menu_icon');
//   const header = document.querySelector('.main_header');
//   const menu   = document.querySelector('.dropdown_menu');
//   if (!header || !menu) return;

//   const open  = () => { header.classList.add('menu_open'); document.body.classList.add('menu_open'); };
//   const shut  = () => { header.classList.remove('menu_open'); document.body.classList.remove('menu_open'); };

//   burger?.addEventListener('click', open);
//   close?.addEventListener('click', shut);

//   menu.querySelectorAll('a').forEach(a => a.addEventListener('click', shut));

//   document.addEventListener('click', e => {
//     const t = e.target;
//     if (!(t instanceof Element)) return;
//     if (header.classList.contains('menu_open') && !header.contains(t) && !menu.contains(t)) shut();
//   });

//   document.addEventListener('keydown', e => {
//     if (e.key === 'Escape' && header.classList.contains('menu_open')) shut();
//   });
// });


const ham   = document.querySelector('.burger_menu_icon');
const menu  = document.querySelector('.dropdown_menu .animated_menu');
const links = menu.querySelectorAll(':scope > li');

// Closed state (no flash)
gsap.set(menu,  { height: 0, overflow: 'hidden' });
gsap.set(links, { autoAlpha: 0, y: 60 });

const tl = gsap.timeline({ paused: true });

tl.to(menu, {
  duration: 0.8,
  height: '100vh',
  ease: 'expo.inOut'
})
.to(links, {
  duration: 0.6,
  autoAlpha: 1,
  y: 0,
  stagger: 0.08,
  ease: 'expo.out'
}, '-=0.3');

// Force a known starting point: CLOSED
tl.progress(0).pause().reversed(true);

// Click = toggle open/close (explicit, reliable)
ham.addEventListener('click', () => {
  if (tl.reversed()) tl.play(); else tl.reverse();
});

document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.main_header');
  if (!header) return;

  // What counts as the "hero" you want to clear before showing header
  // (pick the most specific you have; this matches your Flip hero)
  const hero = document.querySelector('.sec_banner_home .scaling-element-header')
            || document.querySelector('.sec_banner_home')
            || document.body;

  // If you have the WP admin bar, we’ll offset a bit to avoid clash
  const ADMIN_BAR_OFFSET = document.body.classList.contains('admin-bar') ? 32 : 0;
  const HEADER_REVEAL_OFFSET = 0; // tweak (+/- px) to show a bit earlier/later

  let heroBottomY = 0;

  const measure = () => {
    const r = hero.getBoundingClientRect();
    // absolute Y of hero's bottom in the document
    heroBottomY = window.scrollY + r.bottom;
  };

  const onScroll = () => {
    const y = window.scrollY || 0;
    if (y >= heroBottomY - ADMIN_BAR_OFFSET - HEADER_REVEAL_OFFSET) {
      header.classList.add('is-visible');
    } else if (!header.classList.contains('menu_open')) {
      // keep visible if menu is open
      header.classList.remove('is-visible');
    }
  };

  // init
  header.classList.remove('is-visible');
  measure();
  onScroll();

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', () => {
    // debounce-light
    clearTimeout(measure._t);
    measure._t = setTimeout(() => { measure(); onScroll(); }, 120);
  });
});
