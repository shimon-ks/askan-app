





jQuery(document).ready(function($) {

    const debug = true;

    // registerUserIfNew();
    markSelectedCheckboxes();
    initEntryAdModal();
    initPostLinkClickHandler();


    function markSelectedCheckboxes() {
        var savedCategories = localStorage.getItem('userCategories') ? JSON.parse(localStorage.getItem('userCategories')) : [];
        var savedSites = localStorage.getItem('userSites') ? JSON.parse(localStorage.getItem('userSites')) : [];

        savedCategories.forEach(function(category) {
            $(".cat-list #category-select[value='" + category + "']").prop('checked', true);
        });

        savedSites.forEach(function(site) {
            $(".sites-slider .site-select[value='" + site + "']").prop('checked', true);
        });
    }

    function initEntryAdModal() {
        // Get the modal
        var modal = $('#adModal');
    
        // Get the <span> element that closes the modal
        var span = $('.close');
    
        // Open the modal on page load
        $(window).on('load', function() {
        modal.css('display', 'block');
        startCountdown();
        });
    
        // Close the modal when clicking on <span> (x)
        span.on('click', function() {
        modal.css('display', 'none');
        });
    
        // Start the countdown timer
        function startCountdown() {
        var count = 5;
        var countdown = setInterval(function() {
            count--;
            $('#countdown').text(count);
            if (count <= 0) {
            clearInterval(countdown);
            modal.css('display', 'none');
            }
        }, 1000);
        }
    }
    
    function initPostLinkClickHandler() {
        $(document).on('click', '.post-link', function(e) {
        console.log('post-link');
        e.preventDefault();
        var postLink = $(this);
        var postId = postLink.parent().attr('data-post-id');
        var originalHref = postLink.attr('href');
    
        showPostLinkAdModal(originalHref);
        });
    }

    function reinitPostLinkClickHandler() {
        initPostLinkClickHandler();
      }
    
    function showPostLinkAdModal(originalHref) {
        var modal = $('#adModal');
    
        modal.css('display', 'block');
        startCountdown();
    
        setTimeout(function() {
        window.location.href = originalHref;
        }, 5000);
    
        function startCountdown() {
        var count = 5;
        var countdown = setInterval(function() {
            count--;
            $('#countdown').text(count);
            if (count <= 0) {
            clearInterval(countdown);
            modal.css('display', 'none');
            }
        }, 1000);
        }
    }


    var currentPage = 1;
    var categoryFromURL = getCategoryFromPath();
    var savedCategories = localStorage.getItem('userCategories') ? JSON.parse(localStorage.getItem('userCategories')) : [];
    var savedSites = localStorage.getItem('userSites') ? JSON.parse(localStorage.getItem('userSites')) : [];
    var expoToken = localStorage.getItem('expoToken'); // אחזור expoToken מה-LocalStorage
    if (debug) {
        expoToken = 'cc103ebf-0b1f-4cd6-82e0-1ed99803a143'; 
        console.log('expoToken', expoToken);
    }
    // console.log('expoToken', expoToken);
    var moraleChecked = localStorage.getItem('moraleChecked') ? localStorage.getItem('moraleChecked') : 'moralenochacked';
    if (moraleChecked === "moralechecked") {
        $('#morale').prop('checked', true);
    } 


    function getCategoryFromPath(url = window.location.href) {
        var matches = url.match(/\/category\/([^\/]+)\//);
        return matches ? decodeURIComponent(matches[1]) : null;
    }

    function loadPosts(categories, sites, page, token, moraleChecked = 'moralenochacked') {

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            dataType: 'html', 
            data: {
                action: 'load_posts_by_preferences',
                categories: categories,
                sites: sites,
                page: page,
                moraleChecked : moraleChecked,
                expoToken: token // שליחת expoToken כחלק מהנתונים
            },
            success: function(response) {
                if (page === 1) {
                    $('.posts').html(response);
                    initPostLinkClickHandler(); 
                } else {
                    $('.posts').append(response);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function loadSearchResults(page, query) {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            dataType: 'html', 
            data: {
                action: 'load_search_results',
                s: query,
                page: page
            },
            success: function(response) {
                if (response) {
                    $('.posts').append(response);
                    initPostLinkClickHandler();
                } else {
                    console.log('No more posts found.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
        });
    }

    // console.log(moraleChecked);

    function initialLoad() {
        if (isSearchPage()) {
            // טען פוסטים לפי חיפוש
            var searchQuery = new URLSearchParams(window.location.search).get('s');
            loadSearchResults(currentPage, searchQuery);
        } else {
            // טען פוסטים רגילים לפי העדפות
            var categoriesToLoad = categoryFromURL ? [categoryFromURL] : savedCategories;
            loadPosts(categoriesToLoad, savedSites, currentPage, expoToken, moraleChecked);
        }
    }

    function isSearchPage() {
        // לדוגמה, בדיקה אם יש מחרוזת חיפוש ב-URL
        return new URLSearchParams(window.location.search).has('s');
    }

    initialLoad(); // טען פוסטים עם טעינת הדף

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
            currentPage++;
            if (isSearchPage()) {
                var searchQuery = new URLSearchParams(window.location.search).get('s');
                loadSearchResults(currentPage, searchQuery); // טען פוסטים מתוך תוצאות החיפוש
            } else {
                initialLoad(); // טען פוסטים רגילים לפי העדפות
            }
        }
    });
    

    $('.cat-list #category-select').change(function() {
        var itemId = $(this).val();
        var actionType = $(this).is(':checked') ? 'add' : 'remove';

        
        var expoToken = localStorage.getItem('expoToken'); 
        if (debug) {
            expoToken = 'cc103ebf-0b1f-4cd6-82e0-1ed99803a143'; 
            console.log('expoToken', expoToken);
        }


        console.log(expoToken);
        console.log(itemId);
        console.log(actionType);
    
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'set_user_category',
                expoToken: expoToken,
                categoryId: itemId,
                actionType: actionType
            },
            success: function(response) {
                console.log('Category ' + actionType + 'ed:', response);
                updateLocalStorage('category', itemId, actionType);
                var savedCategories = localStorage.getItem('userCategories') ? JSON.parse(localStorage.getItem('userCategories')) : [];
                var categoriesToLoad = categoryFromURL ? [categoryFromURL] : savedCategories;
                var savedSites = localStorage.getItem('userSites') ? JSON.parse(localStorage.getItem('userSites')) : [];
                loadPosts(categoriesToLoad, savedSites, 1, expoToken);
            },
            error: function(xhr, status, error) {
                console.error('Category ' + actionType + ' error:', error);
            }
        });
    });


    $('.sites-slider .site-select').change(function() {
        var itemId = $(this).val();
        var actionType = $(this).is(':checked') ? 'add' : 'remove';

        var expoToken = localStorage.getItem('expoToken'); 
        if (debug) {
            expoToken = 'cc103ebf-0b1f-4cd6-82e0-1ed99803a143'; 
            console.log('expoToken', expoToken);
        }
        // var expoToken = 'cc103ebf-0b1f-4cd6-82e0-1ed99803a143'; 


        // console.log(expoToken);
        // console.log(itemId);
        // console.log($(this).is(':checked'));
    
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'set_user_site',
                expoToken: expoToken,
                siteId: itemId,
                actionType: actionType
            },
            success: function(response) {
                console.log('Site ' + actionType + 'ed:', response);
                updateLocalStorage('site', itemId, actionType);

                var savedCategories = localStorage.getItem('userCategories') ? JSON.parse(localStorage.getItem('userCategories')) : [];
                var categoriesToLoad = categoryFromURL ? [categoryFromURL] : savedCategories;
                var savedSites = localStorage.getItem('userSites') ? JSON.parse(localStorage.getItem('userSites')) : [];
                loadPosts(categoriesToLoad, savedSites, 1, expoToken);
            },
            error: function(xhr, status, error) {
                console.error('Site ' + actionType + ' error:', error);
            }
        });
    });


    $('#morale').change(function() {

        var moraleChecked = $(this).is(':checked') ? 'moralechecked' : 'moralenochecked';
        
        var expoToken = localStorage.getItem('expoToken'); 
        if (debug) {
            expoToken = 'cc103ebf-0b1f-4cd6-82e0-1ed99803a143'; 
            console.log('expoToken', expoToken);
        }
        // var expoToken = 'cc103ebf-0b1f-4cd6-82e0-1ed99803a143'; 

    
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'morale_chacked',
                userId: expoToken,
                moraleChecked: moraleChecked,
            },
            success: function(response) {
                console.log('morale_chacked ' + moraleChecked + 'ed:', response);
                localStorage.setItem('moraleChecked', moraleChecked );

                var savedCategories = localStorage.getItem('userCategories') ? JSON.parse(localStorage.getItem('userCategories')) : [];
                var categoriesToLoad = categoryFromURL ? [categoryFromURL] : savedCategories;
                var savedSites = localStorage.getItem('userSites') ? JSON.parse(localStorage.getItem('userSites')) : [];
                loadPosts(categoriesToLoad, savedSites, 1, expoToken);
            },
            error: function(xhr, status, error) {
                console.log('morale_chacked ' + moraleChecked + 'ed:', error);
            }
        });
    });
    
    
    function updateLocalStorage(itemType, itemId, actionType) {
        var storageKey = 'user' + (itemType === 'category' ? 'Categories' : itemType.charAt(0).toUpperCase() + itemType.slice(1) + 's');
        var previousSelection;
    
        try {
            var storedValue = localStorage.getItem(storageKey);
            console.log('Stored value for', storageKey, ':', storedValue);
    
            // בדיקה אם הערך הוא null לפני ביצוע JSON.parse
            previousSelection = storedValue ? JSON.parse(storedValue) : [];
        } catch (e) {
            console.error('Error parsing JSON from localStorage for key:', storageKey, e);
            previousSelection = [];
        }
    
        console.log('Previous selection before update:', previousSelection);
    
        if (actionType === 'add') {
            if (!previousSelection.includes(itemId)) {
                previousSelection.push(itemId);
            }
        } else { // 'remove'
            previousSelection = previousSelection.filter(item => item !== itemId);
        }
    
        console.log('Updated selection after update:', previousSelection);
        localStorage.setItem(storageKey, JSON.stringify(previousSelection));
    }
    
    
     
    function registerUserIfNew() {
        const isNewUser = localStorage.getItem('new_user');
    
        if (isNewUser !== 'false') {
            expoToken = localStorage.getItem('expoToken');
            if (debug) {
                var expoToken = 'cc103ebf-0b1f-4cd6-82e0-1ed99803a143'; 
                console.log('expoToken', expoToken);
            }
    
            if (expoToken === 'null') {
                console.log('No token found, waiting...');
                setTimeout(registerUserIfNew, 1000); // המתנה שנייה אחת ואז קריאה חוזרת
                return;
            }
    
    
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'registerUserForAllCategoriesAndSites',
                    expoToken: expoToken,
                },
                success: function(response) {
                    const responseData = JSON.parse(response.data);
                    const categories = responseData.categories;
                    const sites = responseData.sites;
    
                    localStorage.setItem('userCategories', JSON.stringify(categories));
                    localStorage.setItem('userSites', JSON.stringify(sites));
    
                    markSelectedCheckboxes();
    
                    localStorage.setItem('new_user', 'false');
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
    }
    
    

});





