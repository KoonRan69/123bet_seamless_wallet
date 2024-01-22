'use strict';

$(document).ready(function () {

    $(document).on('click', '.rmenu .dropdown', function (e) {
        e.preventDefault();
        if ($(this).parent()[0].id === 'account') {
            $(this).parent().toggleClass('open').find('.signin input:first').focus();
        }
        else if ($(this).parent()[0].id === 'search') {
            $(this).parent().toggleClass('open').find('.search input:first').focus();
        }
    });

    var account = $('#account');
    var search = $('#search');
    var signin = $('.signin');
    var signup = $('.signup');
    var recover = $('.recover');

    $(document).mouseup(function (e) {
        if (!account.is(e.target) && account.has(e.target).length === 0) {
            account.removeClass('open');
        }
        if (!search.is(e.target) && search.has(e.target).length === 0) {
            search.removeClass('open');
        }
    });

    $(document).on('click', '#SignUp', function (e) {
        e.preventDefault();
        signin.hide();
        signup.show();
        account.find('.signup input:first').focus();
    });

    $(document).on('click', '#SignIn', function (e) {
        e.preventDefault();
        signin.show();
        signup.hide();
        account.find('.signin input:first').focus();
    });

    $(document).on('click', '#Recover', function (e) {
        e.preventDefault();
        signin.hide();
        recover.show();
        account.find('.recover input:first').focus();
    });

    $(document).on('click', '#RecoverCancel', function (e) {
        e.preventDefault();
        signin.show();
        recover.hide();
        account.find('.signin input:first').focus();
    });

    var RangeSlider = $('.RangeSlider');
    if (RangeSlider.length) {
        RangeSlider.ionRangeSlider({
            type: 'double',
            min: 0,
            max: 1000,
            from: 100,
            to: 900,
            prefix: '$',
            onChange: function (data) {
                $('.RangeText').text('$' + data.from + ' - $' + data.to).val(data.from + ',' + data.to);
                $('.RangeValue').val(data.from + ',' + data.to);
            },
            onFinish: function (data) {
                $('.RangeText').text('$' + data.from + ' - $' + data.to).val(data.from + ',' + data.to);
                $('.RangeValue').val(data.from + ',' + data.to);
            }
        });
    }

    var barrating = $('.barrating');
    if (barrating.length) {
        barrating.barrating({
            theme: 'fontawesome-stars',
            showSelectedRating: false
        });
    }
    
    var filter = $('.filter');
    if (filter.length) {
        var $grid = $('.grid').isotope({});
        $('.grid img').bind('load', function () {
            $grid.isotope('layout');
        });
        filter.on('click', 'li', function () {
            $('.filter li').removeClass('active');
            $(this).addClass('active');
            $grid.isotope({ filter: $(this).data('filter') });
        });
    }

    var masonry = $('.masonry');
    if (masonry.length) {
        var $grid = masonry.isotope({});
        $('.masonry img').bind('load', function () {
            $grid.isotope('layout');
        });
    }

    var carousel = $('.carousel');
    if (carousel.length) {
        carousel.each(function () {
            $(this).owlCarousel({
                items: $(this).data('items') ? $(this).data('items') : 4,
                autoPlay: $(this).data('autoplay') ? $(this).data('autoplay') : 2500,
                itemsDesktop: [1199, 3],
                itemsDesktopSmall: [979, 2],
                itemsTablet: [500, 1],
                pagination: $(this).data('pagination') ? $(this).data('pagination') : false,
                navigation: $(this).data('navigation') ? $(this).data('navigation') : true,
                navigationText: ['<i class="fa fa-caret-left"></i>', '<i class="fa fa-caret-right"></i>']
            });
        });
    }
	
    var popuplink = $('.image-popup-link');
    if (popuplink.length) {
        popuplink.magnificPopup({ type: 'image', zoom: { enabled: true, duration: 300, easing: 'ease-in-out' } });
    }

    var imagelink = $('.image-link');
    if (imagelink.length) {
        imagelink.magnificPopup({ type: 'image' });
    }

    var popuplist = $('.image-popup-list');
    if (popuplist.length) {
        popuplist.magnificPopup({ type: 'image', delegate: 'a', gallery: { enabled: true }, zoom: { enabled: true, duration: 300, easing: 'ease-in-out' } });
    }

    var mcustomscrollbar = $('.mcustomscrollbar');
    if (mcustomscrollbar.length) {
        mcustomscrollbar.each(function () {
            $(this).css({ 'height': $(this).data('height') ? $(this).data('height') : 300 + 'px' }).mCustomScrollbar({
                theme: 'minimal-dark',
                mouseWheelPixels: 15,
                scrollInertia: 0,
                axis: 'y'
            });
        });
    }

    var masterslider = $('#masterslider');
    if (masterslider.length) {
        var slider = new MasterSlider();
        slider.control('arrows', { insertTo: '#masterslider' });
        slider.control('bullets');
        slider.setup('masterslider', {
            width: 1170, // slider standard width
            height: 800, // slider standard height
            space: 0,
            speed: 50,
            layout: "fullwidth",
            centerControls: false,
            loop: true,
            //autoplay: true
        });
    }


    // Services Slider
    var sliderservices = $('#slider-services');
    if (sliderservices.length) {
        var slider = new MasterSlider();
        slider.control('bullets', { autohide: false, align: 'bottom', margin: 10 });

        slider.setup('slider-services', {
            width: 480,
            height: 855,
            layout: 'partialview',
            space: 2,
            view: 'basic',
            loop: true,
            speed: 40
        });
    }

    var mastergallery = $('#mastergallery');
    if (mastergallery.length) {
        var slider = new MasterSlider();
        slider.control('arrows');
        slider.control('thumblist', { autohide: false, dir: 'h', arrows: false, align: 'bottom', width: 176, height: 100, margin: 15, space: 15 });
        slider.setup('mastergallery', {
            width: 1024,
            height: 500,
            space: 5
        });
    }

    var mastershop = $('#mastershop');
    if (mastershop.length) {
        var slider = new MasterSlider();
        slider.control('arrows');
        slider.control('thumblist', { autohide: false, dir: 'h', arrows: false, align: 'bottom', width: 104, height: 104, margin: 15, space: 15 });
        slider.setup('mastershop', {
            width: 1024,
            height: 1024,
            space: 5
        });
    }

    if ($('ul.nav-pills').length) {
        pageLoad();
    }
    function pageLoad() {
        var loc = window.location.href.split('/');
        var page = loc[loc.length - 1];
        $('ul.nav-pills a').each(function (i) {
            if ($(this).attr('href').indexOf(page) !== -1 && page !== '') {
                $('ul.nav-pills li.active').removeClass('active');
                Item($(this).parent('li'));
            }
        });
        $(document).on('click', 'ul.nav-pills .dropdown-toggle, ul.nav-pills .dropdown-submenu > a', function (e) {
            e.preventDefault();
        });
    }
    function Item(li) {
        if (li.length) {
            li.addClass('active');
            Item(li.parent('ul').parent('li'));
        }
    }

    var pagetop = $('.pagetop');
    if (pagetop.length) {
        var uzunluk = $(window).height();
        $(window).scroll(function () {
            var scroll = $(this).scrollTop();
            if (scroll > uzunluk) {
                pagetop.show();
            }
            else {
                pagetop.hide();
            }
        });
        $(document).on('click', '.pagetop', function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 1000);
        });
    }

    $(document).on('click', '.panel-heading a', function () {
        $('.panel-heading a i').addClass('fa-chevron-down');
        if ($(this).hasClass('collapsed') === true) {
            $(this).children('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        }
        else {
            $(this).children('i').removeClass('fa-chevron-up');
        }
    });

    $(document).on('click', '.quantity .quantity-plus', function () {
        var input = $(this).parent().children('input');
        input.val(parseInt(input.val(), 10) + 1);
    });
    $(document).on('click', '.quantity .quantity-minus', function () {
        var input = $(this).parent().children('input');
        if (parseInt(input.val(), 10) > 1) {
            input.val(parseInt(input.val(), 10) - 1);
        }
    });

});