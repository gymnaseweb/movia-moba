<?php
/**
 * Template Name: Campagnes et événements
 */
defined('ABSPATH') || exit;

get_header();
?>

<main class="nos_projets_page">
  <div class="nos_projets_title">
    <div class="max_container">
      <h1><?php echo esc_html( get_the_title() ); ?></h1>
    </div>
  </div>

<?php
// ---- Single filter taxonomy
$filters = [
  'event_type' => __('Type d’événement', 'your-textdomain'),
];

// ---- Event image helper (featured image > ACF event_image > placeholder)
function movia_get_event_image_html($post_id) {
  // 1) Featured image first
  if (has_post_thumbnail($post_id)) {
    return get_the_post_thumbnail($post_id, 'large', ['loading' => 'lazy']);
  }

  // 2) Fallback to ACF event_image (array | ID | URL)
  $img = get_field('event_image', $post_id);
  $fallback_alt = get_the_title($post_id);
  $src = '';
  $alt = $fallback_alt;

  if (is_array($img)) {
    $src = $img['sizes']['large'] ?? $img['url'] ?? '';
    if (!empty($img['alt'])) $alt = $img['alt'];
  } elseif (is_numeric($img)) {
    $src = wp_get_attachment_image_url($img, 'large');
    $alt_meta = get_post_meta($img, '_wp_attachment_image_alt', true);
    if ($alt_meta !== '') $alt = $alt_meta;
  } elseif (is_string($img)) {
    $src = $img;
  }

  if ($src) {
    return sprintf('<img src="%s" alt="%s" loading="lazy" />', esc_url($src), esc_attr($alt));
  }

  // 3) Placeholder
  $ph = get_template_directory_uri() . '/img/projet/placeholder.jpg';
  return '<img src="'.esc_url($ph).'" alt="" loading="lazy" />';
}
?>

