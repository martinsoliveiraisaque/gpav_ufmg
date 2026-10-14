<?php defined("ABSPATH") || exit; ?>
<nav class="site-nav" aria-label="Primary navigation">
  <div class="container">
    <ul class="nav">
      <li><a href="<?php echo esc_url(home_url('/pt/')); ?>">Início</a></li>
      <li class="has-dropdown">
        <a href="<?php echo esc_url(home_url('/pt/about/')); ?>">Sobre o GPAV</a>
        <ul class="dropdown">
          <li><a href="<?php echo esc_url(home_url('/pt/team/')); ?>">Equipe</a></li>
          <li><a href="<?php echo esc_url(home_url('/pt/collaborators/')); ?>">Colaboradores</a></li>
        </ul>
      </li>
      <li class="has-submenu">
        <a href="<?php echo esc_url(home_url('/pt/news/')); ?>" aria-haspopup="true" aria-expanded="false">Notícias</a>
        <ul class="submenu">
          <li><a href="<?php echo esc_url(home_url('/pt/news/')); ?>">Todas as notícias</a></li>
          <li><a href="<?php echo esc_url(home_url('/pt/instagram/')); ?>">Instagram</a></li>
        </ul>
      </li>
      <li><a href="<?php echo esc_url(home_url('/pt/publications/')); ?>">Publicações</a></li>
      <li><a href="<?php echo esc_url(home_url('/pt/contact/')); ?>">Contato</a></li>
    </ul>
  </div>
</nav>
