<?php defined("ABSPATH") || exit; ?>
<nav class="site-nav" aria-label="Primary navigation">
  <div class="container">
    <ul class="nav">
      <li><a href="<?php echo esc_url(home_url('/en/')); ?>">Home</a></li>
      <li class="has-dropdown">
        <a href="<?php echo esc_url(home_url('/en/about/')); ?>">About us</a>
        <ul class="dropdown">
          <li><a href="<?php echo esc_url(home_url('/en/team/')); ?>">Team</a></li>
          <li><a href="<?php echo esc_url(home_url('/en/collaborators/')); ?>">Collaborators</a></li>
        </ul>
      </li>
      <li class="has-submenu">
        <a href="<?php echo esc_url(home_url('/en/news/')); ?>" aria-haspopup="true" aria-expanded="false">News</a>
        <ul class="submenu">
          <li><a href="<?php echo esc_url(home_url('/en/news/')); ?>">All news</a></li>
          <li><a href="<?php echo esc_url(home_url('/en/instagram/')); ?>">Instagram</a></li>
        </ul>
      </li>
      <li><a href="<?php echo esc_url(home_url('/en/publications/')); ?>">Publications</a></li>
      <li><a href="<?php echo esc_url(home_url('/en/contact/')); ?>">Contact</a></li>
    </ul>
  </div>
</nav>
