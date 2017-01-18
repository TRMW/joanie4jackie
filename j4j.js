function formatTitle(title, currentArray, currentIndex, currentOpts) {
  var flickrLink = jQuery(currentArray[currentIndex]).attr('href');
  return '<span id="fancybox-title-over"><strong>' + title + '</strong><span class="fancybox-title-over-left">Image ' +  (currentIndex + 1) + ' of ' + currentArray.length + '</span><span class="fancybox-title-over-right"><a href="' + flickrLink + '" target="_blank">View Full Size</a><span></span>';
}

// https://raw.github.com/Modernizr/Modernizr/master/feature-detects/css/positionsticky.js
function supportsSticky() {
  var prop = 'position:',
      value = 'sticky',
      prefixes = ['-webkit-', '-moz-', '-o-', '-ms-'],
      el = document.createElement('div'),
      mStyle = el.style;

  mStyle.cssText = prop + prefixes.join(value + ';' + prop).slice(0, -prop.length);
  return mStyle.position.indexOf(value) !== -1;
}

function positionNowNav() {
  if ($(this).scrollTop() >= offset.top) {
    if (noStickySupport) $toc.addClass('fixed');
    else if (!$toc[0].style.height) $toc.css('height', $(window).height() - 30);
  } else {
    if (noStickySupport) $toc.removeClass('fixed');
    else $toc.css('height', '');
  }
}

(function($) {

  // Replace video links with modal players
  if ($('.vimeo-link').length) {
    var $links = $('.vimeo-link'),
        aspect = 500 / 375,
        windowHeight = $(window).height(),
        windowWidth = $(window).width(),
        resizedHeight,
        resizedWidth;

    // Swap in player URL
    $.each($links, function() {
      this.href = this.href.replace('http://vimeo.com/','http://player.vimeo.com/video/') + '?byline=false&title=false&portrait=false&color=0xFFFFFF&autoplay=true';
    });

    // Resize to fit window
    if ( windowHeight < windowWidth ) {
      resizedHeight = windowHeight - 100;
      resizedWidth = resizedHeight * aspect;
    } else { // screen width is smaller than height - HELLO IPHONE!
      resizedWidth = windowWidth - 100;
      resizedHeight = resizedWidth / aspect;
    }

    $links.fancybox({
      type: 'iframe',
      width: resizedWidth,
      height: resizedHeight,
      centerOnScroll: true
    });
  }

  var photosetId;

  // Replace booklet photoset links with slideshow
  if ($('.booklet-link').length) {
    photosetId = $('.booklet-link a').attr('href').match(/\/([^\/]*)\/$/)[1];
    var photoset = '';

    $.get('https://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=4c32dbf63d7deabd1ec94d208d0961c0&photoset_id=' + photosetId + '&extras=url_o&format=json&nojsoncallback=1', function(response) {
      for (var i = 0; i < response.photoset.photo.length; i++) {
        photoset += '<a href="' + response.photoset.photo[i].url_o + '" class="booklet-image" id="booklet-image-' + i + '" rel="booklet-gallery">View booklet »</a>';
      }
      $('.booklet-link').html(photoset);
      $('.booklet-image').fancybox({
        cyclic: true,
        titlePosition: 'over',
        centerOnScroll: true,
        titleFormat: formatTitle,
        changeFade: 0
      });
    });
  }

  // Fetch and append archive photosets
  var $photosetContainer = $('.entry-photoset');
  if ($photosetContainer.length) {
    // Get last string of digits, with possible trailing slash
    photosetId = $photosetContainer.data('photoset-link').match(/([0-9]*)\/?$/)[1];

    $.get('https://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=4c32dbf63d7deabd1ec94d208d0961c0&photoset_id=' + photosetId + '&extras=url_s,url_o&format=json&nojsoncallback=1', function(response) {
      for (var i = 0; i < response.photoset.photo.length; i++) {
        $photosetContainer.append('<a href="' + response.photoset.photo[i].url_o + '" class="photoset-image" rel="photoset-gallery" title="' + response.photoset.photo[i].title + '"><img src="' + response.photoset.photo[i].url_s + '" width="' + response.photoset.photo[i].width_s + '" height="' + response.photoset.photo[i].height_s + '"></a>');
      }
      $('.photoset-image').fancybox({
        cyclic: true,
        titlePosition: 'over',
        centerOnScroll: true,
        titleFormat: formatTitle,
        changeFade: 0
      });
    });
  }

  var $toc = $('#toc_container'),
      offset = $toc.offset(),
      noStickySupport = !supportsSticky();

  if ($toc.length) {
    if (noStickySupport) $toc.css('left', offset.left + 'px');
    positionNowNav();
    $(document).on('scroll', positionNowNav);
  }

})(jQuery);