<section class="sec_filter_nos">
  <div class="max_container div_filter_nos">

    <?php
    // --- Render the single taxonomy filter group with a "Tous" button
    foreach ($filters as $tax => $label):
      $terms = get_terms([
        'taxonomy'   => $tax,
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
      ]);
      if (is_wp_error($terms) || empty($terms)) continue;
    ?>
      <div class="div_filtres filter-group"
           data-filter-group
           data-filter-target-match="multi"
           data-filter-name-match="multi"
           role="group"
           aria-label="<?php echo esc_attr($label); ?>">

        <p><?php echo esc_html($label); ?></p>

        <div class="filter-buttons" role="toolbar" aria-label="<?php echo esc_attr($label); ?>">
          <button type="button"
                  class="filter-btn"
                  data-filter-target="all"
                  data-filter-status="active"
                  aria-pressed="true"
                  aria-controls="filter-list">
            <?php esc_html_e('Tous', 'your-textdomain'); ?>
          </button>

          <?php foreach ($terms as $term): ?>
            <button type="button"
                    class="filter-btn"
                    data-filter-target="<?php echo esc_attr($term->slug); ?>"
                    data-filter-status="not-active"
                    aria-pressed="false"
                    aria-controls="filter-list"
                    title="<?php echo esc_attr(sprintf(__('Voir %s', 'your-textdomain'), $term->name)); ?>">
              <span><?php echo esc_html($term->name); ?></span>
            </button>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <?php
    // --- Build the query (client-side filtering only)
    $paged = max(1, get_query_var('paged') ?: get_query_var('page') ?: 1);
    $q = new WP_Query([
      'post_type'      => 'event',
      'post_status'    => 'publish',
      'posts_per_page' => 12,
      'paged'          => $paged,
      'orderby'        => ['date' => 'DESC', 'title' => 'ASC'],
    ]);
    ?>

    <div class="filter-list grid_projets_boxes" role="list" id="filter-list">
      <?php if ($q->have_posts()): ?>
        <?php
        $tax_names = array_keys($filters); // ['event_type']
        while ($q->have_posts()): $q->the_post();
          $permalink = get_permalink();
          $img_html  = movia_get_event_image_html(get_the_ID());

          // Collect only event_type terms
          $terms_by_tax = [];
          foreach ($tax_names as $tname) {
            $terms_by_tax[$tname] = get_the_terms(get_the_ID(), $tname);
          }
        ?>
          <div class="projet_box filter-list__item"
               role="listitem"
               data-filter-status="active"
               data-filter-name="">
            <a href="<?php echo esc_url($permalink); ?>">
              <?php echo $img_html; ?>
            </a>

            <div class="projet_info">
              <div class="projet_tags">
                <ul>
                  <?php
                  foreach ($tax_names as $tname) {
                    if (!empty($terms_by_tax[$tname]) && !is_wp_error($terms_by_tax[$tname])) {
                      foreach ($terms_by_tax[$tname] as $t) {
                        echo '<li><a href="'.esc_url(get_term_link($t)).'">'.esc_html($t->name).'</a></li>';
                      }
                    }
                  }
                  ?>
                </ul>
              </div>

              <a href="<?php echo esc_url($permalink); ?>">
                <h2><?php the_title(); ?></h2>
              </a>
            </div>

            <!-- Hidden collectors: JS aggregates slugs into data-filter-name -->
            <div class="visually-hidden">
              <?php
              foreach ($tax_names as $tname) {
                if (!empty($terms_by_tax[$tname]) && !is_wp_error($terms_by_tax[$tname])) {
                  foreach ($terms_by_tax[$tname] as $t) {
                    echo '<span data-filter-name-collect="'.esc_attr($t->slug).'"></span>';
                  }
                }
              }
              ?>
            </div>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else: ?>
        <p><?php esc_html_e('Aucun événement ne correspond.', 'your-textdomain'); ?></p>
      <?php endif; ?>
    </div>

    <?php
    // --- Pagination (preserves client-side UX per page)
    $total_pages = $q->max_num_pages;
    if ($total_pages > 1) {
      $big = 999999999;
      $links = paginate_links([
        'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format'    => '?paged=%#%',
        'current'   => $paged,
        'total'     => $total_pages,
        'prev_text' => '«',
        'next_text' => '»',
        'type'      => 'list',
      ]);
      if ($links) {
        echo '<nav class="div_plus_projets"><div class="max_container">'.$links.'</div></nav>';
      }
    }
    ?>

  </div>
</section>

</main>

<style>
.visually-hidden{
  position:absolute!important; width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;
  clip:rect(0,0,0,0);white-space:nowrap;border:0;
}
/* Item visibility + small fade */
.filter-list [data-filter-status="active"]{
  display:block;
  opacity:1; transform:none; transition:opacity .25s ease, transform .25s ease;
}
.filter-list [data-filter-status="not-active"]{ display:none; }
.filter-list [data-filter-status="transition-out"]{
  display:block;
  opacity:0; transform:scale(0.98); pointer-events:none; transition:opacity .25s ease, transform .25s ease;
}
/* Button active state hooks */
.filter-btn[data-filter-status="active"]{ /* style active */ }
.filter-btn[data-filter-status="not-active"]{ /* style idle */ }
.div_filtres{ margin-bottom:1rem; }
.filter-buttons{ display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; }
.div_filtres > p{ font-weight:600; margin:.25rem 0 .5rem; }
</style>

<script>
/**
 * Single-group filter (event_type) with “Tous”.
 * – Uses the same multi-group engine; only one group is present here.
 * – OR logic within the group (data-filter-name-match="multi").
 * – Transition-out supported via data-filter-status="transition-out".
 */
