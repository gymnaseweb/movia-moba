<?php
/**
 * Template Name: Projects (Filters)
 * Description: Page template that renders project filters from custom taxonomies.
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
  // Adjust CPT slug if needed
  $archive_url = get_post_type_archive_link('projects') ?: home_url('/');

  // Map taxonomies to UI labels
  $filters = [
    'projects-type'          => __('Type de projet', 'your-textdomain'),
    'projects-type-audience' => __('Type de clientèle', 'your-textdomain'),
    'projects-types-area'    => __('Territoire', 'your-textdomain'),
  ];

  $term_args = [
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
  ];
  ?>

<?php
// ---- Config
$archive_url = get_post_type_archive_link('projects') ?: home_url('/');
$filters = [
  'projects-type'          => __('Type de projet', 'your-textdomain'),
  'projects-type-audience' => __('Type de clientèle', 'your-textdomain'),
  'projects-types-area'    => __('Territoire', 'your-textdomain'),
];

// ---- Lire filtres actifs (1 terme par taxo via ?taxo=slug)
$active = [];
foreach ($filters as $tax => $_label) {
  if (isset($_GET[$tax]) && $_GET[$tax] !== '') {
    $active[$tax] = sanitize_title(wp_unslash($_GET[$tax]));
  }
}

// ---- Helper URL (reconstruit l’URL de la page en conservant/modifiant les filtres)
function movia_build_filter_url(array $overrides, array $filters): string {
  $base    = get_permalink();
  $current = [];
  foreach ($filters as $tax => $_) {
    if (isset($_GET[$tax]) && $_GET[$tax] !== '') {
      $current[$tax] = sanitize_title(wp_unslash($_GET[$tax]));
    }
  }
  foreach ($overrides as $k => $v) {
    if ($v === null) unset($current[$k]); else $current[$k] = $v;
  }
  // on reset la pagination quand on change de filtre
  unset($current['paged'], $current['page']);
  return esc_url(add_query_arg($current, $base));
}
?>

<section class="sec_filter_nos">
  <div class="max_container div_filter_nos">
    <div class="div_clear_filter">
      <?php
      // URL pour retirer tous les filtres
      $clear_all_url = movia_build_filter_url(
        array_fill_keys(array_keys($filters), null),
        $filters
      );
      ?>
      <a class="btn_clear_filter" href="<?php echo $clear_all_url; ?>">
        <?php esc_html_e('Filtres', 'your-textdomain'); ?> <span>×</span>
      </a>
    </div>

    <?php foreach ($filters as $tax => $label): ?>
      <?php
      $terms = get_terms([
        'taxonomy'   => $tax,
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
      ]);
      if (is_wp_error($terms) || empty($terms)) continue;

      $is_none_selected = !isset($active[$tax]) || $active[$tax] === '';
      ?>
      <div class="div_filtres">
        <p><?php echo esc_html($label); ?></p>
        <ul>
          <li class="<?php echo $is_none_selected ? 'selected' : ''; ?>">
            <a href="<?php echo movia_build_filter_url([$tax => null], $filters); ?>">
              <?php esc_html_e('Tous', 'your-textdomain'); ?>
            </a>
          </li>

          <?php foreach ($terms as $term): ?>
            <?php
            $term_link = get_term_link($term);
            if (is_wp_error($term_link)) continue;

            $icon = get_field('cat_icon', $term); // ACF (SVG textarea) optionnel
            $selected = (isset($active[$tax]) && $active[$tax] === $term->slug) ? 'selected' : '';
            $url = movia_build_filter_url([$tax => $term->slug], $filters);
            ?>
            <li class="<?php echo $selected; ?>">
              <a href="<?php echo $url; ?>"
                 title="<?php echo esc_attr(sprintf(__('Voir tout dans %s', 'your-textdomain'), $term->name)); ?>">
                <?php if ($icon) echo $icon; ?>
                <span><?php echo esc_html($term->name); ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php
// ---- Construire la WP_Query pour la grille
$tax_query = [];
foreach ($active as $tax => $slug) {
  $tax_query[] = [
    'taxonomy' => $tax,
    'field'    => 'slug',
    'terms'    => $slug,
  ];
}
if (count($tax_query) > 1) {
  $tax_query = array_merge(['relation' => 'AND'], $tax_query);
}

// Pagination (paged ou page, selon contexte)
$paged = max(1, get_query_var('paged') ?: get_query_var('page') ?: 1);

$q = new WP_Query([
  'post_type'      => 'projects',
  'post_status'    => 'publish',
  'posts_per_page' => 12,
  'paged'          => $paged,
  'tax_query'      => $tax_query ?: null,
]);

?>

<?php
// Put this near the top of your template (before the loop) or in functions.php
function movia_get_project_image_html($post_id) {
  $img = get_field('project_image_1', $post_id);
  $fallback_alt = get_the_title($post_id);

  $src = '';
  $alt = $fallback_alt;

  if (is_array($img)) { // ACF image array
    $src = $img['sizes']['large'] ?? $img['url'] ?? '';
    if (!empty($img['alt'])) $alt = $img['alt'];
  } elseif (is_numeric($img)) { // attachment ID
    $src = wp_get_attachment_image_url($img, 'large');
    $alt_meta = get_post_meta($img, '_wp_attachment_image_alt', true);
    if ($alt_meta !== '') $alt = $alt_meta;
  } elseif (is_string($img)) { // direct URL
    $src = $img;
  }

  if ($src) {
    // Handles jpg/png/webp/svg — same markup is fine
    return sprintf(
      '<img src="%s" alt="%s" loading="lazy" />',
      esc_url($src),
      esc_attr($alt)
    );
  }

  // Fallback placeholder
  $ph = get_template_directory_uri() . '/img/projet/placeholder.jpg';
  return '<img src="'.esc_url($ph).'" alt="" loading="lazy" />';
}
?>


<section class="sec_projets_grid">
  <div class="max_container div_projets_grid">
    <div class="grid_projets_boxes">
      <?php if ($q->have_posts()): ?>
        <?php while ($q->have_posts()): $q->the_post(); ?>
          <?php
          $permalink = get_permalink();
          // Image: d’abord l’image mise en avant, sinon un ACF éventuel, sinon placeholder
          $img_html = movia_get_project_image_html(get_the_ID());

          // Récupérer les 3 taxos pour les tags sous le titre
          $tax_names = array_keys($filters);
          $terms_by_tax = [];
          foreach ($tax_names as $tname) {
            $terms_by_tax[$tname] = get_the_terms(get_the_ID(), $tname);
          }

          // Helper pour fabriquer un lien de tag qui applique/écrase SEULEMENT sa taxo
          $make_tag_url = function($taxonomy, $slug) use ($filters) {
            return movia_build_filter_url([$taxonomy => $slug], $filters);
          };
          ?>
          <div class="projet_box">
            <a href="<?php echo esc_url($permalink); ?>">
                <?php echo $img_html; ?>
            </a>

            <div class="projet_info">
              <div class="projet_tags">
                <ul>
                  <?php
                  // Affiche jusqu’à un terme par taxo dans l’ordre défini par $filters
                  foreach ($tax_names as $tname) {
                    if (!empty($terms_by_tax[$tname]) && !is_wp_error($terms_by_tax[$tname])) {
                      foreach ($terms_by_tax[$tname] as $t) {
                        // Lien qui garde les autres filtres, en remplaçant celui-ci
                        $tag_url = $make_tag_url($tname, $t->slug);
                        echo '<li><a href="'.esc_url($tag_url).'">'.esc_html($t->name).'</a></li>';
                        // Si tu veux seulement le premier terme par taxo, décommente la ligne suivante:
                        // break;
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
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else: ?>
        <p><?php esc_html_e('Aucun projet ne correspond aux filtres sélectionnés.', 'your-textdomain'); ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>
    <div class="div_plus_projets">
        <div class="max_container">
            <a href="/">Voir plus de projets</a>
        </div>
    </div>
<?php
// ---- Pagination (conserve les filtres actifs)
$total_pages = $q->max_num_pages;
if ($total_pages > 1) {
  $big = 999999999;
  $current_url_params = [];
  foreach ($filters as $tax => $_) {
    if (!empty($active[$tax])) $current_url_params[$tax] = $active[$tax];
  }

  $links = paginate_links([
    'base'      => str_replace($big, '%#%', esc_url(add_query_arg($current_url_params, get_pagenum_link($big)))),
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



</main>

<?php
get_footer();
?>