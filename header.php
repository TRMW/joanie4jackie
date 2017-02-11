<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <?php if (!is_user_logged_in()) : ?>
      <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-91018195-1', 'auto');
        ga('send', 'pageview');
      </script>
    <?php endif; ?>
  </head>

  <body <?php body_class(); ?>>
    <div class="container">
      <header class="header">
        <?php if (is_front_page()) : ?>
          <h1 class="front-title">
            <?php bloginfo('name'); ?>
          </h1>
        <?php else : ?>
          <div class="site-title">
            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
              <?php bloginfo('name'); ?>
            </a>
          </div>
        <?php endif; ?>
        <nav class="nav">
          <?php wp_nav_menu(array('container' => '')); ?>
          <form role="search" method="get" class="search-form"  action="http://v2.joanie4jackie.com/">
            <input type="text" value="" name="s" id="search-input" placeholder="Search">
          </form>
        </nav>
        <div class="mobile-menu-toggle">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 24">
            <path fill="white" fill-rule="evenodd" clip-rule="evenodd" d="M0 10h32v4H0v-4zM0 20h32v4H0v-4zM0 0h32v4H0V0z"></path>
          </svg>
        </div>
      </header>

      <div class="main group">
