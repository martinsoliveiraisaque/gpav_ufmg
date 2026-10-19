<?php
/**
 * GPAV Parcerias — página "Parcerias" do site.
 *  1) Shortcode [gpav_parcerias]: mostra o conteúdo de parcerias/parcerias.html.
 *  2) Carrega parcerias.css e parcerias.js somente nas páginas que usam o shortcode.
 *  3) Cria a página /pt/parcerias/ automaticamente na primeira visita (uma única vez).
 * Para editar textos, cores, planos ou perguntas do jogo, veja a pasta parcerias/ (e EDITING.md).
 */
defined('ABSPATH') || exit;
define('GPAV_PARCERIAS_VERSION', '1.0.0');

// 1) Shortcode: lê o HTML da pasta parcerias/ e troca {{IMG}} pelo endereço da pasta de imagens.
add_shortcode('gpav_parcerias', function () {
    $file = __DIR__ . '/parcerias/parcerias.html';
    if (!is_readable($file)) return '';
    return str_replace('{{IMG}}', esc_url(plugins_url('parcerias/img', __FILE__)), file_get_contents($file));
});

// 2) Estilos e script, apenas nas páginas que contêm o shortcode.
add_action('wp_enqueue_scripts', function () {
    $post = is_singular('page') ? get_post() : null;
    if (!$post || !has_shortcode($post->post_content, 'gpav_parcerias')) return;
    $base = plugins_url('parcerias/', __FILE__);
    // Fontes da página (Bricolage Grotesque e Public Sans), carregadas só aqui
    wp_enqueue_style('gpav-parcerias-fonts', 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600;700&display=swap', [], null);
    wp_enqueue_style('gpav-parcerias', $base . 'parcerias.css', ['gpav-design'], GPAV_PARCERIAS_VERSION);
    wp_enqueue_script('gpav-parcerias', $base . 'parcerias.js', [], GPAV_PARCERIAS_VERSION, true);
}, 20);

// 3) Cria a página "Parcerias" (filha da página inicial em português) quando ela ainda não existe.
//    Roda uma única vez; se a página for apagada depois, ela NÃO é recriada.
function gpav_parcerias_ensure_page() {
    if (get_option('gpav_parcerias_page_done')) return;
    $home = get_posts([
        'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 1, 'fields' => 'ids',
        'meta_query' => [['key' => '_gpav_kind', 'value' => 'home'], ['key' => '_gpav_lang', 'value' => 'pt']],
    ]);
    if (!$home) return;                                           // conteúdo do site ainda não importado: tenta de novo depois
    if (!add_option('gpav_parcerias_lock', time(), '', 'no')) return;   // evita criar duas páginas em acessos simultâneos
    if (!get_page_by_path('pt/parcerias')) {
        $id = wp_insert_post([
            'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Seja nosso parceiro', 'post_name' => 'parcerias',
            'post_parent' => (int) $home[0], 'post_content' => '[gpav_parcerias]', 'comment_status' => 'closed', 'ping_status' => 'closed',
        ], true);
        if (is_wp_error($id)) { delete_option('gpav_parcerias_lock'); return; }
        foreach (['lang' => 'pt', 'kind' => 'parcerias', 'source_route' => 'pt/parcerias/', 'imported' => 1] as $k => $v) {
            update_post_meta($id, '_gpav_' . $k, $v);
        }
    }
    update_option('gpav_parcerias_page_done', 1, false);
    delete_option('gpav_parcerias_lock');
}
add_action('init', 'gpav_parcerias_ensure_page', 30);
