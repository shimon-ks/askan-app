
jQuery(document).ready(function($) {
  /// reload page scrooll
let touchStartY = 0;
let touchEndY = 0;
let requiredDistance = 300; 

$(document).on('touchstart', function(e) {
    if ($(window).scrollTop() === 0) {
        touchStartY = e.originalEvent.touches[0].clientY;
    }
});

$(document).on('touchmove', function(e) {
    if ($(window).scrollTop() === 0) {
        touchEndY = e.originalEvent.touches[0].clientY;
    }
});

$(document).on('touchend', function(e) {
    if ($(window).scrollTop() === 0 && (touchEndY - touchStartY) > requiredDistance) {
        $('#loader').show(); 

        setTimeout(function() {
            window.location.reload();
        }, 300); 
    }
});

  /// end reload page scrooll

  $('.search').click(function() {
    $('#searchBox').fadeIn(); // מציג את רובריקת החיפוש
});

$('#closeSearch').click(function(event) {
    event.stopPropagation(); // מונע את ההתפשטות של האירוע
    $('#searchBox').fadeOut(); // מסתיר את רובריקת החיפוש
});

  $('.open-menu-button').on('click', function() {
    $('body').css({
      overflow: 'hidden',
      height: '100vh'
    });
  });

  // כאשר סוגרים את התפריט הנפתח
  $('.close-menu-button').on('click', function() {
    $('body').css({
      overflow: '',
      height: ''
    });
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
    console.log('toggle');
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
      url: ajax_object.ajax_url,
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



