<?php

/****************************
 * Initialization
 ****************************/

add_action('init', 'j4j_registrations');
add_action('init', 'add_j4j_rewrite_rules');
add_action('after_setup_theme', 'j4j_setup');
add_action('after_setup_theme', 'j4j_frontend_setup');
add_action('generate_rewrite_rules', 'setup_taxonomy_index_rules');
add_filter('nav_menu_css_class', 'set_active_menu_class', 10, 2);

function j4j_setup() {
  // This theme uses post thumbnails
  add_theme_support('post-thumbnails');
  set_post_thumbnail_size(320, 240, true);

  // Let WordPress handle <title> tag
  add_theme_support('title-tag');

  // This theme uses wp_nav_menu() in one location.
  register_nav_menus(array('primary' => 'Primary Navigation'));
}

function j4j_frontend_setup() {
  if(!is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
    // last two arguments are version number and $in_footer
    // https://developer.wordpress.org/themes/basics/including-css-javascript/#scripts
    wp_enqueue_script('j4j', get_template_directory_uri() . '/j4j.js', array('jquery'), null, true);
    wp_enqueue_script('fonts', 'http://fast.fonts.com/jsapi/381fad73-b725-412e-9be7-3270fe3de961.js');
    wp_enqueue_style('normalize', get_template_directory_uri() . '/normalize.css');
    wp_enqueue_style('j4j-style', get_stylesheet_uri());
  }
}

function add_j4j_rewrite_rules() {
  add_rewrite_rule(
    '^now/?$',
    'index.php?post_type=now_response&tag=supporter',
    'top'
  );
  add_rewrite_rule(
    '^now/chainletter/([^/]*)/?',
    'index.php?post_type=now_response&chainletter=$matches[1]',
    'top'
  );
  add_rewrite_rule(
    '^now/co-star/([^/]*)/?',
    'index.php?post_type=now_response&costar=$matches[1]',
    'top'
  );
  add_rewrite_rule(
    '^now/supporters/?$',
    'index.php?post_type=now_response&tag=supporter',
    'top'
  );
  add_rewrite_rule(
    '^documents/?$',
    'index.php?archive=posters',
    'top'
  );}

