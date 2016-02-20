      </div><!-- .main -->

      <footer class="footer">
          <div class="site-title">
            <a href="<?php echo home_url( '/' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
          </div>
          <nav class="nav">
            <?php wp_nav_menu(array('container' => '')); ?>
          </nav>
      </footer>

    </div><!-- #wrapper -->

    <?php wp_footer(); ?>
  </body>
</html>
