<?php
/**
 * Template Name: Services (Vous êtes = service-type filter + anchors)
 */
defined('ABSPATH') || exit;

get_header();

// Fetch all services once (ordered)
$services = get_posts([
  'post_type'      => 'service',
  'post_status'    => 'publish',
  'numberposts'    => -1,
  'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
  'order'          => 'ASC',
]);

// Fetch service-type terms for the "Vous êtes..." filter
$service_type_terms = get_terms([
  'taxonomy'   => 'service-type',
  'hide_empty' => true,
  'orderby'    => 'name',
  'order'      => 'ASC',
]);

$banner = get_field('banner_image'); // ACF field on this page
$banner_url = '';

if (is_array($banner)) {
  $banner_url = $banner['sizes']['2048x2048'] ?? $banner['url'] ?? '';
} elseif (is_numeric($banner)) {
  $banner_url = wp_get_attachment_image_url($banner, '2048x2048');
} elseif (is_string($banner)) {
  $banner_url = $banner;
}

if (!$banner_url) {
  $banner_url = get_template_directory_uri() . '/img/service-banner-bg.jpg'; // fallback
}

?>
<main class="services_page">

  <section class="sec_banner_serv">
    <div class="max_container div_banner_serv" 
     style="background-image:url('<?php echo esc_url($banner_url); ?>'); background-size:cover; background-position:center; background-repeat:no-repeat;">
      <h1><?php echo esc_html( get_the_title() ); ?></h1>
    </div>
  </section>

  <section class="sec_content_serv">
    <div class="max_container div_content_serv">
      <div class="left_col_serv">

        <!-- Anchors: all services -->
        <div class="div_trouvez"
             role="navigation"
             aria-label="<?php esc_attr_e('Ancrages services', 'your-textdomain'); ?>">
          <h2><?php esc_html_e('Qu’est-ce qui vous intéresse?', 'your-textdomain'); ?></h2>
          <p><?php esc_html_e('Trouvez votre service!', 'your-textdomain'); ?></p>
          <hr>
          <ul id="service-anchor-list">
            <?php if (!empty($services)) : ?>
              <?php foreach ($services as $srv) :
                $slug = $srv->post_name;
                $service_id = 'service-' . $slug;
              ?>
              <li>
                <a href="#<?php echo esc_attr($service_id); ?>"
                   data-service-anchor="<?php echo esc_attr($slug); ?>"
                   data-filter-status="not-active"
                   aria-pressed="false">
                  <?php echo esc_html( get_the_title($srv) ); ?>
                </a>
              </li>
              <?php endforeach; ?>
            <?php else : ?>
              <li><span><?php esc_html_e('Aucun service disponible.', 'your-textdomain'); ?></span></li>
            <?php endif; ?>
          </ul>
        </div>

        <!-- VOUS ÊTES... = the ONLY filter group (service-type). No "Tous" button. -->
        <?php if (!is_wp_error($service_type_terms) && $service_type_terms) : ?>
        <div class="div_vous"
             data-filter-group
             data-filter-target-match="multi"
             data-filter-name-match="multi"
             role="group"
             aria-label="<?php esc_attr_e('Vous êtes...', 'your-textdomain'); ?>">
          <p><?php esc_html_e('Vous êtes...', 'your-textdomain'); ?></p>
          <hr>
          <ul class="vous-list" role="toolbar" aria-label="<?php esc_attr_e('Filtres service-type', 'your-textdomain'); ?>">
            <?php foreach ($service_type_terms as $term) : ?>
              <li>
                <a href="#"
                   class="vous-chip"
                   data-filter-target="<?php echo esc_attr($term->slug); ?>"
                   data-filter-status="not-active"
                   aria-pressed="false"
                   aria-controls="service-list">
                  <?php echo esc_html($term->name); ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
      </div><!-- /.left_col_serv -->

      <div class="right_col_serv">
        <div class="service-list" id="service-list" role="list">
          <?php if (!empty($services)) : ?>
            <?php foreach ($services as $srv) :
              $slug = $srv->post_name;
              $service_id = 'service-' . $slug;

              // Featured image (optional)
              $thumb_html = has_post_thumbnail($srv) ? get_the_post_thumbnail($srv->ID, 'large', ['loading' => 'lazy']) : '';

              // service-type terms for collectors and display
              $terms = get_the_terms($srv->ID, 'service-type');
              $term_slugs = [];
              if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $t) { $term_slugs[] = $t->slug; }
              }
            ?>
            <article class="service-item filter-list__item"
                     id="<?php echo esc_attr($service_id); ?>"
                     role="listitem"
                     data-filter-status="active"
                     data-filter-name="">
              <div class="service-item__media">
                <?php echo $thumb_html; ?>
              </div>
              <div class="service-item__body">
                <header class="service-item__header">
                  <h2 class="service-item__title"><?php echo esc_html( get_the_title($srv) ); ?></h2>
                  <?php if (!empty($terms) && !is_wp_error($terms)) : ?>
                    <ul class="service-item__tags">
                      <?php foreach ($terms as $t) : ?>
                        <li><?php echo esc_html($t->name); ?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                </header>
                <div class="service-item__content">
                  <?php
                    $content = apply_filters('the_content', $srv->post_content);
                    echo $content ?: wpautop( get_the_excerpt($srv) );
                  ?>
                </div>
              </div>

              <!-- Hidden collectors: populate data-filter-name with service-type term slugs -->
              <div class="visually-hidden">
                <?php
                if ($term_slugs) {
                  foreach ($term_slugs as $s) {
                    echo '<span data-filter-name-collect="' . esc_attr($s) . '"></span>';
                  }
                }
                ?>
              </div>
            </article>
            <?php endforeach; ?>
          <?php else : ?>
            <p><?php esc_html_e('Aucun service trouvé.', 'your-textdomain'); ?></p>
          <?php endif; ?>
        </div>
      </div><!-- /.right_col_serv -->
    </div><!-- /.max_container -->
  </section>
