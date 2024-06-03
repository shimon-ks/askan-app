


  // // Wait for the document to be ready
  // document.addEventListener('DOMContentLoaded', function() {
  //   // Get the element by id
  //   var element = document.getElementById('tokenid');

  //   // Check if the element exists
  //   if (element) {
  //     // Get the value of data-token-id attribute
  //     var dataTokenIdValue = element.getAttribute('data-token-id');

  //     // Alert the value
  //     alert("data-token-id: " + dataTokenIdValue);
  //   } else {
  //     // Alert that the element does not exist
  //     alert("The element with id 'tokenid' does not exist.");
  //   }
  // });
console.log('lol');
  let value = localStorage.getItem('myKey'); // value יהיה 'myValue'
alert(value)
jQuery(document).ready(function($) {


    // for (const [key, value] of Object.entries(onesignal_device_id)) {
    //   alert(key + ": " + value);
    // }

    // var array = Object.values(onesignal_device_id);

    // var string = array.join(", ");
    
    // alert(string);
  // $('.sites-slider').slick({
  //   slidesToShow: 5,
  //   slidesToScroll: 1,
  //   autoplay: true,
  //   autoplaySpeed: 2000,
  //   arrows: true,
  //   prevArrow: "<div class='slick-prev-sites arrow-style'><i class='fa fa-angle-right' aria-hidden='true'></i></div>",
  //   nextArrow: "<div class='slick-next-sites arrow-style'><i class='fa fa-angle-left' aria-hidden='true'></i></div>",
  // });
  // $('.posts .status').slick({
  //   slidesToShow: 3,
  //   slidesToScroll: 1,
  //   autoplay: true,
  //   autoplaySpeed: 2000,
  // });
  // ( function( $ ) {
  //   class SlickCarousel {
  //     constructor() {
  //       this.initiateCarousel();
  //     }
  
  //     initiateCarousel() {
  //       $('.sites-slider.front').slick({
  //         slidesToShow: 5,
  //         slidesToScroll: 1,
  //         autoplay: true,
  //         rtl: true,
  //         infinite: true,
  //         autoplaySpeed: 2000,
  //         arrows: true,
  //         prevArrow: "<div class='slick-prev-sites arrow-style'><i class='fa fa-angle-right' aria-hidden='true'></i></div>",
  //         nextArrow: "<div class='slick-next-sites arrow-style'><i class='fa fa-angle-left' aria-hidden='true'></i></div>",
  //       });
  //       $('.status').slick({
  //         slidesToShow: 2,
  //         slidesToScroll: 1,
  //         autoplay: true,
  //         rtl: true,
  //         autoplaySpeed: 2000,
  //       });
  //     }
  //   }
  
  //   new SlickCarousel();
  
  // } )( jQuery );
  $('.option .report').on('click', function() {
    $('.reportModal').show();
  })
  $('.reportModal .close').on('click', function() {
    $('.reportModal').hide();
  })
  // window.onclick( function(event) {
  //   if (event.target == $('.reportModal')) {
  //     $('.reportModal').hide();
  //   }
  // });
  $('.header-content .toggle-icon').on('click', function() {
    $('.side-manu').css('width', '300px');
  });
  $('.side-manu .close').on('click', function() {
    $('.side-manu').css('width', '0px');
  })
});

