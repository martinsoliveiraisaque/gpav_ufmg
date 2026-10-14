<?php defined("ABSPATH") || exit; ?>
<header class="site-header">
  <div class="container">
    <div class="header-top">
      <a class="brand" href="<?php echo esc_url(home_url('/pt/')); ?>">
        <img class="logo" src="<?php echo esc_url(gpav_theme_image('logo', 'logo.jpg')); ?>" alt="GPAV logo">
        <div class="brand-text">
          <div class="brand-title"><?php echo esc_html(gpav_brand_title()); ?></div>
          <div class="brand-subtitle"><?php echo esc_html(gpav_setting("subtitle_" . gpav_lang())); ?></div>
        </div>
      </a>

      <div class="header-utils">
        <a class="lang"
          href="<?php echo esc_url(gpav_translation_url()); ?>"
          aria-label="Switch language to English"
          title="English">
          <svg class="flag-icon" viewBox="0 0 7410 3900" aria-hidden="true" focusable="false">
            <!-- Red background -->
            <rect width="7410" height="3900" fill="#B22234"/>

            <!-- White stripes -->
            <g fill="#FFFFFF">
              <rect y="300" width="7410" height="300"/>
              <rect y="900" width="7410" height="300"/>
              <rect y="1500" width="7410" height="300"/>
              <rect y="2100" width="7410" height="300"/>
              <rect y="2700" width="7410" height="300"/>
              <rect y="3300" width="7410" height="300"/>
            </g>

            <!-- Blue canton -->
            <rect width="2964" height="2100" fill="#3C3B6E"/>

            <!-- Stars (simplified grid, readable at small size) -->
            <g fill="#FFFFFF">
              
              <circle cx="247" cy="210" r="70"/>
              <circle cx="741" cy="210" r="70"/>
              <circle cx="1235" cy="210" r="70"/>
              <circle cx="1729" cy="210" r="70"/>
              <circle cx="2223" cy="210" r="70"/>

              <circle cx="494" cy="420" r="70"/>
              <circle cx="988" cy="420" r="70"/>
              <circle cx="1482" cy="420" r="70"/>
              <circle cx="1976" cy="420" r="70"/>
              <circle cx="2470" cy="420" r="70"/>
            </g>
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
