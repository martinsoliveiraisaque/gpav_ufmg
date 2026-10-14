<?php
defined('ABSPATH') || exit;
get_header();
if (have_posts()) : while (have_posts()) : the_post();
    if (get_post_type() === 'gpav_news') : ?>
    <article class="news-post">
      <header class="news-header">
        <h1><?php the_title(); ?></h1>
        <p class="news-meta"><time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date('Y-m-d')); ?></time></p>
        <?php if (has_post_thumbnail()) the_post_thumbnail('large', ['class' => 'news-cover']); ?>
      </header>
      <div class="news-body"><?php the_content(); ?></div>
      <?php if (function_exists('gpav_news_attachments')) gpav_news_attachments(get_the_ID()); ?>
    </article>
    <?php else: the_content(); endif;
endwhile; else: ?>
<h1><?php echo esc_html(gpav_t('Página não encontrada','Page not found')); ?></h1>
<p><a href="<?php echo esc_url(home_url('/' . gpav_lang() . '/')); ?>"><?php echo esc_html(gpav_t('Voltar ao início','Back to home')); ?></a></p>
<?php endif; get_footer(); ?>