function j4j_registrations() {
  // Custom Post Types

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
      'supports' => array('title','editor', 'thumbnail'),
      'taxonomies' => array('post_tag')
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
      'menu_position' => 5,
      'rewrite' => array('slug' => 'documents'),
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
      'menu_position' => 6,
      'supports' => array('title','editor'),
      'taxonomies' => array('post_tag')
    )
  );

  // Note: `has_archive` needs to be truthy for taxonomy (chainletter/costar)
  // archives to exist, but can't be "Now" or it will clobber /now
  register_post_type('now_response',
    array(
      'labels' => array(
        'name' =>  'Now',
        'singular_name' =>  'Now',
        'edit_item' => 'Edit Entry',
        'add_new_item' => 'New Entry'
      ),
      'public' => true,
      'menu_position' => 7,
      'supports' => array('title','editor'),
      'taxonomies' => array('post_tag'),
      'rewrite' => array('slug' => 'now'),
      'has_archive' => 'now-archive'
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

  // Custom Taxomies

  register_taxonomy('chainletter', array('video', 'now_response'), array(
    'hierarchical' => true,
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
    'show_admin_column' => true
  ));

  register_taxonomy('costar', array('video', 'now_response'), array(
    'hierarchical' => true,
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
    'rewrite' => array('slug' => 'co-star'),
    'show_admin_column' => true
  ));

  register_taxonomy('filmmaker', array('video', 'now_response'), array(
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
    'show_admin_column' => true
  ));
}

function set_active_menu_class($classes , $item){
  $is_current = false;

  switch ($item->url) {
    case '/chainletter-tapes/':
      $is_current = is_tax('chainletter') && !is_post_type_archive('now_response');
      break;
    case '/co-star-tapes/':
      $is_current = is_tax('costar') && !is_post_type_archive('now_response');
      break;
    case '/documents/':
      $is_current = get_post_type() == 'archive';
      break;
    case '/events/':
      $is_current = get_post_type() == 'event';
      break;
    case '/now/':
      global $wp_query;
      $is_current = is_post_type_archive('now_response') || $wp_query->query_vars['post_type'] == 'now_response';
      break;
  }

  if ($is_current) {
    $classes[] = 'current-menu-item';
  }

  return $classes;
}

// Link /chainletter and /costar to the first tape in those categories
function setup_taxonomy_index_rules($wp_rewrite) {
  $chainletters = get_taxonomy_terms_by_custom_date('chainletter', array('slug'), 1);
  $costars = get_taxonomy_terms_by_custom_date('costar', array('slug'), 1);

  $events = get_posts(array(
    'order' => 'ASC',
    'meta_key' => 'event_start_date',
    'orderby' => 'meta_value_num',
    'post_type' => 'event',
    'numberposts' => 1
  ));

  $taxonomy_index_rules = array(
    'chainletter-tapes/?$' => 'index.php?chainletter=' . $chainletters[0]->slug,
    'co-star-tapes/?$' => 'index.php?costar=' . $costars[0]->slug,
    'events/?$' => 'index.php?event=' . $events[0]->post_name
  );

  // Add the new rewrite rules into the top of the global rules array
  $wp_rewrite->rules = array_merge($taxonomy_index_rules, $wp_rewrite->rules);
}


/****************************
 * Saving and editing
 ****************************/

add_action('edited_chainletter', 'flush_rewrite_rules');
add_action('edited_costar', 'flush_rewrite_rules');
add_action('edited_term', 'delete_taxonomy_transients', 10, 3);
add_action('edited_filmmaker', 'delete_filmmaker_transients', 10, 2);
add_action('save_post_video', 'delete_video_transients', 10 ,3);
add_action('save_post_now_response', 'delete_now_response_transients', 10, 3);
add_action('save_post_archive', 'set_post_attachments', 10, 3);
add_action('save_post_event', 'set_post_attachments', 10, 3);

// A lot of this is copied from `get_post_galleries`
// https://developer.wordpress.org/reference/functions/get_post_galleries/
function get_term_galleries($term_id) {
  $term_description = get_term($term_id)->description;

  if (!$term_description || !has_shortcode($term_description, 'gallery'))
    return array();

  $galleries = array();
  if (preg_match_all('/' . get_shortcode_regex() . '/s', $term_description, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $shortcode) {
      if ('gallery' === $shortcode[2]) {
        $srcs = array();

        $gallery = do_shortcode_tag($shortcode);
        preg_match_all('#src=([\'"])(.+?)\1#is', $gallery, $src, PREG_SET_ORDER);
        if (!empty($src)) {
          foreach ($src as $s)
            $srcs[] = $s[2];
        }

        $data = shortcode_parse_atts($shortcode[3]);
        $data['src'] = array_values(array_unique($srcs));
        $galleries[] = $data;
      }
    }
  }
  return $galleries;
}

function delete_taxonomy_transients($term_id, $tt_id, $taxonomy) {
  if ($taxonomy === 'filmmaker') {
    delete_transient('filmmaker_links_for_filmmaker_page_' . $term_id);
    foreach (get_objects_in_term($term_id, $taxonomy) as $term_object) {
      if ($term_object->type === 'video') {
        delete_transient('filmmaker_links_for_video_' . $term_object->ID);
      }
    }
  } else { // chainletters and costars
    delete_transient('taxonomy_sidebar_terms_' . $taxonomy);
    foreach (get_terms($taxonomy) as $term) {
      delete_transient('taxonomy_sidebar_' . $taxonomy . '_' . $term->slug);
      delete_transient('now_sidebar_items_' . $taxonomy . '_' . $term->slug);
    }
  }

  if (get_term_galleries($term_id)) {
    $gallery_ids = array();
    foreach ($galleries as $gallery) {
      // $gallery['ids'] is a string like '123, 124, 125'
      $gallery_ids = array_merge($gallery_ids, explode(',', $gallery['ids']));
    }
    $gallery_ids_array = array_map('intval', $gallery_ids);

    foreach ($gallery_ids_array as $attachment_id) {
      update_post_meta($attachment_id, 'attachment_parent_term', $term_id);
    }
  }
}

function delete_video_transients($post_id, $post, $update) {
  delete_transient('filmmaker_links_for_video_' . $post_id);
  delete_transient('video_chainletter_links_' . $post->slug);

  if ($video_filmmakers = get_the_terms($post, 'filmmaker')) {
    foreach ($video_filmmakers as $filmmaker) {
      delete_transient('now_video_list_' . $filmmaker->slug);
    }
  }
}

function delete_filmmaker_transients($term_id, $tt_id) {
  $filmmaker_videos = get_posts(array(
    'post_type' => 'video',
    'numberposts' => -1,
    'tax_query' => array(array(
      'taxonomy' => 'filmmaker',
      'field' => 'term_id',
      'terms' => $term_id
  ))));

  foreach ($filmmaker_videos as $video) {
    delete_transient('filmmaker_links_for_video_' . $video->ID);
  }
}

function delete_now_response_transients($post_ID, $post, $update) {
  foreach (get_the_terms($post, 'chainletter') as $term) {
    delete_transient('now_sidebar_items_chainletter_' . $term->slug);
  }

  foreach (get_the_terms($post, 'costar') as $term) {
    delete_transient('now_sidebar_items_costar_' . $term->slug);
  }

  $filmmakers = get_the_terms($post_ID, 'filmmaker');
  $filmmaker = $filmmakers[0]; // assume only one filmmamer linked to each interview

  $filmmaker_videos = get_posts(array(
    'post_type' => 'video',
    'numberposts' => -1,
    'tax_query' => array(array(
      'taxonomy' => 'filmmaker',
      'field' => 'term_id',
      'terms' => $filmmaker->term_id
  ))));

  foreach ($filmmaker_videos as $video) {
    delete_transient('filmmaker_links_for_video_' . $video->ID);
  }
}

function set_post_attachments($post_ID, $post, $update) {
  if ($galleries = get_post_galleries($post, false)) {
    $gallery_ids = array();
    foreach ($galleries as $gallery) {
      // $gallery['ids'] is a string like '123, 124, 125'
      array_push($gallery_ids, $gallery['ids']);
    }
    // merge all id strings into one mega string
    $gallery_ids_string = implode(',', $gallery_ids);

    // copied from wp_media_attach_action source
    global $wpdb;
    $wpdb->query($wpdb->prepare("UPDATE $wpdb->posts SET post_parent = %d WHERE post_type = 'attachment' AND ID IN ($gallery_ids_string)", $post_ID));
  }
}


/****************************
 * Query Customization
 ****************************/

add_action('pre_get_posts', 'j4j_pre_get_posts');
add_action('the_posts', 'j4j_the_posts', 10, 2);

function j4j_pre_get_posts($query) {
  if ($query->is_post_type_archive('now_response')) {
    // alphabetical!
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
  } else if ($query->is_tax && $query->get('filmmaker')) {
    // Note that we're not doing `$query->is_tax('filmmaker')` because it triggers a db hint
    // We only want videos, not now_responses
    $query->set('post_type', 'video');
  }
}

function j4j_the_posts($posts, $query) {
  if ($query->is_main_query() && $query->is_tax && ($query->get('chainletter') || $query->get('costar'))) {
    // For tape pages we want to filter out the Now responses so we can use them later without hitting
    // the db, and also so we can assume the loop is only getting video posts.
    $video_posts = array();
    $now_posts = array();
    foreach ($posts as $post) {
      if ($post->post_type === 'now_response') {
        $now_posts[get_the_terms($post->ID, 'filmmaker')[0]->term_id] = $post;
      } else {
        $video_posts[] = $post;
      }
    }
    // stash this for later use in `the_filmmaker_links()`
    $query->now_posts = $now_posts;
    return $video_posts;
  } else {
    return $posts;
  }
}


/****************************
 * Search Customization
 ****************************/

add_filter('relevanssi_hits_filter', 'remove_video_thumbnails');

function remove_video_thumbnails($hits) {
  $hits[0] = array_filter($hits[0], function($hit) {
    if ($hit->post_type !== 'attachment') {
      return true;
    }
    if ($hit->post_parent) {
      // return true (include in results) if no parent or parent type isn't video
      $parent = get_post($hit->post_parent);
      return !$parent || $parent->post_type !== 'video';
    } else {
      return get_post_meta($hit->ID, 'attachment_parent_term', true) || false;
    }
  });
  return $hits;
}


/****************************
 * Admin Tweaks
 ****************************/

add_action('admin_menu', 'j4j_admin_menu_tweaks');
add_action('admin_bar_menu', 'j4j_admin_bar_menu_tweaks', 999);

function j4j_admin_menu_tweaks() {
  remove_menu_page('link-manager.php'); // Links
  remove_menu_page('edit.php'); // Default Posts
  remove_menu_page('edit-comments.php'); // Comments
}

function j4j_admin_bar_menu_tweaks() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_node('new-post');
  $wp_admin_bar->remove_node('new-link');
}


