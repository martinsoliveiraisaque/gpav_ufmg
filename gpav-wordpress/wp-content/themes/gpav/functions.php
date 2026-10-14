<?php
/** GPAV theme: design only. Content models and shortcodes live in gpav-content. */
defined('ABSPATH') || exit;
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    register_nav_menus(['gpav_pt' => 'GPAV Português', 'gpav_en' => 'GPAV English']);
});
function gpav_lang() {
    $lang = get_post_meta(get_queried_object_id(), '_gpav_lang', true);
    if (in_array($lang, ['pt', 'en'], true)) return $lang;
    return get_query_var('gpav_lang') === 'en' ? 'en' : 'pt';
}
function gpav_t($pt, $en) { return gpav_lang() === 'pt' ? $pt : $en; }
function gpav_image($path) {
    $uploads = wp_upload_dir();
    return trailingslashit($uploads['baseurl']) . 'gpav/' . ltrim($path, '/');
}
function gpav_theme_image($key, $fallback) {
    return get_theme_mod('gpav_' . $key, '') ?: gpav_image($fallback);
}
function gpav_brand_title() {
    return get_theme_mod('gpav_title_' . gpav_lang(), gpav_t('Grupo de Pesquisa em Arboviroses', 'Research Group on Arboviral Diseases'));
}
function gpav_setting($key) {
    $defaults = [
        'subtitle_pt' => 'Departamento de Morfologia · ICB · UFMG',
        'subtitle_en' => 'Department of Morphology · ICB · UFMG',
        'footer_pt' => "Grupo de Pesquisa em Arboviroses\nDepartamento de Morfologia · ICB · UFMG\nCentro de Pesquisa e Desenvolvimento de Fármacos (CPDF)\nAvenida Antônio Carlos, 6627 - Bloco G3\nCEP: 31270-901 · Belo Horizonte, MG · Brasil",
        'footer_en' => "Research Group on Arboviral Diseases\nDepartment of Morphology · ICB · UFMG\nCenter for Drug Research and Development\nAvenida Antonio Carlos, 6627 - Section G3\nZip Code: 31270-901 · Belo Horizonte, MG · Brazil",
    ];
    return get_theme_mod('gpav_' . $key, $defaults[$key] ?? '');
}
function gpav_translation_url() {
    $other = gpav_lang() === 'pt' ? 'en' : 'pt';
    $id = (int) get_post_meta(get_queried_object_id(), '_gpav_translation', true);
    if ($id && get_post_status($id) === 'publish') return get_permalink($id);
    return home_url('/' . $other . '/');
}
add_filter('language_attributes', function ($attributes) {
    if (!is_admin()) return 'lang="' . (gpav_lang() === 'pt' ? 'pt-BR' : 'en') . '"';
    return $attributes;
});
add_filter('body_class', function ($classes) {
    if (get_post_meta(get_queried_object_id(), '_gpav_kind', true) === 'home') $classes[] = 'home';
    return $classes;
});
add_action('wp_enqueue_scripts', function () {
    $uri = get_template_directory_uri();
    wp_enqueue_style('gpav-font-inter', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', [], null);
    wp_enqueue_style('gpav-font-source', 'https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap', [], null);
    wp_enqueue_style('gpav-font-plex', 'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600;700&display=swap', [], null);
    wp_enqueue_style('gpav-design', $uri . '/assets/css/styles.css', [], '1.0.0');
    wp_enqueue_style('gpav-wordpress', $uri . '/assets/css/wordpress.css', ['gpav-design'], '1.0.0');
    wp_enqueue_script('gpav-site', $uri . '/assets/js/site.js', [], '1.0.0', true);
    $slides = [];
    foreach (['banner1.jpg','banner2.jpg','banner3.png','banner4.jpg'] as $i => $file) {
        $slides[] = ['src' => gpav_theme_image('banner_' . ($i + 1), 'banner/' . $file), 'alt' => gpav_t('Imagem de destaque ', 'Featured image ') . ($i + 1)];
    }
    wp_add_inline_script('gpav-site', 'window.GPAV_SLIDES = ' . wp_json_encode($slides) . ';', 'before');
});
add_action('wp_head', function () {
    echo '<link rel="icon" href="' . esc_url(get_template_directory_uri() . '/favicon.ico') . '">' . "\n";
    if (is_singular()) {
        echo '<link rel="alternate" hreflang="' . (gpav_lang() === 'pt' ? 'pt-BR' : 'en') . '" href="' . esc_url(get_permalink()) . '">' . "\n";
        $id = (int) get_post_meta(get_queried_object_id(), '_gpav_translation', true);
        if ($id && get_post_status($id) === 'publish') echo '<link rel="alternate" hreflang="' . (gpav_lang() === 'pt' ? 'en' : 'pt-BR') . '" href="' . esc_url(get_permalink($id)) . '">' . "\n";
    }
});
function gpav_fallback_menu() { require get_template_directory() . '/navigation-' . gpav_lang() . '.php'; }
add_action('customize_register', function ($customizer) {
    $customizer->add_section('gpav_branding', ['title' => 'GPAV: identidade e banner', 'priority' => 30]);
    foreach (['pt','en'] as $lang) {
        foreach (['title','subtitle','footer'] as $field) {
            $key = 'gpav_' . $field . '_' . $lang;
            $customizer->add_setting($key, ['sanitize_callback' => $field === 'footer' ? 'sanitize_textarea_field' : 'sanitize_text_field']);
            $customizer->add_control($key, ['section' => 'gpav_branding', 'label' => ucfirst($field) . ' (' . strtoupper($lang) . ')', 'type' => $field === 'footer' ? 'textarea' : 'text']);
        }
    }
    foreach (['logo','logo_icb','logo_ufmg','banner_1','banner_2','banner_3','banner_4'] as $field) {
        $key = 'gpav_' . $field;
        $customizer->add_setting($key, ['sanitize_callback' => 'esc_url_raw']);
        $customizer->add_control(new WP_Customize_Image_Control($customizer, $key, ['section' => 'gpav_branding', 'label' => ucfirst(str_replace('_',' ', $field))]));
    }
});
