(function($) {

    "use strict";

    var searchPopup = function() {
      // open search box
      $('#header-nav').on('click', '.search-button', function(e) {
        $('.search-popup').toggleClass('is-visible');
      });

      $('#header-nav').on('click', '.btn-close-search', function(e) {
        $('.search-popup').toggleClass('is-visible');
      });
      
      $(".search-popup-trigger").on("click", function(b) {
          b.preventDefault();
          $(".search-popup").addClass("is-visible"),
          setTimeout(function() {
              $(".search-popup").find("#search-popup").focus()
          }, 350)
      }),
      $(".search-popup").on("click", function(b) {
          ($(b.target).is(".search-popup-close") || $(b.target).is(".search-popup-close svg") || $(b.target).is(".search-popup-close path") || $(b.target).is(".search-popup")) && (b.preventDefault(),
          $(this).removeClass("is-visible"))
      }),
      $(document).keyup(function(b) {
          "27" === b.which && $(".search-popup").removeClass("is-visible")
      })
    }

    var initProductQty = function(){

      $('.product-qty').each(function(){

        var $el_product = $(this);
        var quantity = 0;

        $el_product.find('.quantity-right-plus').click(function(e){
            e.preventDefault();
            var quantity = parseInt($el_product.find('#quantity').val());
            $el_product.find('#quantity').val(quantity + 1);
        });

        $el_product.find('.quantity-left-minus').click(function(e){
            e.preventDefault();
            var quantity = parseInt($el_product.find('#quantity').val());
            if(quantity>0){
              $el_product.find('#quantity').val(quantity - 1);
            }
        });

      });

    }

    $(document).ready(function() {

      searchPopup();
      initProductQty();

      var swiper = new Swiper(".main-swiper", {
        speed: 500,
        navigation: {
          nextEl: ".swiper-arrow-next",
          prevEl: ".swiper-arrow-prev",
        },
      });         

      console.log(Swiper)

      function initCarousel(idCarousel) {
        var swiper = new Swiper(`${idCarousel} .swiper`, {
          slidesPerView: 4,
          spaceBetween: 10,
          pagination: {
            el: `${idCarousel} .swiper-pagination`,
            clickable: true,
          },
          breakpoints: {
            0: {
              slidesPerView: 2,
              spaceBetween: 20,
            },
            980: {
              slidesPerView: 4,
              spaceBetween: 20,
            }
          },
        });
      }

      initCarousel('#kuebasah')
      initCarousel('#kuekering')
      initCarousel('#bolu')
      initCarousel('#asinan-gorengan')

      var swiper = new Swiper(".testimonial-swiper", {
        loop: true,
        navigation: {
          nextEl: ".swiper-arrow-next",
          prevEl: ".swiper-arrow-prev",
        },
      }); 

    }); // End of a document ready

})(jQuery);

// Data Maps
const mapData = { location: "Latitude,Longitude", name: "Location Name" };

// Fungsi untuk membuat elemen Map
function createMapItem(data) {
  const figure = document.createElement("figure");
  figure.className = "map-item";

  const iframe = document.createElement("iframe");
  iframe.src = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.6742844964597!2d117.16134747472361!3d-0.4868527995083761!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df67f664d96f2c3%3A0x4303b97f13d9511b!2sRoti%20Bahari!5e0!3m2!1sid!2sid!4v1715170437628!5m2!1sid!2sid";
  iframe.width = "900";
  iframe.height = "450";
  iframe.style.border = "0";
  iframe.allowfullscreen = "";
  iframe.loading = "lazy";
  iframe.referrerpolicy = "no-referrer-when-downgrade";

  figure.appendChild(iframe);

  return figure;
}

// // Menambahkan data Maps ke dalam elemen
// const mapContainer = document.getElementById("map-container");
// const mapItem = createMapItem(mapData);
// mapContainer.appendChild(mapItem);
















