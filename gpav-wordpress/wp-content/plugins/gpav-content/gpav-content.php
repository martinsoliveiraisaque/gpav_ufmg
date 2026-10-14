<?php
/**
 * Plugin Name: GPAV Content
 * Description: Bilingual GPAV pages, news, team records, Formspree and PubMed shortcodes.
 * Version: 1.0.0
 * Requires at least: 6.6
 * Requires PHP: 8.1
 */
defined('ABSPATH') || exit;
function gpav_content_lang() {
    $lang = get_post_meta(get_queried_object_id(), '_gpav_lang', true);
    return $lang === 'en' || get_query_var('gpav_lang') === 'en' ? 'en' : 'pt';
}
function gpav_content_t($pt, $en) { return gpav_content_lang() === 'pt' ? $pt : $en; }
function gpav_content_init() {
    register_post_type('gpav_news', [
        'labels' => ['name' => 'GPAV Notícias / News', 'singular_name' => 'Notícia / News article', 'add_new_item' => 'Adicionar notícia / Add news'],
        'public' => true, 'show_in_rest' => true, 'has_archive' => false, 'rewrite' => false,
        'menu_icon' => 'dashicons-megaphone', 'supports' => ['title','editor','thumbnail','revisions','excerpt'],
    ]);
    register_post_type('gpav_member', [
        'labels' => ['name' => 'GPAV Equipe / Team', 'singular_name' => 'Integrante / Member', 'add_new_item' => 'Adicionar integrante / Add member'],
        'public' => false, 'show_ui' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-groups',
        'supports' => ['title','thumbnail','revisions'], 'capability_type' => 'post',
    ]);
    add_rewrite_rule('^(pt|en)/news/([^/]+)/?$', 'index.php?post_type=gpav_news&name=$matches[2]&gpav_lang=$matches[1]', 'top');
}
add_action('init', 'gpav_content_init');
register_activation_hook(__FILE__, function () { gpav_content_init(); flush_rewrite_rules(); });
register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
add_filter('query_vars', function ($vars) { $vars[] = 'gpav_lang'; return $vars; });
add_filter('post_type_link', function ($url, $post) {
    if ($post->post_type !== 'gpav_news') return $url;
    $lang = get_post_meta($post->ID, '_gpav_lang', true) === 'en' ? 'en' : 'pt';
    return home_url('/' . $lang . '/news/' . $post->post_name . '/');
}, 10, 2);
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && $query->get('post_type') === 'gpav_news' && $query->get('gpav_lang')) {
        $query->set('meta_query', [['key' => '_gpav_lang', 'value' => $query->get('gpav_lang')]]);
    }
});
add_action('template_redirect', function () {
    if (is_home() && !is_feed() && !is_preview()) { wp_safe_redirect(home_url('/pt/'), 302); exit; }
});
// Preserve imported structured HTML; use the native classic editor without requiring another plugin.
add_filter('use_block_editor_for_post_type', function ($use, $type) {
    return in_array($type, ['page','gpav_news','gpav_member'], true) ? false : $use;
}, 10, 2);
add_filter('the_content', function ($content) {
    if (is_singular('page') && get_post_meta(get_the_ID(), '_gpav_imported', true)) {
        remove_filter('the_content', 'wpautop');
    }
    return $content;
}, 0);
function gpav_roles() { return ['Group Leader','Project Manager','Postdocs','PhD students','Master Students','Undergrad students','Former members']; }
add_action('add_meta_boxes', function () {
    foreach (['page','gpav_news'] as $type) add_meta_box('gpav-language', 'GPAV: idioma e tradução / Language and translation', 'gpav_language_box', $type, 'side');
    add_meta_box('gpav-member', 'GPAV: perfil / Profile', 'gpav_member_box', 'gpav_member', 'normal');
    add_meta_box('gpav-attachments', 'GPAV: anexos / Attachments', 'gpav_attachments_box', 'gpav_news', 'normal');
});
function gpav_language_box($post) {
    wp_nonce_field('gpav_metadata', 'gpav_nonce');
    $lang = get_post_meta($post->ID, '_gpav_lang', true) ?: 'pt';
    echo '<p><label for="gpav_lang">Idioma / Language</label><br><select name="gpav_lang" id="gpav_lang">';
    foreach (['pt'=>'Português','en'=>'English'] as $key=>$label) echo '<option value="'.esc_attr($key).'" '.selected($lang,$key,false).'>'.esc_html($label).'</option>';
    echo '</select></p><p><label for="gpav_translation">Tradução / Translation</label><br><select style="width:100%" name="gpav_translation" id="gpav_translation"><option value="0">—</option>';
    $translated = (int) get_post_meta($post->ID, '_gpav_translation', true);
    $candidates = get_posts(['post_type'=>$post->post_type,'post_status'=>['publish','draft','pending','future'],'numberposts'=>-1,'orderby'=>'title','order'=>'ASC','exclude'=>[$post->ID]]);
    foreach ($candidates as $candidate) {
        $candidate_lang = get_post_meta($candidate->ID, '_gpav_lang', true) ?: 'pt';
        echo '<option value="'.esc_attr($candidate->ID).'" '.selected($translated,$candidate->ID,false).'>'.esc_html(strtoupper($candidate_lang).' · '.$candidate->post_title).'</option>';
    }
    echo '</select></p><p class="description">Selecione a versão no outro idioma. / Select the other-language version.</p>';
}
function gpav_member_box($post) {
    wp_nonce_field('gpav_metadata', 'gpav_nonce');
    $role = get_post_meta($post->ID, '_gpav_role', true);
    echo '<p><label for="gpav_role">Categoria / Role</label><br><select id="gpav_role" name="gpav_role">';
    foreach (gpav_roles() as $r) echo '<option '.selected($role,$r,false).' value="'.esc_attr($r).'">'.esc_html($r).'</option>';
    echo '</select></p>';
    foreach (['title'=>'Título / Title','order'=>'Ordem na categoria / Order within category','lattes'=>'URL do CV Lattes'] as $field=>$label) {
        $type = $field==='order' ? 'number' : ($field==='lattes' ? 'url' : 'text');
        $value = get_post_meta($post->ID, '_gpav_'.$field, true);
        echo '<p><label for="gpav_'.$field.'">'.esc_html($label).'</label><br><input class="widefat" id="gpav_'.$field.'" name="gpav_'.$field.'" type="'.$type.'" '.($type==='number'?'min="1"':'').' value="'.esc_attr($value).'"></p>';
    }
    foreach (['pt'=>'Português','en'=>'English'] as $lang=>$label) echo '<p><label for="gpav_bio_'.$lang.'">Biografia / Biography ('.esc_html($label).')</label><textarea rows="6" class="widefat" id="gpav_bio_'.$lang.'" name="gpav_bio_'.$lang.'">'.esc_textarea(get_post_meta($post->ID,'_gpav_bio_'.$lang,true)).'</textarea></p>';
    echo '<p>Foto: use Imagem destacada. / Photo: use Featured image.</p>';
}
function gpav_attachments_box($post) {
    wp_nonce_field('gpav_metadata', 'gpav_nonce');
    $ids = get_post_meta($post->ID, '_gpav_attachments', true);
    echo '<p><label for="gpav_attachments">IDs da Biblioteca de Mídia, separados por vírgulas / Media Library IDs, comma-separated</label><br><input class="widefat" id="gpav_attachments" name="gpav_attachments" value="'.esc_attr(is_array($ids)?implode(',',$ids):'').'"></p><button type="button" class="button" id="gpav-select-attachments">Selecionar arquivos / Select files</button>';
}
add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook,['post.php','post-new.php'],true) || get_post_type() !== 'gpav_news') return;
    wp_enqueue_media();
    wp_enqueue_script('gpav-admin-media', plugins_url('admin-media.js',__FILE__), ['jquery'], '1.0.0', true);
});
add_action('save_post', function ($id) {
    if (!isset($_POST['gpav_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['gpav_nonce'])), 'gpav_metadata') || !current_user_can('edit_post',$id) || wp_is_post_autosave($id) || wp_is_post_revision($id)) return;
    $type = get_post_type($id);
    if (in_array($type,['page','gpav_news'],true)) {
        $lang = ($_POST['gpav_lang'] ?? '') === 'en' ? 'en' : 'pt';
        update_post_meta($id,'_gpav_lang',$lang);
        $other = absint($_POST['gpav_translation'] ?? 0);
        $old = (int) get_post_meta($id,'_gpav_translation',true);
        if ($old && (int)get_post_meta($old,'_gpav_translation',true)===$id) delete_post_meta($old,'_gpav_translation');
        delete_post_meta($id,'_gpav_translation');
        if ($other && $other !== $id && get_post_type($other)===$type && current_user_can('edit_post',$other) && get_post_meta($other,'_gpav_lang',true)!==$lang) {
            $previous = (int)get_post_meta($other,'_gpav_translation',true);
            if ($previous && $previous!==$id && (int)get_post_meta($previous,'_gpav_translation',true)===$other) delete_post_meta($previous,'_gpav_translation');
            update_post_meta($id,'_gpav_translation',$other);
            update_post_meta($other,'_gpav_translation',$id);
        }
    }
    if ($type==='gpav_member') {
        $role = sanitize_text_field(wp_unslash($_POST['gpav_role'] ?? ''));
        if (in_array($role,gpav_roles(),true)) update_post_meta($id,'_gpav_role',$role);
        foreach (['title','bio_pt','bio_en','lattes','order'] as $field) {
            $raw = wp_unslash($_POST['gpav_'.$field] ?? '');
            $value = str_starts_with($field,'bio_') ? sanitize_textarea_field($raw) : ($field==='lattes' ? esc_url_raw($raw) : ($field==='order' ? max(1,absint($raw)) : sanitize_text_field($raw)));
            update_post_meta($id,'_gpav_'.$field,$value);
        }
    }
    if ($type==='gpav_news') {
        $ids=array_filter(array_map('absint',explode(',',sanitize_text_field(wp_unslash($_POST['gpav_attachments'] ?? '')))),function($attachment){return get_post_type($attachment)==='attachment';});
        update_post_meta($id,'_gpav_attachments',array_values($ids));
    }
});
function gpav_news_attachments($id) {
    $attachments = get_post_meta($id,'_gpav_attachments',true);
    if (!is_array($attachments) || !$attachments) return;
    echo '<section class="news-attachments"><h2>'.esc_html(gpav_content_t('Anexos','Attachments')).'</h2><ul>';
    foreach ($attachments as $attachment) {
        $url=wp_get_attachment_url($attachment);
        if ($url) echo '<li><a href="'.esc_url($url).'">'.esc_html(get_the_title($attachment)).'</a></li>';
    }
    echo '</ul></section>';
}
function gpav_news_query($limit, $page=1) {
    return new WP_Query(['post_type'=>'gpav_news','post_status'=>'publish','posts_per_page'=>$limit,'paged'=>$page,'orderby'=>'date','order'=>'DESC','meta_query'=>[['key'=>'_gpav_lang','value'=>gpav_content_lang()]]]);
}
add_shortcode('gpav_latest_news',function(){
    $query=gpav_news_query(3); ob_start();
    echo '<section class="section-card" aria-labelledby="latest-news-title"><h2 id="latest-news-title" class="section-title">'.esc_html(gpav_content_t('Últimas notícias','Latest News')).'</h2><div class="news-cards">';
    foreach($query->posts as $post){
        echo '<article class="news-card"><a class="news-card-link" href="'.esc_url(get_permalink($post)).'">';
        if(has_post_thumbnail($post)) echo get_the_post_thumbnail($post,'large',['class'=>'news-card-img']);
        echo '<div class="news-card-body"><h3 class="news-card-title">'.esc_html(get_the_title($post)).'</h3><p class="news-card-meta"><time datetime="'.esc_attr(get_the_date(DATE_W3C,$post)).'">'.esc_html(get_the_date('Y-m-d',$post)).'</time></p></div></a></article>';
    }
    if(!$query->posts) echo '<p>'.esc_html(gpav_content_t('Em breve.','Coming soon.')).'</p>';
    echo '</div></section>';return ob_get_clean();
});
add_shortcode('gpav_news_list',function(){
    $page=max(1,absint($_GET['news_page'] ?? 1));$query=gpav_news_query(12,$page);ob_start();
    echo '<ul class="news-list">';
    foreach($query->posts as $post){
        echo '<li class="news-item"><h2 class="news-item-title"><a href="'.esc_url(get_permalink($post)).'">'.esc_html(get_the_title($post)).'</a></h2><p class="news-item-meta"><time datetime="'.esc_attr(get_the_date(DATE_W3C,$post)).'">'.esc_html(get_the_date('Y-m-d',$post)).'</time></p>';
        if(has_post_thumbnail($post)) echo '<a href="'.esc_url(get_permalink($post)).'">'.get_the_post_thumbnail($post,'large',['class'=>'news-thumb']).'</a>';
        echo '</li>';
    }
    echo '</ul>';
    if(!$query->posts) echo '<p>'.esc_html(gpav_content_t('Em breve.','Coming soon.')).'</p>';
    if($query->max_num_pages>1) echo '<nav class="gpav-pagination">'.paginate_links(['base'=>esc_url_raw(add_query_arg('news_page','%#%',get_permalink(get_queried_object_id()))),'format'=>'','current'=>$page,'total'=>$query->max_num_pages]).'</nav>';
    return ob_get_clean();
});
add_shortcode('gpav_team',function(){
    $members=get_posts(['post_type'=>'gpav_member','post_status'=>'publish','numberposts'=>-1,'meta_key'=>'_gpav_order','orderby'=>'meta_value_num','order'=>'ASC']);
    $lang=gpav_content_lang();ob_start();
    foreach(gpav_roles() as $role){
        $group=array_filter($members,function($m)use($role){return get_post_meta($m->ID,'_gpav_role',true)===$role;});
        if(!$group)continue;
        echo '<h2>'.esc_html($role).'</h2><div class="cards">';
        foreach($group as $member){
            echo '<article class="card">';
            if(has_post_thumbnail($member)) echo get_the_post_thumbnail($member,'large',['class'=>'card-photo','alt'=>$member->post_title]);
            echo '<div class="card-body"><h3 class="card-title">'.esc_html($member->post_title).'</h3>';
            $title=get_post_meta($member->ID,'_gpav_title',true);
            if($title)echo '<p class="card-subtitle">'.esc_html($title).'</p>';
            echo wpautop(esc_html(get_post_meta($member->ID,'_gpav_bio_'.$lang,true)));
            $lattes=get_post_meta($member->ID,'_gpav_lattes',true);
            if($lattes)echo '<p><a href="'.esc_url($lattes).'" aria-label="'.esc_attr('CV Lattes: '.$member->post_title).'">Lattes</a></p>';
            echo '</div></article>';
        }
        echo '</div>';
    }
    if(!$members)echo '<p>'.esc_html(gpav_content_t('Em breve.','Coming soon.')).'</p>';
    return ob_get_clean();
});
function gpav_pubmed_enqueue($recent=false) {
    $base=get_template_directory_uri().'/assets/js/';
    wp_enqueue_script('gpav-pubmed-config',$base.'pubmed-config.js',[],'1.0.0',true);
    $options=get_option('gpav_options',[]);
    $config=['TOOL'=>$options['pubmed_tool'] ?? 'gpav_ufmg_website','EMAIL'=>$options['pubmed_email'] ?? 'secchimribeiro@gmail.com','TERM'=>$options['pubmed_term'] ?? gpav_default_pubmed_term(),'DB'=>'pubmed','EUTILS'=>'https://eutils.ncbi.nlm.nih.gov/entrez/eutils'];
    wp_add_inline_script('gpav-pubmed-config','window.GPAV_PUBMED = '.wp_json_encode($config).';','after');
    wp_enqueue_script($recent?'gpav-recent-pubs':'gpav-publications',$base.($recent?'pubmed-recent-publications.js':'pubmed-publications.js'),['gpav-pubmed-config'],'1.0.0',true);
}
function gpav_default_pubmed_term(){return '"Costa VV"[1au] OR "Costa VV"[lastau] OR "Queiroz-Junior CM"[1au] OR "Queiroz-Junior CM"[lastau] OR "Queiroz Junior CM"[1au] OR "Queiroz Junior CM"[lastau]';}
add_shortcode('gpav_recent_publications',function(){
    gpav_pubmed_enqueue(true);
    return '<section class="section-card" aria-labelledby="recent-pubs-title"><h2 id="recent-pubs-title" class="section-title">'.esc_html(gpav_content_t('Publicações recentes','Recent publications')).'</h2><div id="recent-publications" class="pub-cards" data-lang="'.esc_attr(gpav_content_lang()).'"><p class="muted">'.esc_html(gpav_content_t('Carregando…','Loading…')).'</p></div><p class="muted"><a href="'.esc_url(home_url('/'.gpav_content_lang().'/publications/')).'">'.esc_html(gpav_content_t('Ver todas as publicações','View all publications')).'</a></p></section>';
});
add_shortcode('gpav_publications',function(){
    gpav_pubmed_enqueue(false);
    return '<div id="pubmed-publications" data-lang="'.esc_attr(gpav_content_lang()).'"><p class="muted">'.esc_html(gpav_content_t('Carregando…','Loading…')).'</p></div>';
});
add_shortcode('gpav_contact_form',function(){
    $form=file_get_contents(__DIR__.'/contact-'.gpav_content_lang().'.html');
    $options=get_option('gpav_options',[]);
    $endpoint=$options['formspree_url'] ?? 'https://formspree.io/f/mqeeekln';
    $form=str_replace('https://formspree.io/f/mqeeekln',esc_url($endpoint),$form);
    return str_replace('https://gpav.icb.ufmg.br/'.gpav_content_lang().'/privacy/',esc_url(home_url('/'.gpav_content_lang().'/privacy/')),$form);
});
add_action('admin_menu',function(){add_options_page('GPAV','GPAV','manage_options','gpav-settings','gpav_settings_page');});
add_action('admin_init',function(){
    register_setting('gpav_settings','gpav_options',['sanitize_callback'=>function($input){
        return ['formspree_url'=>esc_url_raw($input['formspree_url'] ?? ''),'pubmed_email'=>sanitize_email($input['pubmed_email'] ?? ''),'pubmed_tool'=>sanitize_text_field($input['pubmed_tool'] ?? ''),'pubmed_term'=>sanitize_textarea_field($input['pubmed_term'] ?? '')];
    }]);
});
function gpav_settings_page(){
    if(!current_user_can('manage_options'))return;
    $defaults=['formspree_url'=>'https://formspree.io/f/mqeeekln','pubmed_email'=>'secchimribeiro@gmail.com','pubmed_tool'=>'gpav_ufmg_website','pubmed_term'=>gpav_default_pubmed_term()];
    $options=wp_parse_args(get_option('gpav_options',[]),$defaults);
    echo '<div class="wrap"><h1>GPAV</h1><form action="options.php" method="post">';settings_fields('gpav_settings');
    foreach(['formspree_url'=>'Formspree URL','pubmed_email'=>'PubMed contact email','pubmed_tool'=>'PubMed tool name','pubmed_term'=>'PubMed query'] as $key=>$label){
        echo '<p><label for="'.$key.'">'.esc_html($label).'</label><br>';
        if($key==='pubmed_term')echo '<textarea class="large-text" rows="5" id="'.$key.'" name="gpav_options['.$key.']">'.esc_textarea($options[$key]).'</textarea>';
        else echo '<input class="regular-text" id="'.$key.'" name="gpav_options['.$key.']" value="'.esc_attr($options[$key]).'" type="'.($key==='pubmed_email'?'email':($key==='formspree_url'?'url':'text')).'">';
        echo '</p>';
    }
    submit_button();echo '</form><p>Aparência → Personalizar: identidade visual e banners. / Appearance → Customize: branding and banners.</p></div>';
}