/****************************
 * Template Helper Functions
 ****************************/

function get_term_path($term, $taxonomy) {
  global $wp_rewrite;
  // Returns the rewrite pattern i.e. "/co-star/%costar%" for costars
  // Note that this is a global object, so it doesn't hit the db
  $rewrite_pattern = $wp_rewrite->get_extra_permastruct($taxonomy);
  return str_replace("%$taxonomy%", $term->slug, $rewrite_pattern);
}

// Using this instead of `get_term_link` saves us a database query,
// since `get_term_link` immediately calls `get_term_by` and we don't.
function fast_get_term_link($term, $taxonomy) {
  $term_path = get_term_path($term, $taxonomy);
  return home_url(user_trailingslashit($term_path, 'category'));
}

function get_the_now_link($term, $taxonomy) {
  $now_path = '/now/' . get_term_path($term, $taxonomy);
  return home_url(user_trailingslashit($now_path, 'category'));
}

function get_linked_filmmaker_name($term) {
  return '<a href="' . fast_get_term_link($term, 'filmmaker') . '" class="filmmaker-page-link">' . $term->name . '</a>';
}

/**
 * Join a string with a natural language conjunction at the end.
 * https://gist.github.com/dan-sprog/e01b8712d6538510dd9c
 */
function natural_language_join(array $list, $conjunction = 'and') {
  $last = array_pop($list);
  if ($list) {
    return implode(', ', $list) . ' ' . $conjunction . ' ' . $last;
  }
  return $last;
}

