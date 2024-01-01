(function ($) {
    "use strict";
    // Dropdown on mouse hover
    $(document).ready(function () {
        function toggleNavbarMethod() {
            if ($(window).width() > 992) {
                $('.navbar .dropdown').on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                }).on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }
        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Vendor carousel
    $('.vendor-carousel').owlCarousel({
        loop: true,
        margin: 29,
        nav: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0:{
                items:2
            },
            576:{
                items:3
            },
            768:{
                items:4
            },
            992:{
                items:5
            },
            1200:{
                items:6
            }
        }
    });


    // Related carousel
    $('.related-carousel').owlCarousel({
        loop: true,
        margin: 29,
        nav: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:2
            },
            768:{
                items:3
            },
            992:{
                items:4
            }
        }
    });


    // Product Quantity
    $('.quantity button').on('click', function () {
        var button = $(this);
        var oldValue = button.parent().parent().find('input').val();
        if (button.hasClass('btn-plus')) {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        button.parent().parent().find('input').val(newVal);
    });
    
})(jQuery);

// search

jQuery(document).ready(function($) {
    var typingTimer;
    var searchForm = $('#searchForm');
    var searchResults = $('#searchResults');
    var doneTypingInterval = 500;
    $('input[name="q"]').on('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(performSearch, doneTypingInterval);
    });
    $('input[name="q"]').on('keydown', function () {
        clearTimeout(typingTimer);
    });
    function hideSearchResults() {
        searchResults.empty().hide();
    }
    function performSearch() {
        var query = $('input[name="q"]').val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: 'GET',
            url: '/search',
            data: { q: query, _token: csrfToken },
            success: function (data) {
                searchResults.empty();
                if (data.length > 0) {
                    console.log(data);
                    data.forEach(function (result) {
                        var listItem = $('<div class=" w-100 shadow-lg p-2 mb-2 bg-body rounded cursor-pointer top-0 start-50 translate-middle-x">')
                                        .text(result.name)
                                        .attr('data-id', result.id)
                                        searchResults.append(listItem);
                    });
                    searchResults.on('click', 'div', function () {
                        var productId = $(this).attr('data-id');
                        window.location.href = '/product-detail/' + productId;
                    });
                    searchResults.show();
                } else {
                    searchResults.append('<p class="w-100 shadow-lg p-3 mb-5 bg-body rounded cursor-pointer translate-middle-x">Không tìm thấy sản phẩm nào!!</p>');
                }
            },
            error: function (error) {
                console.log('Error:', error);
            }
        });
    }
    $(document).on('click', function (event) {
        if (!searchForm.is(event.target) && searchForm.has(event.target).length === 0 &&
            !searchResults.is(event.target) && searchResults.has(event.target).length === 0) {
            hideSearchResults();
        }
    });
});
//sort by price
$(document).ready(function () {
    var products = $('#productList').children();

    $('#priceFilterForm input[type="checkbox"]').change(function () {
        filterProducts();
    });

    function filterProducts() {
        var filteredProducts = products.filter(function () {
            var price = parseFloat($(this).data('price'));

            if ($('#price-all').is(':checked')) {
                return true;
            } else if ($('#price-1').is(':checked') && price > 100000) {
                return true;
            } else if ($('#price-2').is(':checked') && price < 100000) {
                return true;
            }

            return false;
        });
        products.hide();
        filteredProducts.show();
    }
});
