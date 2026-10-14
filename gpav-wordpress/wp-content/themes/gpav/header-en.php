<?php defined("ABSPATH") || exit; ?>
<header class="site-header">
  <div class="container">
    <div class="header-top">
      <a class="brand" href="<?php echo esc_url(home_url('/en/')); ?>">
        <img class="logo" src="<?php echo esc_url(gpav_theme_image('logo', 'logo.jpg')); ?>" alt="Federal University of Minas Gerais logo">
        <div class="brand-text">
          <div class="brand-title"><?php echo esc_html(gpav_brand_title()); ?></div>
          <div class="brand-subtitle"><?php echo esc_html(gpav_setting("subtitle_" . gpav_lang())); ?></div>
        </div>
      </a>

      <div class="header-utils">
        <a class="lang"
          href="<?php echo esc_url(gpav_translation_url()); ?>"
          aria-label="Switch language to Portuguese"
          title="Português">
          <svg class="flag-icon" viewBox="0 0 720 504" aria-hidden="true" focusable="false">
            <!-- Green background -->
            <rect width="720" height="504" fill="#009C3B"/>
            <!-- Yellow diamond -->
            <polygon points="360,72 648,252 360,432 72,252" fill="#FFDF00"/>
            <!-- Blue globe -->
            <circle cx="360" cy="252" r="120" fill="#002776"/>
            <!-- White band (simplified) -->
            <path d="M240 252a120 120 0 0 1 240 0"
                  fill="none" stroke="#FFFFFF" stroke-width="20"/>
          </svg>
        </a>
      </div>
    </div>

    <section class="banner" aria-label="Featured images">
      <div class="banner-frame" id="banner-frame" role="button" tabindex="0"
          aria-label="Pause or play slideshow">
        <img id="banner-img" src="<?php echo esc_url(gpav_theme_image('banner_1', 'banner/banner1.jpg')); ?>" alt="" />
      </div>
    </section>
  </div>
</header>