(function(){
  const transitionDelay = 300;

  // Resolve the list
  const list = document.getElementById('filter-list') || document.querySelector('[role="list"]');
  if (!list) return;

  const items = [...list.querySelectorAll('[data-filter-name]')];

  // Populate data-filter-name from collectors
  items.forEach(item => {
    const collectors = item.querySelectorAll('[data-filter-name-collect]');
    if (!collectors.length) return;
    const seen = new Set(), tokens = [];
    collectors.forEach(c => {
      const v = (c.getAttribute('data-filter-name-collect') || '').trim().toLowerCase();
      if (v && !seen.has(v)) { seen.add(v); tokens.push(v); }
    });
    if (tokens.length) item.setAttribute('data-filter-name', tokens.join(' '));
  });

  // Cache tokens
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

  // Build the single group
  const groups = [...document.querySelectorAll('[data-filter-group]')].map(group => {
    const targetMatch = (group.getAttribute('data-filter-target-match') || 'multi').trim().toLowerCase(); // 'multi'
    const nameMatch   = (group.getAttribute('data-filter-name-match')   || 'multi').trim().toLowerCase(); // 'multi'
    const buttons     = [...group.querySelectorAll('[data-filter-target]')];

    let activeTags = new Set(['all']); // start as "Tous"

    const hasRealActive = () => activeTags.size > 0 && !activeTags.has('all');
    const resetAll = () => { activeTags.clear(); activeTags.add('all'); };

    const toggleTarget = (rawTarget) => {
      const target = (rawTarget || '').trim().toLowerCase();
      if ((target === 'all' || target === 'reset') && !hasRealActive()) return;

      if (target === 'all' || target === 'reset') {
        resetAll();
      } else {
        if (activeTags.has('all')) activeTags.delete('all');
        if (activeTags.has(target)) activeTags.delete(target);
        else activeTags.add(target);
        if (activeTags.size === 0) resetAll();
      }

      // Update button states
      buttons.forEach(btn => {
        const t = (btn.getAttribute('data-filter-target') || '').trim().toLowerCase();
        let on = false;
        if (t === 'all') on = !hasRealActive();
        else if (t === 'reset') on = hasRealActive();
        else on = activeTags.has(t);
        setButtonState(btn, on);
      });
    };

    // Init buttons
    buttons.forEach(btn => {
      if (!btn.hasAttribute('aria-controls')) btn.setAttribute('aria-controls', 'filter-list');
      const t = (btn.getAttribute('data-filter-target') || '').trim().toLowerCase();
      setButtonState(btn, t === 'all'); // only "Tous" starts active
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        toggleTarget(btn.getAttribute('data-filter-target'));
        recompute();
      });
    });

    return {
      nameMatch,
      getActiveDescriptor() {
        if (!hasRealActive()) return null; // no effective filter
        return { mode: 'multi', tags: new Set(activeTags) }; // OR within group
      }
    };
  });

  // Apply filters
  function recompute() {
    items.forEach(el => {
      if (el._ft) clearTimeout(el._ft);
      const tokens = itemTokens.get(el);

      // Must pass all active groups (we have one)
      let visible = true;
      for (let i = 0; i < groups.length; i++) {
        const g = groups[i];
        const desc = g.getActiveDescriptor();
        if (!desc) continue; // no active selection => ignore
        // OR logic within group
        let any = false;
        for (const tag of desc.tags) {
          if (tag === 'all') continue;
          if (tokens.has(tag)) { any = true; break; }
        }
        if (!any) { visible = false; break; }
      }

      const cur = el.getAttribute('data-filter-status');
      if (cur === 'active' && transitionDelay > 0) {
        el.setAttribute('data-filter-status', 'transition-out');
        el._ft = setTimeout(() => { setItemState(el, visible); el._ft = null; }, transitionDelay);
      } else if (transitionDelay > 0) {
        el._ft = setTimeout(() => { setItemState(el, visible); el._ft = null; }, transitionDelay);
      } else {
        setItemState(el, visible);
      }
    });
  }

  // Initial paint
  recompute();
})();
</script>

<?php
get_footer();
