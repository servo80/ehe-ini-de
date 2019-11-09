$(document).ready(function() {

  (function($) {
    $(function() {

      $('.jcarousel').jcarousel({
        transitions: Modernizr.csstransitions ? {
          transforms:   Modernizr.csstransforms,
          transforms3d: Modernizr.csstransforms3d,
          easing:       'ease'
        } : false
      })
        .jcarouselAutoscroll({
        interval: 10000,
        target: '+=1',
        autostart: true
      });

      $('.jcarousel').on('jcarousel:lastin', 'li', function(event, carousel) {
        var current = $(this).children('img').attr('src');
        var last = $('.jcarousel ul li:last-child img').attr('src');

        if(current == last) {
          var instance = $('.jcarousel').data('jcarousel');
            setTimeout(
              function() {
                instance.scroll(0);
              },
              10000
            );
          ;
        }

      });

      $('.jcarousel-control-prev')
        .on('jcarouselcontrol:active', function() {
          $(this).removeClass('inactive');
        })
        .on('jcarouselcontrol:inactive', function() {
          $(this).addClass('inactive');
        })
        .jcarouselControl({
          target: '-=1'
        });

      $('.jcarousel-control-next')
        .on('jcarouselcontrol:active', function() {
          $(this).removeClass('inactive');
        })
        .on('jcarouselcontrol:inactive', function() {
          $(this).addClass('inactive');
        })
        .jcarouselControl({
          target: '+=1'
        });

      $('.jcarousel-pagination')
        .on('jcarouselpagination:active', 'a', function() {
          $(this).addClass('active');
        })
        .on('jcarouselpagination:inactive', 'a', function() {
          $(this).removeClass('active');
        })
        .jcarouselPagination();
    });

  })(jQuery);

  $('a.toggleMenu').click(function() {
    $('ol.main').toggle();
    var display = $('ol.main').css('display');
    if('block' === display) {
      $('a.toggleMenu img').attr('src', '../custom/themes/ehe-ini.de/images/close.png');
    } else {
      $('a.toggleMenu img').attr('src', '../custom/themes/ehe-ini.de/images/menu.png');
    }
  });

});