<?php

  add_action( 'after_setup_theme', 'j4j_setup' );

  function j4j_setup() {
    global $pagenow;

    if (!is_admin() && $pagenow != 'wp-login.php') {
      require_once('phpFlickr/phpFlickr.php');
      wp_deregister_script('jquery');
      wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js');
      wp_enqueue_script('fancybox', get_template_directory_uri() . '/fancybox/jquery.fancybox-1.3.4.pack.js');
      wp_enqueue_script('j4j', get_template_directory_uri() . '/j4j.js');
      wp_enqueue_script('fonts', 'http://fast.fonts.com/jsapi/381fad73-b725-412e-9be7-3270fe3de961.js');
      wp_enqueue_style('normalize', get_template_directory_uri() . '/normalize.css');
      wp_enqueue_style('fancybox', get_template_directory_uri() . '/fancybox/jquery.fancybox-1.3.4.css');
      wp_enqueue_style('j4j-style', get_stylesheet_uri());
    }

    // This theme uses post thumbnails
    add_theme_support( 'post-thumbnails' );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array('primary' => 'Primary Navigation'));

    /*********************
     * Custom Actions    *
     *********************/

    add_action('generate_rewrite_rules', 'setup_taxonomy_index_rules');
    add_action('edited_chainletter', 'flush_rewrite_rules');
    add_action('edited_costar', 'flush_rewrite_rules');
    add_action('admin_menu', 'j4j_admin_menu_tweaks');
    add_action('admin_head', 'j4j_admin_tweaks');
    add_action('admin_bar_menu', 'j4j_admin_bar_menu_tweaks', 999);
    add_action('template_redirect','j4j_auth');

    function j4j_auth() {
      global $wp_query;
      nocache_headers();

      if ( is_user_logged_in() || $wp_query->query_vars['pagename'] == 'participant-information-form')
        return;

      $user = isset($_SERVER["PHP_AUTH_USER"]) ? $_SERVER["PHP_AUTH_USER"] : '';
      $pwd  = isset($_SERVER["PHP_AUTH_PW"])   ? $_SERVER["PHP_AUTH_PW"]   : '';
      if ( !is_wp_error(wp_authenticate($user, $pwd)) ) {
        return;
      }

      header('WWW-Authenticate: Basic');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Authorization Required';
      die();
    }

    function setup_taxonomy_index_rules($wp_rewrite) {
      $chainletters = get_taxonomy_terms_by_custom_date('chainletter', array('slug'), 1);
      $costars = get_taxonomy_terms_by_custom_date('costar', array('slug'), 1);
      $taxonomy_index_rules = array(
                                    'chainletter-tapes/?$' => 'index.php?chainletter=' . $chainletters[0]->slug,
                                    'costar-tapes/?$' => 'index.php?costar=' . $costars[0]->slug
                                  );

      // Add the new rewrite rules into the top of the global rules array
      $wp_rewrite->rules = array_merge($taxonomy_index_rules, $wp_rewrite->rules);
    }

    function j4j_admin_tweaks() {
      ?>
      <style>
        /* Don't show Re-order submenu item on Posts, Videos and Events menus, and hide annoying info box thing on Re-order plugin page */
        #menu-posts li:last-child,
        #menu-posts-video li:last-child,
        #menu-posts-event li:last-child,
        #menu-posts-feedback li:last-child,
        #cpt_info_box {
          display: none;
        }
      </style>
      <script>
        jQuery(function($) {
          // hide "Slug" field on taxonomy add pages
          $('#addtag #tag-slug').parents('.form-field').hide();
        });
      </script>
      <?php
    }

    function j4j_admin_menu_tweaks() {
      remove_menu_page('link-manager.php'); // Links
      remove_menu_page('edit.php'); // Default Posts
      remove_menu_page('edit-comments.php'); // Comments
    }

    function j4j_admin_bar_menu_tweaks() {
      global $wp_admin_bar;
      $wp_admin_bar->remove_node( 'new-post' );
      $wp_admin_bar->remove_node( 'new-link' );
      $wp_admin_bar->remove_node( 'new-media' );
    }

    /*********************
     * Custom Post Types *
     *********************/

    register_post_type('video',
      array(
        'labels' => array(
          'name' => 'Videos',
          'singular_name' => 'Video',
          'edit_item' => 'Edit Video',
          'add_new_item' => 'New Video',
          'view_item' => 'View Video',
          'search_items' => 'Search Videos',
          'not_found' => 'No videos found',
          'not_found_in_trash' => 'No videos found in trash'
        ),
        'public' => true,
        'menu_position' => 4,
        'supports' => array('title', 'editor', 'thumbnail')
      )
    );

    register_post_type('archive',
      array(
        'labels' => array(
          'name' =>  'Archives',
          'singular_name' =>  'Archive',
          'edit_item' => 'Edit Archive',
          'add_new_item' => 'New Archive',
          'view_item' => 'View Archive',
          'search_items' => 'Search Archives',
          'not_found' => 'No archives found',
          'not_found_in_trash' => 'No archives found in trash'
        ),
        'public' => true,
        'menu_position' => 5
      )
    );

    register_post_type('event',
      array(
        'labels' => array(
          'name' =>  'Events',
          'singular_name' =>  'Event',
          'edit_item' => 'Edit Event',
          'add_new_item' => 'New Event',
          'view_item' => 'View Event',
          'search_items' => 'Search Events',
          'not_found' => 'No events found',
          'not_found_in_trash' => 'No events found in trash'
        ),
        'public' => true,
        'menu_position' => 6
      )
    );

    register_post_type('blurb',
      array(
        'labels' => array('name' =>  'Front Blurb' ),
        'public' => true,
        'show_in_menu' => false,
        'exclude_from_search' => true,
        'supports' => array('title','editor','thumbnail'),
      )
    );

    /*********************
     * Custom Taxomies   *
     *********************/

    register_taxonomy('chainletter', 'video', array(
      'hierarchical' => false,
      'labels' => array(
        'name' => 'Chainletters',
        'singular_name' => 'Chainletter',
        'search_items' =>   'Search Chainletters',
        'all_items' =>  'All Chainletters',
        'edit_item' =>  'Edit Chainletter',
        'update_item' =>  'Update Chainletter',
        'add_new_item' =>  'Add New Chainletter',
        'new_item_name' =>  'New Chainletter Name',
        'menu_name' =>  'Chainletters',
        'popular_items' => NULL // hide tag cloud on admin page
      ),
      'show_ui' => true,
      'show_admin_column' => true
    ));

    register_taxonomy('costar', 'video', array(
      'hierarchical' => false,
      'labels' => array(
        'name' => 'Co-Star Tapes',
        'singular_name' => 'Co-Star Tape',
        'search_items' =>   'Search Co-Star Tapes',
        'all_items' =>  'All Co-Star Tapes',
        'edit_item' =>  'Edit Co-Star Tape',
        'update_item' =>  'Update Co-Star Tape',
        'add_new_item' =>  'Add New Co-Star Tape',
        'new_item_name' =>  'New Co-Star Tape Name',
        'menu_name' =>  'Co-Star Tapes',
        'popular_items' => NULL
      ),
      'show_ui' => true,
      'show_admin_column' => true
    ));

    register_taxonomy('filmmaker', 'video',array(
      'hierarchical' => false,
      'labels' => array(
        'name' => 'Filmmaker',
        'singular_name' => 'Filmmakers',
        'search_items' =>   'Search Filmmakers',
        'popular_items' =>  'Popular Filmmakers',
        'all_items' =>  'All Filmmakers',
        'edit_item' =>  'Edit Filmmaker',
        'update_item' =>  'Update Filmmaker',
        'add_new_item' =>  'Add New Filmmaker',
        'new_item_name' =>  'New Filmmaker Name',
        'separate_items_with_commas' =>  'Separate filmmakers with commas',
        'add_or_remove_items' =>  'Add or remove filmmakers',
        'choose_from_most_used' =>  'Choose from the most used filmmakers',
        'menu_name' =>  'Filmmakers',
        'popular_items' => NULL
      ),
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'filmmaker' ),
    ));

    /*****************************
     * Template Helper Functions *
     *****************************/

    function the_filmmaker_names() {
      $filmmakers = wp_get_post_terms(get_the_ID(), 'filmmaker');

      if( count($filmmakers) == 1 ) {
        echo $filmmakers[0]->name;
      }

      if( count($filmmakers) > 1 ) {
        $filmmaker_string = $filmmakers[0]->name;
        for( $i = 1; $i < count($filmmakers); $i++  ) {
          if( $i > 0 && $i < (count($filmmakers) - 1)) {
            $filmmaker_string .= ', ';
          }
          if( $i == (count($filmmakers) - 1)) {
            $filmmaker_string .= ' and ';
          }
          $filmmaker_string .= $filmmakers[$i]->name;
        }
        echo $filmmaker_string;
      }
    }

    function the_linked_filmmaker_names() {
      $filmmakers = wp_get_post_terms(get_the_ID(), 'filmmaker');

      if( count($filmmakers) == 1 ) {
        echo '<a href="' . get_term_link($filmmakers[0]->slug, 'filmmaker') . '">' . $filmmakers[0]->name . '</a>';
      }

      if( count($filmmakers) > 1 ) {
        $filmmaker_string = '<a href="' . get_term_link($filmmakers[0]->slug, 'filmmaker') . '">' . $filmmakers[0]->name . '</a>';
        for( $i = 1; $i < count($filmmakers); $i++  ) {
          if( $i > 0 && $i < (count($filmmakers) - 1)) {
            $filmmaker_string .= ', ';
          }
          if( $i == (count($filmmakers) - 1)) {
            $filmmaker_string .= ' and ';
          }
          $filmmaker_string .= '<a href="' . get_term_link($filmmakers[$i]->slug, 'filmmaker') . '">' . $filmmakers[$i]->name . '</a>';
        }
        echo $filmmaker_string;
      }
    }

    function the_filmmaker_links() {
      $filmmakers = is_tax('filmmaker') ? array(get_queried_object()) : wp_get_post_terms(get_the_ID(), 'filmmaker');
      foreach( $filmmakers as $filmmaker ) {
        $acg_id = $filmmaker->taxonomy._.$filmmaker->term_id;
        $filmmaker_website = get_field('filmmaker_website', $acg_id);
        if( !empty($filmmaker_website) ) {
          echo '<li><a href="' . $filmmaker_website . '">' . (count($filmmakers) > 1 ? $filmmaker->name : 'Filmmaker') . '\'s Website &raquo;</a></li>';
        }
      }
    }

    function the_chainletter_meta() {
      $term = get_queried_object();
      $acg_id = $term->taxonomy._.$term->term_id;
      $fulfilled_date = DateTime::createFromFormat('Ymd', get_field('chainletter_fulfilled_date', $acg_id));
      $compiled_by = get_field('chainletter_compiled_by', $acg_id);
      if ( !empty($fulfilled_date) || !empty($compiled_by) )
        echo '<p>';
      if ( !empty($fulfilled_date) )
        echo 'Compiled in ' . $fulfilled_date->format('F Y');
      if ( !empty($fulfilled_date) && !empty($compiled_by) )
        echo ($term->taxonomy == 'costar' ? ' | ' : ' ') ;
      if ( !empty($compiled_by) )
         echo ($term->taxonomy == 'costar' ? ' Curated by ' : ' by ')  . $compiled_by;
      if ( !empty($fulfilled_date) || !empty($compiled_by) )
        echo '</p>';
    }

    function the_booklet_link() {
      $term = get_queried_object();
      $acg_id = $term->taxonomy._.$term->term_id;
      $setLink = get_field('chainletter_booklet', $acg_id);

      if(!empty($setLink)) {
      $pattern = '#sets/([0-9]+)#';
      preg_match($pattern, $setLink, $setId);

      $f = new phpFlickr('4c32dbf63d7deabd1ec94d208d0961c0');
      // $f->enableCache('fs', get_template_directory_uri() . '/phpFlickr/cache');
      $result = $f->photosets_getPhotos($setId[1]);

      foreach ($result['photoset']['photo'] as $i => $photo) {
        echo '<a href="' . $f->buildPhotoURL($photo, "large") . '" id="booklet-image-' . $i . '" class="booklet-image" rel="booklet-gallery" title="' . $term->name . '" data-flickr-link="' . $f->buildPhotoURL($photo, "large") . '"/>View booklet &raquo;</a>';
        }
      }
    }

    function the_vimeo_link($vimeo_url, $link_text) {
      $vimeo_embed = get_vimeo_embed($vimeo_url);
      echo '<a href="' . $vimeo_url . '" class="vimeo-link" data-height="' . $vimeo_embed->height . '" data-width="' . $vimeo_embed->width . '" target="_blank">' . $link_text . ' &raquo;</a>';
    }

    function the_video_thumbnail() {
      $video_url = get_field('clip_link');
      if ($video_url) {
        echo '<a href="' . $video_url . '" class="vimeo-link" target="_blank">';
      }
      if (has_post_thumbnail()) {
        the_post_thumbnail('large');
      } else {
        $vimeo_embed = get_vimeo_embed($video_url);
        if ($vimeo_embed) {
          echo '<img src="' . $vimeo_embed->thumbnail_url . '" class="vimeo-thumbnail">';
        } else {
          echo '<img src="/images/ComingSoon.jpg" class="vimeo-thumbnail">';
        }
      }
      if ($video_url) {
        echo '</a>';
      }
    }

    function the_slow_video_thumbnail() {
      $video_url = get_field('clip_link');
      $vimeo_embed = get_vimeo_embed($video_url);

      if ($vimeo_embed) {
        echo '<a href="' . $video_url . '" class="vimeo-link" data-height="' . $vimeo_embed->height . '" data-width="' . $vimeo_embed->width . '" target="_blank">';
        if (has_post_thumbnail()) {
          the_post_thumbnail('large');
        } elseif ($vimeo_embed) {
          echo '<img src="' . $vimeo_embed->thumbnail_url . '" class="vimeo-thumbnail" width="320">';
        } else {
          echo '<img src="/images/ComingSoon.jpg" class="vimeo-thumbnail" width="320">';
        }
        echo '</a>';
      } elseif (has_post_thumbnail()) {
        the_post_thumbnail('large');
      }
    }

    function the_video_chainletter_links() {
      global $post;
      $tapes = wp_get_object_terms($post->ID, array('chainletter', 'costar'));
      $html = 'Appears on ';

      foreach ($tapes as $i => $tape) {
        $html .= '<a href="/' . $tape->taxonomy . '/' . $tape->slug . '/">' . $tape->name . '</a>';
        if ($i < count($tapes) - 1) {
          $html .= ', ';
        }
      }

      echo $html;
    }

    function the_photoset($setLink) {
      if(!empty($setLink)) {
        $pattern = '#sets/([0-9]+)#';
        preg_match($pattern, $setLink, $setId);

        $f = new phpFlickr('4c32dbf63d7deabd1ec94d208d0961c0');
        // $f->enableCache('fs', get_template_directory_uri() . '/phpFlickr/cache');
        $result = $f->photosets_getPhotos($setId[1]);

        echo '<div class="entry-photoset">';
        foreach ($result['photoset']['photo'] as $i => $photo) {
          $photoInfo = $f->photos_getInfo($photo['id']);
          $photoSizes = $f->photos_getSizes($photo['id']);
          echo '<a href="' . $f->buildPhotoURL($photo, "large") . '" class="archive-image" id="archive-image-' . $i . '" rel="archive-gallery" title="' . wp_specialchars($photoInfo['photo']['description']) . '" data-flickr-link="' . $f->buildPhotoURL($photo, "large") . '"/><img src="' . $f->buildPhotoURL($photo, "small") . '" width="' . $photoSizes[3]['width'] . '" height="' . $photoSizes[3]['height'] . '"></a>';
        }
        echo '</div>';
      }
    }

    function get_taxonomy_terms_by_custom_date($taxonomy, $term_fields, $limit = false) {
      global $wpdb;
      $term_fields = is_array($term_fields) ? implode(', ', $term_fields) : 'wp_terms.*';
      $date_field = 'chainletter_fulfilled_date';
      $orderby = 'date';

      $query = "SELECT " . $term_fields . ", option_value AS date
                FROM wp_term_taxonomy
                JOIN wp_terms ON wp_terms.term_id = wp_term_taxonomy.term_id
                JOIN wp_options ON wp_options.option_name = CONCAT_WS('_', '" . $taxonomy . "', wp_term_taxonomy.term_id, '" . $date_field ."')
                WHERE wp_term_taxonomy.taxonomy = '" . $taxonomy . "'
                ORDER BY " . $orderby .
                ($limit ? (" LIMIT " . $limit) : "");
      return $wpdb->get_results($query);
    }

    function get_taxonomy_sidebar() {
      $taxonomy = get_queried_object()->taxonomy;
      $terms = get_taxonomy_terms_by_custom_date($taxonomy, array('name', 'slug'));
      $current_term_slug = get_query_var('term') ? get_query_var('term'): $terms[0]->slug;
      $current_term = get_term_by('slug', $current_term_slug, $taxonomy);

      // print out the taxonomy sidebar
      echo('<ul>');
        foreach ($terms as $term) {
          echo('<li'  . ($term->slug == $current_term->slug ? ' class="current-menu-item">' : '>') . '<a href="/' . $taxonomy . '/' . $term->slug . '/">' . substr($term->date, 0, 4) . ' ' . $term->name . '</a></li>');
        }
      echo('</ul>');

      return $current_term;
    }

    function get_post_type_sidebar() {
      global $post;
      if ($post->post_type == 'page') {
        $post_type = $post->post_name == 'events' ? 'event' : 'archive';
      } else {
        $post_type = $post->post_type;
      }
      $sidebar_posts = get_posts(array('order'=> 'ASC', 'orderby' => 'title', 'post_type' => $post_type, 'numberposts' => -1));

      echo('<ul>');
      foreach($sidebar_posts as $sidebar_post) {
        $year = $post_type == 'event' ? DateTime::createFromFormat('Ymd', get_field('event_start_date', $sidebar_post->ID))->format('Y ') : null;
        echo('<li' . ($sidebar_post->ID == $post->ID ? ' class="current-menu-item">' : '>') . '<a href="' . get_permalink($sidebar_post->ID) . '">' . $year . $sidebar_post->post_title . '</a></li>');
      }
      echo('</ul>');
    }

    function the_event_date() {
      $start_date = DateTime::createFromFormat('Ymd', get_field('event_start_date'));
      $end_date = DateTime::createFromFormat('Ymd', get_field('event_end_date'));
      echo $start_date->format('F d, Y');
      echo $end_date ? ' - ' . $end_date->format('F d, Y') : '';
    }

    // Curl helper function
    function curl_get($url) {
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_TIMEOUT, 30);
      $return = curl_exec($curl);
      curl_close($curl);
      return $return;
    }

    function get_vimeo_embed() {
      global $post;
      if ($post->vimeo_embed) return $post->vimeo_embed;

      $video_url = get_field('clip_link');
      if($video_url != '') {
        $oembed_endpoint = 'http://vimeo.com/api/oembed';
        $xml_url = $oembed_endpoint . '.xml?url=' . rawurlencode($video_url);
        $post->vimeo_embed = simplexml_load_string(curl_get($xml_url));
        return $post->vimeo_embed;
      }
      else return false;
    }

    function the_now_link($acg_id) {
      $year = substr(get_field('chainletter_fulfilled_date', $acg_id), 0, 4);
      $title_string = str_replace(' ', '_', get_queried_object()->name);
      // remove non-alphanumeric characters to match TOC plugin generated anchor tags
      echo preg_replace('/[^a-zA-Z0-9 \-_]*/', '', $year . _ . $title_string);
    }
  }
?>