// Doing this instead of `get_the_post_video_url()` avoids hitting the
// db to load all of the attachment info for the video.
function j4j_the_post_video() {
  global $post;
  $meta = get_post_meta($post->ID, '_fvp_video', true);

  // Don't render anything if this post doesn't have video
  if (!isset( $meta ) || empty($meta['full'])) {
    return;
  }

  echo wp_video_shortcode(array(
    'src' => $meta['full'],
    'poster' => get_the_post_thumbnail_url(),
    'width' => 320,
    'height' => 240
  ));
}

function the_linked_filmmaker_names($should_link = true) {
  $filmmaker_terms = get_the_terms(get_the_ID(), 'filmmaker');

  if (!is_array($filmmaker_terms)) {
    return;
  }

  if ($should_link) {
    $filmmakers = array_map("get_linked_filmmaker_name", $filmmaker_terms);
  } else {
    $filmmakers = array_map(function($filmmaker) { return $filmmaker->name; }, $filmmaker_terms);
  }

  echo natural_language_join($filmmakers);
}

function the_other_video_text($other_video) {
  $other_video_taxonomies = array_keys(get_the_taxonomies($other_video));
  if (in_array('chainletter', $other_video_taxonomies)) {
    $other_video_taxonomy = 'chainletter';
  } elseif (in_array('costar', $other_video_taxonomies)) {
    $other_video_taxonomy = 'costar';
  }
  if ($other_video_taxonomy) {
    $filmmaker_other_videos_chainletters = get_the_terms($other_video, $other_video_taxonomy);
    return '<em>' . $other_video->post_title . '</em> on <em><a href="' . fast_get_term_link($filmmaker_other_videos_chainletters[0], $other_video_taxonomy) . '">' . $filmmaker_other_videos_chainletters[0]->name . '</a></em>';
  }
}

