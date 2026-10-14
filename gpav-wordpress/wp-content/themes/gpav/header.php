<?php defined('ABSPATH') || exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php echo esc_html(gpav_t('Pular para o conteúdo', 'Skip to content')); ?></a>
<?php require get_template_directory() . '/header-' . gpav_lang() . '.php'; ?>
<?php if (has_nav_menu('gpav_' . gpav_lang())) : ?>
<nav class="site-nav" aria-label="<?php echo esc_attr(gpav_t('Navegação principal','Primary navigation')); ?>"><div class="container">
<?php wp_nav_menu(['theme_location' => 'gpav_' . gpav_lang(), 'container' => false, 'menu_class' => 'nav', 'fallback_cb' => false]); ?>
</div></nav>
<?php else: gpav_fallback_menu(); endif; ?>
<main id="main" class="container">
