document.addEventListener('DOMContentLoaded', () => {
  const burger = document.querySelector('.burger_menu_icon');
  const close  = document.querySelector('.close_menu_icon');
  const header = document.querySelector('.main_header');
  const menu   = document.querySelector('.dropdown_menu');
  if (!header || !menu) return;

  const open  = () => { header.classList.add('menu_open'); document.body.classList.add('menu_open'); };
  const shut  = () => { header.classList.remove('menu_open'); document.body.classList.remove('menu_open'); };

  burger?.addEventListener('click', open);
  close?.addEventListener('click', shut);

  menu.querySelectorAll('a').forEach(a => a.addEventListener('click', shut));

  document.addEventListener('click', e => {
    const t = e.target;
    if (!(t instanceof Element)) return;
    if (header.classList.contains('menu_open') && !header.contains(t) && !menu.contains(t)) shut();
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && header.classList.contains('menu_open')) shut();
  });
});
