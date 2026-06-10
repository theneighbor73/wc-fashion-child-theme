jQuery(function($){
  "use strict";
  jQuery('.main-menu-navigation > ul').superfish({
    delay: 500,
    animation: {opacity:'show',height:'show'},
    speed:'fast'
  });
});

/* TODO: mobile menu toggle behavior remains stable.
   For redesign, move the toggle button in the header markup to the first mobile column.
   Keep this open/close state management unchanged.
*/
function custom_print_shop_menu_open() {
  jQuery(".side-menu").addClass('open');
}
function custom_print_shop_menu_close() {
  jQuery(".side-menu").removeClass('open');
}

/* TODO: search overlay behavior remains available, but the header product search component should be moved to a second full-width mobile row in the redesign. */
function custom_print_shop_search_show() {
  jQuery(".search-outer").addClass('show');
  jQuery(".search-outer").fadeIn();
}
function custom_print_shop_search_hide() {
  jQuery(".search-outer").removeClass('show');
  jQuery(".search-outer").fadeOut();
}

(function( $ ) {
  // Back to top
  jQuery(document).ready(function () {
    jQuery(window).scroll(function () {
      if (jQuery(this).scrollTop() > 0) {
        jQuery('.scrollup').fadeIn();
      } else {
        jQuery('.scrollup').fadeOut();
      }
    });
    jQuery('.scrollup').click(function () {
      jQuery("html, body").animate({
        scrollTop: 0
      }, 600);
      return false;
    });
  });

  // Window load function
  window.addEventListener('load', (event) => {
    jQuery(".preloader").delay(2000).fadeOut("slow");
  });

})( jQuery );

( function( window, document ) {
  function custom_print_shop_keepFocusInMenu() {
    document.addEventListener( 'keydown', function( e ) {
      const custom_print_shop_nav = document.querySelector( '.side-menu' );

      if ( ! custom_print_shop_nav || ! custom_print_shop_nav.classList.contains( 'open' ) ) {
        return;
      }

      const elements = [...custom_print_shop_nav.querySelectorAll( 'input, a, button' )],
        custom_print_shop_lastEl = elements[ elements.length - 1 ],
        custom_print_shop_firstEl = elements[0],
        custom_print_shop_activeEl = document.activeElement,
        tabKey = e.keyCode === 9,
        shiftKey = e.shiftKey;

      if ( ! shiftKey && tabKey && custom_print_shop_lastEl === custom_print_shop_activeEl ) {
        e.preventDefault();
        custom_print_shop_firstEl.focus();
      }

      if ( shiftKey && tabKey && custom_print_shop_firstEl === custom_print_shop_activeEl ) {
        e.preventDefault();
        custom_print_shop_lastEl.focus();
      }
    } );
  }
  custom_print_shop_keepFocusInMenu();

} )( window, document );

// product
jQuery(document).ready(function($) {
jQuery('#banner .slider-for').slick({
  slidesToShow: 1,
  infinite: true,
  arrows: true,
  fade: true,
  asNavFor: '.slider-nav',

});
jQuery('#banner .slider-nav').slick({
  slidesToShow: 4,
  infinite: true,
  centerPadding: '0px',
  centerMode: true,
  arrows: true,
  slidesToScroll: 1,
  asNavFor: '#banner .slider-for',
  prevArrow: '<i class="fa fa-chevron-left"></i>',
  nextArrow: '<i class="fa fa-chevron-right"></i>',
  dots: false,
  focusOnSelect: true,
  responsive: [
  {
    breakpoint: 1024,
    settings: {
    slidesToShow: 2,
  }
},
{
    breakpoint: 768,
    settings: {
    slidesToShow: 2,
  }
},
{
    breakpoint: 425,
    settings: {
    slidesToShow: 1,
  }
},

  {
    breakpoint: 1200,
    settings: {
    slidesToShow: 2,
  }
  }
]
})
});

// tab
jQuery(document).ready(function () {
  jQuery( ".tablinks" ).first().addClass( "active" );
});

function custom_print_shop_project_tab(evt, cityName) {
  var custom_print_shop_i, custom_print_shop_tabcontent, custom_print_shop_tablinks;
  custom_print_shop_tabcontent = document.getElementsByClassName("tabcontent");
  for (custom_print_shop_i = 0; custom_print_shop_i < custom_print_shop_tabcontent.length; custom_print_shop_i++) {
    custom_print_shop_tabcontent[custom_print_shop_i].style.display = "none";
  }
  custom_print_shop_tablinks = document.getElementsByClassName("tablinks");
  for (custom_print_shop_i = 0; custom_print_shop_i < custom_print_shop_tablinks.length; custom_print_shop_i++) {
    custom_print_shop_tablinks[custom_print_shop_i].className = custom_print_shop_tablinks[custom_print_shop_i].className.replace(" active", "");
  }
  jQuery('#'+ cityName).show()
  evt.currentTarget.className += " active";
}

jQuery(document).ready(function () {
  jQuery('.tabcontent').hide();
  jQuery('.tabcontent:first').show();
});

/*sticky copyright*/
window.addEventListener('scroll', function() {
  var custom_print_shop_sticky = document.querySelector('.copyright-sticky');
  if (!custom_print_shop_sticky) return;

  var scrollTop = window.scrollY || document.documentElement.scrollTop;
  var windowHeight = window.innerHeight;
  var documentHeight = document.documentElement.scrollHeight;

  var isBottom = scrollTop + windowHeight >= documentHeight-100;

  if (scrollTop >= 100 && !isBottom) {
    custom_print_shop_sticky.classList.add('copyright-fixed');
  } else {
    custom_print_shop_sticky.classList.remove('copyright-fixed');
  }
});

// Custom Cursor JS Load Control
document.addEventListener("mousemove", function(e){
    let cursor = document.querySelector(".custom-cursor");
    if(cursor){
        cursor.style.left = e.clientX + "px";
        cursor.style.top = e.clientY + "px";
    }
});

