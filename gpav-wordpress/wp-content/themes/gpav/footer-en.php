<?php defined("ABSPATH") || exit; ?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-col footer-text"><?php foreach (explode("\n", gpav_setting("footer_" . gpav_lang())) as $i => $line) { echo "<div>" . ($i === 0 ? "<b>" : "") . esc_html($line) . ($i === 0 ? "</b>" : "") . "</div>"; } ?>
    </div>

    <div class="footer-col footer-logo">
      <a href="https://www.icb.ufmg.br/"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Institute of Biological Sciences – UFMG">
        <img src="<?php echo esc_url(gpav_theme_image('logo_icb', 'logo_icb.png')); ?>"
            alt="Institute of Biological Sciences (ICB) – UFMG"
            class="footer-img">
      </a>
    </div>

    <div class="footer-col footer-logo">
      <a href="https://ufmg.br/"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Federal University of Minas Gerais">
        <img src="<?php echo esc_url(gpav_theme_image('logo_ufmg', 'logo_ufmg.png')); ?>"
            alt="Federal University of Minas Gerais (UFMG)"
            class="footer-img">
      </a>
    </div>
    <div class="footer-privacy">
      © <?php echo esc_html(wp_date("Y")); ?> GPAV. All rights reserved. 
      <a href="<?php echo esc_url(home_url('/en/privacy/')); ?>">Privacy Policy</a>
    </div>
  </div>
</footer>