function the_now_video_list($filmmaker) {
  $transient_key = 'now_video_list_' . $filmmaker->slug;

  if (false === ($now_video_list_string = get_transient($transient_key))) {
    $response_taxonomies = array_keys(get_the_taxonomies());
    if (in_array('chainletter', $response_taxonomies)) {
      $response_tapes = get_the_terms(get_the_ID(), 'chainletter');
      $response_tape_taxonomy = 'chainletter';
    } elseif (in_array('costar', $response_taxonomies)) {
      $response_tapes = get_the_terms(get_the_ID(), 'costar');
      $response_tape_taxonomy = 'costar';
    }
    $response_tape = $response_tapes[0]; // assume only one tape linked to each interview

    // Get the films by this filmmaker for the tape (chainletter or costar)
    // that this interview response is categorized under
    $filmmaker_response_tape_videos = get_posts(array(
      'numberposts' => 1,
      'post_type' => 'video',
      'tax_query' => array(
        array(
          'taxonomy' => 'filmmaker',
          'field' => 'term_id',
          'terms' => $filmmaker->term_id),
        array(
          'taxonomy' => $response_tape_taxonomy,
          'field' => 'term_id',
          'terms' => $response_tape->term_id)
      )
    ));

    // Get films by this filmmaker from other tapes not associated with this response
    $filmmaker_other_videos = get_posts(array(
      'numberposts' => -1,
      'post_type' => 'video',
      'tax_query' => array(
        array(
          'taxonomy' => 'filmmaker',
          'field' => 'term_id',
          'terms' => $filmmaker->term_id),
        array(
          'taxonomy' => $response_tape_taxonomy,
          'field' => 'term_id',
          'terms' => $response_tape->term_id,
          'operator' => 'NOT IN')
      )
    ));

    $now_video_list_string = '';

    if ($filmmaker_response_tape_videos) {
      // Note that we're assuming one tape by this filmmaker on the tape
      // linked to this interview
      $now_video_list_string .= '<em>' . get_the_title($filmmaker_response_tape_videos[0]) . '</em>';
    }

    if ($filmmaker_other_videos) {
      $other_video_texts = array_map('the_other_video_text', $filmmaker_other_videos);
      $now_video_list_string .= ' (also ' . natural_language_join($other_video_texts) . ')';
    }

    set_transient($transient_key, $now_video_list_string);
  }

  echo $now_video_list_string;
}

function get_the_filmmaker_links() {
  if (is_tax('filmmaker')) {
    $transient_key = 'filmmaker_links_for_filmmaker_page_' . get_queried_object_id();
  } else {
    $transient_key = 'filmmaker_links_for_video_' . get_the_ID();
  }
  if (false === ($filmmaker_links = get_transient($transient_key))) {
    $filmmaker_links = '';
    $filmmakers = is_tax('filmmaker') ? array(get_queried_object()) : get_the_terms(get_the_ID(), 'filmmaker');
    if ($filmmakers) {
      foreach ($filmmakers as $filmmaker) {
        $filmmaker_website = get_field('filmmaker_website', $filmmaker);
        if($filmmaker_website) {
          $filmmaker_links .= '<li><a href="' . $filmmaker_website . '">Filmmaker’s Website' . (count($filmmakers) > 1 ? ' (' . $filmmaker->name . ')' : '') . ' &raquo;</a></li>';
        }

        $now_response = null;
        global $wp_query;
        // `$wp_query->now_posts` is set up in `j4j_the_posts` for tape pages but not video pages
        if ($wp_query->now_posts) {
          if (array_key_exists($filmmaker->term_id, $wp_query->now_posts))
            $now_response = $wp_query->now_posts[$filmmaker->term_id];
        } else {
          $now_responses = get_posts(array(
            'numberposts' => 1,
            'post_type' => 'now_response',
            'tax_query' => array(array(
              'taxonomy' => 'filmmaker',
              'field' => 'term_id',
              'terms' => $filmmaker->term_id
          ))));
          $now_response = empty($now_responses) ? null : $now_responses[0];
        }

        if ($now_response) {
          $filmmaker_links .= '<li><a href="' . get_post_permalink($now_response->ID) . '">Where Is She Now?' . (count($filmmakers) > 1 ? ' (' . $filmmaker->name . ')' : '') . ' &raquo;</a></li>';
        }
      }
    }
    set_transient($transient_key, $filmmaker_links);
  }
  return $filmmaker_links;
}

