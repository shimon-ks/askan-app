jQuery(document).ready(function($) {


  $('.cat-list #category-select').change(function() {
    var expoToken = localStorage.getItem('expoToken');
    // var expoToken = '7b4f0885-e733-4590-89da-d78a49e4de35';

    var  checkboxValue = $(this).val();
    var isChecked = $(this).is(':checked'); 

      $.ajax({
        url: ajax_object.ajax_url, 
        type: 'POST',
        data: {
            action: 'set_user_category', 
            expoToken: expoToken,
            checkboxData: checkboxValue,
            isActive: isChecked
        },
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

  var userId = localStorage.getItem('expoToken'); 
  // var userId = '7b4f0885-e733-4590-89da-d78a49e4de35'; 
  if (userId) {
      $.ajax({
        url: ajax_object.ajax_url, 
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'get_user_category', // שם הפעולה שהגדרת ב- add_action
            userId: userId
        },
        success: function(response) {
          console.log(response);
            response.forEach(function(category) {
                $("input[type=checkbox][value='" + category + "']").prop('checked', true);
            });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
  }

});

function get_user_categories() {
    var userId = localStorage.getItem('expoToken'); 
    // var userId = '7b4f0885-e733-4590-89da-d78a49e4de35'; 
    if (userId) {
        $.ajax({
          url: ajax_object.ajax_url, 
          type: 'POST',
          dataType: 'json',
          data: {
              action: 'get_user_category', // שם הפעולה שהגדרת ב- add_action
              userId: userId
          },
          success: function(response) {
            return response;
          },
          error: function(xhr, status, error) {
              console.error(error);
          }
      });
    }
}

