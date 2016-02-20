<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="UTF-8" />
    <title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>
    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>
    <div class="container">
      <?php if (!is_front_page()) : ?>
        <header class="header">
          <?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
          <<?php echo $heading_tag; ?> class="site-title">
              <a href="<?php echo home_url( '/' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
          </<?php echo $heading_tag; ?>>
          <nav class="nav">
            <?php wp_nav_menu(array('container' => '')); ?>
            <form role="search" method="get" class="search-form" action="http://v2.joanie4jackie.com/">
              <input type="text" value="" name="s" id="search-input" placeholder="Search">
            </form>
          </nav>
        </header>
      <?php endif; ?>

      <div class="main group">
