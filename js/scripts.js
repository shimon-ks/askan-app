jQuery(document).ready(function($) {

  //   var loaderanimationtouchend = lottie.loadAnimation({
  //     container: document.getElementById('loader-lottie-animation-for-touchend'),
  //     renderer: 'svg',
  //     loop: true,
  //     autoplay: false,
  //     path: ajax_object.url + '/images/touchendloader.json' // Replace with the actual path to your Lottie file
  // });

  // function showMainLoaderTouchend() {
  //     $('#loader-touchend').show();
  //     loaderanimationtouchend.play();
  // }
  /// reload page scrooll
  let isMenuOpen = false;

  $('.header-content .toggle-icon').on('click', function() {
    $('body').css({
      overflow: 'hidden',
      height: '100vh'
    });
    $('#menu-overlay').show(); // הצג את השכבה החופפת
    isMenuOpen = true; // עדכון מצב פתיחת התפריט
  });

  // כאשר התפריט נסגר
  $('.side-manu .close').on('click', function() {
    $('body').css({
      overflow: '',
      height: ''
    });
    $('#menu-overlay').hide(); // הסתר את השכבה החופפת
    isMenuOpen = false; // עדכון מצב סגירת התפריט
  });

  // משתני מגע להתחלת וסיום מגע
  let touchStartY = 0;
  let touchEndY = 0;
  let requiredDistance = 300;

  // אירוע התחלת מגע
  $(document).on('touchstart', function(e) {
    if (!isMenuOpen && $(window).scrollTop() === 0) {
      touchStartY = e.originalEvent.touches[0].clientY;
    }
  });

  // אירוע תנועת מגע
  $(document).on('touchmove', function(e) {
    if (!isMenuOpen && $(window).scrollTop() === 0) {
      touchEndY = e.originalEvent.touches[0].clientY;
    }
  });

  // אירוע סיום מגע
  $(document).on('touchend', function(e) {
    if (!isMenuOpen && $(window).scrollTop() === 0 && (touchEndY - touchStartY) > requiredDistance) {
      // showMainLoaderTouchend()

      setTimeout(function() {
        window.location.reload();
      }, 300);
    }
  });

  /// end reload page scrooll






  $('.search').click(function() {
    $('#menu-overlay').show(); // הצג את השכבה החופפת

    $('#searchBox').fadeIn(); // מציג את רובריקת החיפוש
});

$('#closeSearch').click(function(event) {
  $('#menu-overlay').hide(); // הצג את השכבה החופפת

    event.stopPropagation(); // מונע את ההתפשטות של האירוע
    $('#searchBox').fadeOut(); // מסתיר את רובריקת החיפוש
});




  // scrool menu to active item
  var $activeItem = $('.bottom-head .active'); // שנה את '.your-menu-class' למחלקה המתאימה של התפריט שלך
  if ($activeItem.length) {
      var containerScrollPos = $('.bottom-head').scrollLeft(); // קבל את מיקום הגלילה הנוכחי של התפריט
      var activeItemOffset = $activeItem.offset().left; // קבל את המיקום האופקי של האייטם הפעיל ביחס למסך
      var containerWidth = $('.bottom-head').width(); // קבל את רוחב התפריט
      var activeItemWidth = $activeItem.width(); // קבל את רוחב האייטם הפעיל

      // חשב את מיקום הגלילה החדש כך שהאייטם הפעיל יהיה במרכז
      var scrollTo = containerScrollPos + activeItemOffset - containerWidth / 2 + activeItemWidth / 2;

      // גלול את התפריט למיקום החדש
      $('.bottom-head').animate({
          scrollLeft: scrollTo
      }, 800); // שנה את המהירות (800 מילישניות בדוגמה) לפי הצורך
  }



  $('.option .report').on('click', function() {
    $('.reportModal').show();
  })
  $('.reportModal .close').on('click', function() {
    $('.reportModal').hide();
  })

  ///menu
  $('.header-content .toggle-icon').on('click', function() {
    var sidebar = $('.side-manu');
        sidebar.animate({right: '0px'}, 300);

        $('body').css({
          overflow: 'hidden',
          height: '100vh'
        });
  });

  $('.side-manu .close').on('click', function() {
    console.log('close');
    var sidebar = $('.side-manu');
    sidebar.animate({right: '-400px'}, 300);

    $('body').css({
      overflow: '',
      height: ''
    });
  });



  $('.posts, .bottom-post').on('click', '.report-wrap', function() {
    console.log('report in');

    var postID = $(this).data('post-id');
    var that = $(this); 

    $.ajax({
      url: askan_js_ajax_object.ajax_url,
      type: 'POST',
      data: {
          action: 'post_report',
          post_id: postID
      },
      success: function(response) {
        that.find('.report-txt').text('הדיווח נשלח וייבדק');
        setTimeout(function() {
            that.css({'display': 'none'});
        }, 1000);
      },
      error: function(xhr, status, error) {
          console.error(error);
      }
    });
  });

  $('.posts, .bottom-post').on('click', '.open-report', function() {
    
    $(this).parent().find('.report-wrap').css({'display': 'flex'});
  });

  

  $('.posts').on('click', '.share', function() {
    if (navigator.share) {
      navigator.share({
          title: 'כותרת לשיתוף',  // כותרת התוכן שתרצה לשתף
          text: 'טקסט לשיתוף',   // תיאור או טקסט התוכן לשיתוף
          url: 'https://example.com' // ה-URL שתרצה לשתף
      }).then(() => {
          console.log('השיתוף בוצע בהצלחה');
      }).catch((error) => {
          console.error('התרחשה שגיאה בעת השיתוף', error);
      });
      } else {
          console.log('Web Share אינו נתמך במכשיר זה');
      }
  });
  



});