function the_video_chainletter_links() {
  global $post;
  $transient_key = 'video_chainletter_links_' . $post->post_name;

  if (false === ($html = get_transient($transient_key))) {
    $tapes = wp_get_object_terms($post->ID, array('chainletter', 'costar'));

    if ($tapes) {
      $html = 'Appears on ';

      foreach ($tapes as $i => $tape) {
        $html .= '<a href="' . fast_get_term_link($tape, $tape->taxonomy) . '">' . $tape->name . '</a> ('. substr(get_field('chainletter_fulfilled_date', $tape), 0, 4) . ')';
        if ($i < count($tapes) - 1) {
          $html .= ', ';
        }
      }
      set_transient($transient_key, $html);
    }
  }

  echo $html;
}

function the_chainletter_meta() {
  $term = get_queried_object();
  $fulfilled_date = DateTime::createFromFormat('Ymd', get_field('chainletter_fulfilled_date', $term));
  $compiled_by = get_field('chainletter_compiled_by', $term);
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
    the_post_thumbnail('medium');
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

function get_taxonomy_terms_by_custom_date($taxonomy, $term_fields = null, $limit = false) {
  global $wpdb;
  $term_fields = is_array($term_fields) ? implode(', ', $term_fields) : '*';
  $date_field = 'chainletter_fulfilled_date';
  $orderby = 'date';

  $query = "SELECT $wpdb->terms." . $term_fields . ", option_value AS date
            FROM $wpdb->term_taxonomy
            JOIN $wpdb->terms ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
            JOIN $wpdb->options ON $wpdb->options.option_name = CONCAT_WS('_', '" . $taxonomy . "', $wpdb->term_taxonomy.term_id, '" . $date_field ."')
            WHERE $wpdb->term_taxonomy.taxonomy = '" . $taxonomy . "'
            ORDER BY " . $orderby .
            ($limit ? (" LIMIT " . $limit) : "");
  $terms = $wpdb->get_results($query);
  return $terms;
}

function get_taxonomy_sidebar() {
  $taxonomy = get_queried_object()->taxonomy;
  $current_term_slug = get_query_var('term') ? get_query_var('term'): $terms[0]->slug;
  $transient_key = 'taxonomy_sidebar_' . $taxonomy . '_' . $current_term_slug;

  if (false === ($sidebar_object = get_transient($transient_key))) {
    $terms = get_taxonomy_terms_by_custom_date($taxonomy);
    $bard_chainletter_ids = array(
      92, // The Newborn Chainletter
      245, // The Frozen Chainletter
      96, // Girafferator A Chainletter
      97, // Transformer Chainletter
    );

    $sidebar_dom = '<ul>';
      foreach ($terms as $i => $term) {
        $is_current = $term->slug == $current_term_slug;
        $sidebar_dom .= '<li class="menu-item'  . ($is_current ? ' current-menu-item' : '') . (in_array($term->term_id, $bard_chainletter_ids) ? ' bard-chainletter' : '') . '"><a href="' . fast_get_term_link($term, $taxonomy) . '">' . substr($term->date, 0, 4) . ' ' . $term->name . '</a></li>';
        if ($is_current) { $current_index = $i; }
      }
    $sidebar_dom .= '</ul>';

    $pagination = array(
      'previous' => isset($current_index) && $current_index > 0 ? $terms[$current_index - 1] : null,
      'next' => isset($current_index) && $current_index < (count($terms) - 1) ? $terms[$current_index + 1] : null
    );

    $sidebar_object = array('dom' => $sidebar_dom, 'pagination' => $pagination);
    set_transient($transient_key, $sidebar_object);
  }

  echo($sidebar_object['dom']);
  return $sidebar_object['pagination'];
}

function get_now_sidebar_section_items($taxonomy) {
  $transient_key = 'now_sidebar_items_' . $taxonomy . '_' . get_queried_object()->slug;

  if (false === ($sidebar_items_object = get_transient($transient_key))) {
    $terms = get_taxonomy_terms_by_custom_date($taxonomy, array('name', 'slug'));
    $sidebar_dom = '';

    foreach ($terms as $i => $term) {
      $is_current = $term->slug == get_queried_object()->slug;
      $sidebar_dom .= '<li class="now-sidebar__section__item' . ($is_current ? ' current-menu-item' : '') . '"><a href="' . get_the_now_link($term, $taxonomy) . '">' . substr($term->date, 0, 4) . ' ' . $term->name . '</a></li>';
      if ($is_current) { $current_index = $i; }
    }

    // $current_index won't be set on /now since it defaults to supporters
    $pagination = array(
      'previous' => isset($current_index) && $current_index > 0 ? $terms[$current_index - 1] : null,
      'next' => isset($current_index) && $current_index < count($terms) - 1 ? $terms[$current_index + 1] : null,
      'first' => $terms[0],
      'last' => array_pop($terms)
    );

    $sidebar_items_object = array('dom' => $sidebar_dom, 'pagination' => $pagination);
    set_transient($transient_key, $sidebar_items_object);
  }

  echo($sidebar_items_object['dom']);
  return $sidebar_items_object['pagination'];
}

function get_post_type_sidebar() {
  global $post;
  if ($post->post_type == 'page') {
    $post_type = $post->post_name == 'events' ? 'event' : 'archive';
  } else {
    $post_type = $post->post_type;
  }
  if ($post_type == 'event') {
    $sidebar_posts = get_posts(array(
      'order' => 'ASC',
      'meta_key' => 'event_start_date',
      'orderby' => 'meta_value_num',
      'post_type' => $post_type,
      'numberposts' => -1
    ));
  } else {
    $sidebar_posts = get_posts(array(
      'order'=> 'ASC',
      'orderby' => 'title',
      'post_type' => $post_type,
      'numberposts' => -1
    ));
  }

  echo('<nav class="sidebar"><ul>');
  foreach ($sidebar_posts as $i => $sidebar_post) {
    $year = '';
    if ($post_type == 'event') {
      $meta = get_post_meta($sidebar_post->ID);
      // $year = DateTime::createFromFormat('Ymd', get_field('event_start_date', $sidebar_post->ID))->format('Y ');
      $start_year = substr($meta['event_start_date'][0], 0, 4);
      $end_year = substr($meta['event_end_date'][0], 0, 4);
      $year = $start_year . ($end_year && $end_year != $start_year ? ' - ' . $end_year : null) . ' ';
    }

    $is_current = $sidebar_post->ID == $post->ID;
    echo('<li' . ($is_current ? ' class="current-menu-item">' : '>') . '<a href="' . get_permalink($sidebar_post->ID) . '">' . $year . $sidebar_post->post_title . '</a></li>');
    if ($is_current) { $current_index = $i; }
  }
  echo('</ul></nav>');

  $pagination = array(
    'previous' => isset($current_index) && $current_index > 0 ? $sidebar_posts[$current_index - 1] : null,
    'next' => isset($current_index) && $current_index < count($sidebar_posts) - 1 ? $sidebar_posts[$current_index + 1] : null,
  );
  return $pagination;
}

function the_event_date() {
  $start_date = DateTime::createFromFormat('Ymd', get_field('event_start_date'));
  $end_date = DateTime::createFromFormat('Ymd', get_field('event_end_date'));
  if (get_field('ignore_event_day') == true) {
    if ($end_date) {
      $start_date_format = $start_date->format('Y') == $end_date->format('Y') ? 'F' : 'F Y';
      echo $start_date->format($start_date_format) . ' - ' . $end_date->format('F Y');
    } else {
      echo $start_date->format('F Y');
    }
  } else {
    if ($end_date) {
      $start_date_format = $start_date->format('Y') == $end_date->format('Y') ? 'F d' : 'F d, Y';
      echo $start_date->format($start_date_format) . ' - ' . $end_date->format('F d, Y');
    } else {
      echo $start_date->format('F d, Y');
    }
  }
}

function the_TOC_now_link($acg_id) {
  $year = substr(get_field('chainletter_fulfilled_date', $acg_id), 0, 4);
  $title_string = str_replace(' ', '_', get_queried_object()->name);
  // remove non-alphanumeric characters to match TOC plugin generated anchor tags
  echo preg_replace('/[^a-zA-Z0-9 \-_]*/', '', $year . _ . $title_string);
}

?>
