// Fire when DOM is loaded
jQuery(document).ready(function($) {

  //Troligen ingen sök så ta bort sen
  $( "nav.search" ).click(function() {
    $('body').toggleClass('open-nav');
    if( $( "body.home" ).hasClass("open-nav") ) {
      }
      else {
    }
    $('.search-field').focus();
  });

  $( "button.hamburger" ).click(function() {
    $('body').toggleClass('open-nav');
  });

  $( ".artwork img" ).click(function() {
    $('body').toggleClass('fullscreen');
    var src = $(this).attr('src');
    $('.fullscreen-image img').attr("src",src);
    $('.fullscreen-image').css("top",$(document).scrollTop());
  });

  $( ".fullscreen-image img" ).click(function() {
    $('body').toggleClass('fullscreen');
  });

  //forms
  $('.page-title input').val( $('main h1').text() );

});

// Fire when pages is loaded
jQuery(window).load(function($) {
  if (jQuery('.swiper-container').length) {
      mySwiper.update();
      var activeSlide = jQuery('.swiper-slide')[mySwiper.realIndex + 1];
      var activeCaption = jQuery(activeSlide).find('.caption').text();
      jQuery('.swiper-footer .caption').text(activeCaption);
  }




});