</main>

<style>
/* helpers */
.visually-hidden{position:absolute!important;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}

/* taxonomy filter visibility */
.filter-list__item[data-filter-status="active"]{display:block;opacity:1;transform:none;transition:opacity .25s ease,transform .25s ease}
.filter-list__item[data-filter-status="not-active"]{display:none}
.filter-list__item[data-filter-status="transition-out"]{display:block;opacity:0;transform:scale(.98);pointer-events:none;transition:opacity .25s ease,transform .25s ease}

/* left anchors styling */
#service-anchor-list a[data-filter-status="active"],
#service-anchor-list a.active{ text-decoration:underline; }

/* vous etes chips */
.vous-list{ list-style:none; padding:0; margin:0; display:flex; flex-wrap:wrap; gap:.5rem; }
.vous-list li{ margin:0; }
.vous-chip{ display:inline-block; padding:.375rem .6rem; border:1px solid currentColor; border-radius:999px; text-decoration:none; }
.vous-chip[data-filter-status="active"]{ font-weight:600; }

/* basic layout hooks */
.services_page .service-item{display:flex;gap:1.25rem;margin-bottom:3rem}
.services_page .service-item__media img{max-width:360px;height:auto;display:block}
.services_page .service-item__tags{display:flex;gap:.5rem;flex-wrap:wrap;margin:.5rem 0 0;padding:0;list-style:none}
.services_page .service-item__tags li{margin:0}
</style>

<script>
// ===============================
// 1) FILTER ENGINE (as provided)
// ===============================
function initMutliFilterSetupMultiMatch(){

  const transitionDelay = 300;
  const groups = [...document.querySelectorAll('[data-filter-group]')];

  groups.forEach(group => {
    const targetMatch = (group.getAttribute('data-filter-target-match') || 'multi').trim().toLowerCase(); // 'single' | 'multi'
    const nameMatch   = (group.getAttribute('data-filter-name-match')   || 'multi').trim().toLowerCase(); // 'single' | 'multi'

    const buttons = [...group.querySelectorAll('[data-filter-target]')];
    const items   = [...document.querySelectorAll('.filter-list__item[data-filter-name]')];

    // Collect tokens from children if present
    items.forEach(item => {
      const collectors = item.querySelectorAll('[data-filter-name-collect]');
      if (!collectors.length) return;
      const seen = new Set(), tokens = [];
      collectors.forEach(c => {
        const v = (c.getAttribute('data-filter-name-collect') || '').trim().toLowerCase();
        if (v && !seen.has(v)) {
          seen.add(v);
          tokens.push(v);
        }
      });
      if (tokens.length) item.setAttribute('data-filter-name', tokens.join(' '));
    });

    // Cache item tokens
    const itemTokens = new Map();
    items.forEach(el => {
      const raw = (el.getAttribute('data-filter-name') || '').trim().toLowerCase();
      const tokens = raw ? raw.split(/\s+/).filter(Boolean) : [];
      itemTokens.set(el, new Set(tokens));
    });

    const setItemState = (el, on) => {
      const next = on ? 'active' : 'not-active';
      if (el.getAttribute('data-filter-status') !== next) {
        el.setAttribute('data-filter-status', next);
        el.setAttribute('aria-hidden', on ? 'false' : 'true');
      }
    };

    const setButtonState = (btn, on) => {
      const next = on ? 'active' : 'not-active';
      if (btn.getAttribute('data-filter-status') !== next) {
        btn.setAttribute('data-filter-status', next);
        btn.setAttribute('aria-pressed', on ? 'true' : 'false');
      }
    };

    // Active tags model (multi). Start with 'all' token (shows everything).
    let activeTags = new Set(['all']);

    const hasRealActive = () => {
      // real selection = anything aside from 'all'
      return activeTags.size > 0 && !activeTags.has('all');
    };

    const resetAll = () => {
      activeTags.clear();
      activeTags.add('all');
    };

    // Matching logic (group is multi, OR within group)
    const itemMatches = (el) => {
      if (!hasRealActive()) return true; // show all if nothing real selected
      const tokens = itemTokens.get(el);
      const selected = [...activeTags];
      for (let i = 0; i < selected.length; i++) {
        if (selected[i] === 'all') continue;
        if (tokens.has(selected[i])) return true;
      }
      return false;
    };

    const paint = (rawTarget) => {
      const target = (rawTarget || '').trim().toLowerCase();

      if (target === 'all' || target === 'reset') {
        resetAll();
      } else {
        if (activeTags.has('all')) activeTags.delete('all');
        if (activeTags.has(target)) activeTags.delete(target);
        else activeTags.add(target);
        if (activeTags.size === 0) resetAll();
      }

      // Update items
      items.forEach(el => {
        if (el._ft) clearTimeout(el._ft);
        const next = itemMatches(el);
        const cur = el.getAttribute('data-filter-status');
        if (cur === 'active' && transitionDelay > 0) {
          el.setAttribute('data-filter-status','transition-out');
          el._ft = setTimeout(() => { setItemState(el, next); el._ft = null; }, transitionDelay);
        } else if (transitionDelay > 0) {
          el._ft = setTimeout(() => { setItemState(el, next); el._ft = null; }, transitionDelay);
        } else {
          setItemState(el, next);
        }
      });

      // Update buttons (we don't have an "all" button here; that's fine)
      buttons.forEach(btn => {
        const t = (btn.getAttribute('data-filter-target') || '').trim().toLowerCase();
        const on = activeTags.has(t);
        setButtonState(btn, on);
      });
    };

    group.addEventListener('click', e => {
      const btn = e.target.closest('[data-filter-target]');
      if (btn && group.contains(btn)) {
        e.preventDefault();
        paint(btn.getAttribute('data-filter-target'));
      }
    });

    // Initial: show all (no visible "all" button)
    paint('all');
  });
}

