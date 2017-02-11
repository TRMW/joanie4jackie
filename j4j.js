(function($) {
  var scrollPosition = null,
      $body = $('body');

  // CSS media queries determine whether this toggle is visible
  $('.mobile-menu-toggle').on('click', function(e) {
    if ($body.hasClass('showing-mobile-nav')) {
      $('.sidebar').append($('#menu-j4j-menu .current-menu-item > ul'));
      $body.removeClass('showing-mobile-nav');
      $(window).scrollTop(scrollPosition);
    } else {
      $('#menu-j4j-menu .current-menu-item').append($('.sidebar > ul'));
      scrollPosition = $(window).scrollTop();
      $(window).scrollTop(0);
      $body.addClass('showing-mobile-nav');
    }
  });
})(jQuery);
