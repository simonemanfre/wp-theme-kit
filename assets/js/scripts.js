jQuery(document).ready(function ($) {
  //Polyfill Object Fit su I.E. --> font-family: 'object-fit: cover;';
  if (typeof objectFitImages == "function") {
    objectFitImages();
  }

  //MENÙ
  jQuery(".j-toggle").click(function () {
    jQuery("body").toggleClass("u-menu-open");
  });

  //SMOOTH SCROLL
  jQuery("a").on("click", function (event) {
    if (this.hash !== "") {
      var hash = this.hash;
      if (jQuery(hash).offset() != undefined) {
        event.preventDefault();
        jQuery("html, body").animate(
          {
            scrollTop: jQuery(hash).offset().top - 92,
          },
          800
        );
      }
    }
  });

  //HEADROOM STICKY HEADER
  /*
  var options = {
    // vertical offset in px before element is first unpinned
    offset: 100,
    // scroll tolerance in px before state changes
    tolerance: 100,
    // or you can specify tolerance individually for up/down scroll
    tolerance: {
      up: 5,
      down: 0,
    },
  };
  var myElement = document.querySelector(".c-site-header");
  var headroom = new Headroom(myElement, options);
  headroom.init();
  */

  //SLIDER
  if (php_vars.fancybox) {
    //FANCYBOX QUI
    /* ESEMPIO
    IMPORTANTE: ATTRIBUIRE AGLI SLIDER LE CLASSI carousel e carousel__slide
    let sliders = document.querySelectorAll(".carousel");
    let sliderPicture = [];

    for (i = 0; i < sliders.length; ++i) {
      sliderPicture[i] = new Carousel(sliders[i], {
        slidesPerPage: 1,
        center: false,
        Navigation: {
          prevTpl:
            '<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="m17.7 4.437-8.056 8.055 8.056 8.056-1.69 1.69-8.908-8.909-.801-.837.808-.845 8.9-8.9 1.691 1.69Z" fill="#142F42"/></svg>',
          nextTpl:
            '<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="m6.3 20.548 8.056-8.056L6.3 4.437l1.69-1.69 8.908 8.908.801.837-.808.845-8.9 8.9-1.691-1.69Z" fill="#142F42"/></svg>',
        },
      });
    }
    */
  }
});