// =======================================
// 2) ANCHORS: smooth scroll + scroll-spy
// =======================================
(function(){
  const anchorList = document.getElementById('service-anchor-list');
  if (!anchorList) return;

  const anchorLinks = [...anchorList.querySelectorAll('a[data-service-anchor]')];
  const items = [...document.querySelectorAll('.filter-list__item[id]')];

  const setAnchorActive = (a, on) => {
    const next = on ? 'active' : 'not-active';
    a.dataset.filterStatus = next;
    a.setAttribute('aria-pressed', on ? 'true' : 'false');
    a.classList.toggle('active', on);
  };

  // smooth scroll on click
  anchorList.addEventListener('click', (e) => {
    const a = e.target.closest('a[data-service-anchor]');
    if (!a) return;
    const hash = a.getAttribute('href') || '';
    if (!hash.startsWith('#')) return;
    const target = document.querySelector(hash);
    if (!target) return;

    e.preventDefault();
    const y = target.getBoundingClientRect().top + window.pageYOffset;
    history.replaceState(null, '', hash);
    window.scrollTo({ top: y, behavior: 'smooth' });
  });

  // scroll spy: most centered visible item
  const getViewportCenter = () => window.pageYOffset + (window.innerHeight / 2);

  const syncFromScroll = () => {
    if (!items.length) return;
    const centerY = getViewportCenter();
    let best = null, bestDist = Infinity;
    items.forEach(el => {
      if (el.getAttribute('data-filter-status') === 'not-active') return; // hidden by filters
      const rect = el.getBoundingClientRect();
      const elCenter = rect.top + window.pageYOffset + rect.height/2;
      const dist = Math.abs(elCenter - centerY);
      if (dist < bestDist) { best = el; bestDist = dist; }
    });
    if (!best) return;

    const id = best.id ? '#' + best.id : null;
    anchorLinks.forEach(a => setAnchorActive(a, id && a.getAttribute('href') === id));
  };

  let ticking = false;
  const onScroll = () => {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(() => {
      syncFromScroll();
      ticking = false;
    });
  };
  window.addEventListener('scroll', onScroll, { passive:true });
  window.addEventListener('resize', onScroll);

  // initial
  document.addEventListener('DOMContentLoaded', syncFromScroll);

  // resync when items toggle visibility (filter)
  const obs = new MutationObserver(syncFromScroll);
  document.querySelectorAll('.filter-list__item').forEach(el => {
    obs.observe(el, { attributes:true, attributeFilter:['data-filter-status'] });
  });
})();

// boot filters
document.addEventListener('DOMContentLoaded', () => {
  initMutliFilterSetupMultiMatch();
});
</script>

<?php
get_footer();
