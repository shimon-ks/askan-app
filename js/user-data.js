jQuery(document).ready(function($) {

    const debug = false;
    var userId = localStorage.getItem('userId');

    // function to get userId from localStorage
    function getUserIdFromLocalStorage() {
        return localStorage.getItem('userId');
    }

    // Listen for messages from React Native
    window.addEventListener('message', function(event) {
        // alert('Received message from React Native:' + event.data);
        if (event.data) {
            const data = JSON.parse(event.data);
            if (data.userId) {
                localStorage.setItem('userId', data.userId);
                // alert('Received userId from React Native:' + data.userId);
                
                // Perform necessary actions after receiving userId
                // registerUserIfNew();
                initEntryAdModal();
                initPostLinkClickHandler();
                initialLoad();
            }
        }
    });

    function initEntryAdModal() {
        var modal = $('#adModal');
        var span = $('.close');

        $(window).on('load', function() {
            modal.css('display', 'block');
            startCountdown();
        });

        span.on('click', function() {
            modal.css('display', 'none');
        });

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
            // alert('post-link');
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

    if (debug) {
        userId = 'cc103ebf-0b1f-4cd6-82e0-1ed99803a143';
        alert('Debug userId:' + userId);
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
                moraleChecked: moraleChecked,
                userId: token
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
                alert('Error loading posts: ' + error);
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
                    alert('No more posts found.');
                }
            },
            error: function(xhr, status, error) {
                alert('Error loading search results: ' + error);
            }
        });
    }

    function initialLoad() {
        if (isSearchPage()) {
            var searchQuery = new URLSearchParams(window.location.search).get('s');
            loadSearchResults(currentPage, searchQuery);
        } else {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'get_user_preferences',
                    userId: userId
                },
                success: function(response) {
                    var preferences = response;
                    var categoriesToLoad = categoryFromURL ? [categoryFromURL] : preferences.categories;
                    // alert('Preferences: ' + JSON.stringify(preferences));
                    loadPosts(categoriesToLoad, preferences.sites, currentPage, userId, preferences.moraleChecked);
                    markSelectedCheckboxes(preferences);
                },
                error: function(xhr, status, error) {
                    alert('Error loading user preferences: ' + error);
                }
            });
        }
    }

    function isSearchPage() {
        return new URLSearchParams(window.location.search).has('s');
    }

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
            currentPage++;
            if (isSearchPage()) {
                var searchQuery = new URLSearchParams(window.location.search).get('s');
                loadSearchResults(currentPage, searchQuery);
            } else {
                $.ajax({
                    url: ajax_object.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'get_user_preferences',
                        userId: userId
                    },
                    success: function(response) {
                        var preferences = response;
                        var categoriesToLoad = categoryFromURL ? [categoryFromURL] : preferences.categories;

                        loadPosts(categoriesToLoad, preferences.sites, currentPage, userId, preferences.moraleChecked);
                    },
                    error: function(xhr, status, error) {
                        alert('Error loading user preferences on scroll: ' + error);
                    }
                });
            }
        }
    });

    $('.cat-list #category-select').change(function() {
        var itemId = $(this).val();
        var actionType = $(this).is(':checked') ? 'add' : 'remove';

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'set_user_category',
                userId: userId,
                categoryId: itemId,
                actionType: actionType
            },
            success: function(response) {
                var preferences = response;
                var categoriesToLoad = categoryFromURL ? [categoryFromURL] : preferences.categories;
                loadPosts(categoriesToLoad, preferences.sites, 1, userId, preferences.moraleChecked);
                markSelectedCheckboxes(preferences);
            },
            error: function(xhr, status, error) {
                alert('Error setting user category: ' + error);
            }
        });
    });

    $('.sites-slider .site-select').change(function() {
        var itemId = $(this).val();
        var actionType = $(this).is(':checked') ? 'add' : 'remove';

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'set_user_site',
                userId: userId,
                siteId: itemId,
                actionType: actionType
            },
            success: function(response) {
                var preferences = response;
                var categoriesToLoad = categoryFromURL ? [categoryFromURL] : preferences.categories;
                loadPosts(categoriesToLoad, preferences.sites, 1, userId, preferences.moraleChecked);
                markSelectedCheckboxes(preferences);
            },
            error: function(xhr, status, error) {
                alert('Error setting user site: ' + error);
            }
        });
    });

    $('#morale').change(function() {
        var moraleChecked = $(this).is(':checked') ? 'moralechecked' : 'moralenochecked';

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'morale_chacked',
                userId: userId,
                moraleChecked: moraleChecked
            },
            success: function(response) {
                $.ajax({
                    url: ajax_object.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'get_user_preferences',
                        userId: userId
                    },
                    success: function(response) {
                        var preferences = response;
                        var categoriesToLoad = categoryFromURL ? [categoryFromURL] : preferences.categories;
                        loadPosts(categoriesToLoad, preferences.sites, 1, userId, preferences.moraleChecked);
                        markSelectedCheckboxes(preferences);
                    },
                    error: function(xhr, status, error) {
                        alert('Error loading user preferences after morale check: ' + error);
                    }
                });
            },
            error: function(xhr, status, error) {
                alert('Error setting morale check: ' + error);
            }
        });
    });

    function markSelectedCheckboxes(preferences) {
        // alert('Marking selected checkboxes, preferences: ' + JSON.stringify(preferences));

        if (preferences && preferences.data && preferences.data.categories) {
            preferences.data.categories.forEach(function(encodedCategory) {
                var category = decodeURIComponent(encodedCategory);
                $(".cat-list #category-select[value='" + category + "']").prop('checked', true);
            });
        }

        if (preferences && preferences.data && preferences.data.sites) {
            preferences.data.sites.forEach(function(site) {
                $(".sites-slider .site-select[value='" + site + "']").prop('checked', true);
            });
        }

        if (preferences && preferences.data && preferences.data.moraleChecked === "moralechecked") {
            $('#morale').prop('checked', true);
        } else {
            $('#morale').prop('checked', false);
        }
    }

    function registerUserIfNew() {
        userId = localStorage.getItem('userId');
        if (debug) {
            userId = 'cc103ebf-0b1f-4cd6-82e0-1ed99803a143';
            alert('Debug userId:' + userId);
        }

        if (!userId) {
            // alert('No user ID found, waiting...');
            setTimeout(registerUserIfNew, 1000);
            return;
        }

        registerUserForAllCategoriesAndSites();
    }

    function registerUserForAllCategoriesAndSites() {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'registerUserForAllCategoriesAndSites',
                userId: userId
            },
            success: function(response) {
                // alert('User registered for all categories and sites.');
            },
            error: function(xhr, status, error) {
                alert('Error registering user for all categories and sites: ' + error);
            }
        });
    }
});