jQuery(function($) {

	// Shrink navbar on scroll
	
	$(document).scroll(function() {
		if ($(window).scrollTop() >= 200 && $(window).width() > 755 &&  $(window).width() != 768) {
			$('body.home').css({'padding-top': '54px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default').css({'height': '80px','transition': 'height .3s'});
			$('nav#navbar-top.navbar-default .navbar-nav > li > a').css({'padding-top': '14px', 'padding-bottom': '13px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .navbar-header').css({'height': '80px','transition': 'height .3s'});
			$('nav#navbar-top.navbar-default .navbar-nav > li.nav-search > a').css({'padding-top': '15px', 'padding-bottom': '13px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .nav li .dropdown-menu').css({'top': '12px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .brand img').css({'margin-top': '0px', 'padding-bottom': '14px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .search-box').css({'padding-top': '0px','height' : '58%','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .search-box input[type="text"]').css({'padding-bottom' : '13px'});
			$('nav.page-nav.navbar-fixed-top').css({'top': '75px'});
		} else if ($(window).scrollTop() <= 200 && $(window).width() > 755 && $(window).width() != 768) {
			$('body.home').css({'padding-top': '96px','transition': 'padding .3s'});
			$('body.page').css({'padding-top': '96px','transition': 'padding .3s'});
			$('body.page-template-page-listing').css({'padding-top': '114px','transition': 'padding .3s'});
			$('body.single-product').css({'padding-top': '146px'});
			$('nav#navbar-top.navbar-default').css({'height': '114px','transition': 'height .3s'});
			$('nav#navbar-top.navbar-default .navbar-nav > li > a').css({'padding-top': '31px', 'padding-bottom': '31px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .navbar-header').css({'height': '96px','transition': 'height .3s'});
			$('nav#navbar-top.navbar-default .navbar-nav > li.nav-search > a').css({'padding-bottom': '34px', 'padding-top': '28px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .nav li .dropdown-menu').css({'top': '49px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .brand img').css({'margin-top': '20px', 'padding-bottom': '0','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .search-box').css({'padding-top': '','transition': 'padding .3s','height' : 'auto'});
            $('nav#navbar-top.navbar-default .search-box input[type="text"]').css({'padding-bottom' : '25px'});
			$('nav.page-nav.navbar-fixed-top').css({'top': '116px','transition': 'padding .3s'});
		} else if ($(window).scrollTop() && $(window).width() < 755) {
			$('body').css({'padding-top': '74px','transition': 'padding .3s'});
		}
	});
	

	// Product Specifications Tabs
	$('#specificationTabs').responsiveTabs({
	    startCollapsed: 'accordion'
	});
	
	
	// Product Gallery
	$('.flexslider').flexslider({
		slideshow: false,
		animation: "slide",
		animationLoop: true,
		itemWidth: 300,
		itemMargin: 2,
		maxItems: 3,
		controlNav: false
	});
	
	
	// Isotope Filtering
	$(function() {
        var $container = $('.item-list'),
            filters = {};

        $container.isotope({
            itemSelector: '.item',
            percentPosition: true,
            transitionDuration: 0
        });

        $('.filters-select').change(function () {
            var $this = $(this);

            // store filter value in object
            var group = $this.attr('data-filter-group');

            filters[group] = $this.find(':selected').attr('data-filter-value');

            // convert object into array
            var isoFilters = [];
            for (var prop in filters) {
                isoFilters.push(filters[prop])
            }

            var selector = isoFilters.join('');
            $container.isotope({filter: selector});
            return false;
        });


    });
	
	// Search box
	
	// show
	$('.nav-search.hidden-xs').click(function(e) {
		e.stopPropagation();

		if($('.nav-search.hidden-xs').is(':visible')){
            $('.search-box').css({'padding-top': '','height': '' });

            var menu_width =$('.main-navi-panel ul.navbar-nav').width() - $('.main-navi-panel ul.navbar-nav li.ico-search').width();
            $('.search-box').css({'width' : menu_width, 'right' :  $('.main-navi-panel ul.navbar-nav li.ico-search').width() + 13 });

			$('.search-box').toggle('slide', {
			    direction: 'right',
			    easing: 'linear'
			}, 500);
			
			$('.search-box input[type=text]').focus();
			$(this).toggleClass('ico-close');
			
			$('.searchwp-live-search-results-showing').toggle();


		} else {
			$('.search-box').toggle('slide', {
			    direction: 'left'
			}, 500);
		}
		
	});

	// Hide when click detected outside search box
	$('body').click(function(e) {
	
		if($('.search-box').is(':visible')){
			$('.search-box').toggle('slide', {
			    direction: 'right'
			}, 500);
			
			$('.nav-search.hidden-xs').removeClass('ico-close');
		}
			
	});
	
	$('.search-box').click(function(e) {
		e.stopPropagation();
	});

	// Expand dealer sidebar details on click
	$(document).on('click', '.results_wrapper', function() {

		$(this).find('.details-hidden').toggle();
		$(this).siblings('div').find('.details-hidden').hide();

		$(this).find('.results_entry').toggleClass('arrow-down');
		$(this).siblings('div').find('.results_entry').removeClass('arrow-down').addClass('arrow-right');
		
	});

	// Load dealer details into enquiry forms
	$(document).on('click', '.info_bubble button', function() {
		var DealerName = $('#slp_bubble_name').text();
		var DealerEmail = $('.info_bubble .hidden-form-email').html();
		$('#field_dealer_name').val(DealerName);
		$('#field_dealer_email').val(DealerEmail);
		$('.modal-title').text(DealerName)
	});
	$(document).on('click', '.results_wrapper', function() {
		var DealerName = $('.location_name', this).text();
		var DealerEmail = $('.hidden-form-email', this).html();
		$('#field_dealer_name').val(DealerName);
		$('#field_dealer_email').val(DealerEmail);
		$('.modal-title').text(DealerName)
	});

	// Hide dealer sidebar toggle
	$('.hide-sidebar').click(function(e) {

		e.stopPropagation();

		$('#map_sidebar_cont').toggle('slide', {
			direction: 'left',
			easing: 'linear'
		}, 200);

		$('.map-screen').toggleClass('col-sm-8 col-md-9');
		$('#map.slp_map').animate({width:'100%'}, 200);
		google.maps.event.trigger(map, 'resize');

		if ($('.hide-sidebar span .lbl').text() == 'Hide') {
			$('.hide-sidebar span .lbl').text('Show');
			$('.hide-sidebar span .glyphicon').removeClass('glyphicon-triangle-left').addClass('glyphicon-triangle-right');
		} else {
			$('.hide-sidebar span .lbl').text('Hide');
			$('.hide-sidebar span .glyphicon').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-left');			
		}

	});

	$('#addressInputState').on('change', function() {

		var stateSelected = $('#addressInputState').children('option').filter(':selected').text();

		if (stateSelected != "All States") {
			$('#addy_in_radius').hide();
			$('#addy_in_address').hide();
		}

		if (stateSelected == "All States") {
			$('#addy_in_radius').show();
			$('#addy_in_address').show();
		}

	});

	
	// smooth scroll
	$(function() {
		$('a[href*="#"]:not([href="#"])').click(function() {
			if ($(this).hasClass('r-tabs-anchor')) {
			} else {
				if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
					var target = $(this.hash);
					target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
					if (target.length) {
						$('html, body').animate({
							scrollTop: target.offset().top - 130
					}, 1000);
					return false;
					}
				}
			}
		});
	});



	//customize the main menu style
    $('#navbar-top .main-navi-panel li.menu-item').click(function(){
        //other main menu link remove up arrow
        $('#navbar-top .main-navi-panel li.menu-item span.caret').removeClass('up-arrow');

        //customize the "Our Range" custom link at Main Menu
    	if($(this).hasClass('our-range-class'))
		{
            if($('#navbar-top .main-navi-panel .our-range-class.menu-item').hasClass('active'))
            {
                $('#navbar-top .main-navi-panel li.menu-item.our-range-class').removeClass('active');
                $('body').removeClass('no-scroll');
                $(this).find('span.caret').removeClass('up-arrow');
                $('.products-navigation').removeClass('show-nav');
            }
            else
            {

                $('#navbar-top .main-navi-panel li.menu-item.our-range-class').addClass('active');
                $('body').addClass('no-scroll');
                $(this).find('span.caret').addClass('up-arrow');
                $('.products-navigation').addClass('show-nav');

            }

            $('#navbar-top .main-navi-panel li.menu-item.our-range-class ul.dropdown-menu').hide();

		}
		else
		{
			//hide the product navigation of custom link "our range"
            $('.products-navigation').removeClass('show-nav');
            $('#navbar-top .main-navi-panel li.menu-item.our-range-class').removeClass('active');

			//this clicked menu link will have up arrow or not
			//this link drop donw open then should add up-arrow icon
            if($(this).find('a').attr('aria-expanded') == 'true')
            {
                $(this).find('span.caret').removeClass('up-arrow');
            }
            else
            {
            	//if not, then remove the up arrow icon
                $(this).find('span.caret').addClass('up-arrow');
            }
		}


	});

    $('.all-caravans-menu a.caravans-header').click(function() {

    	//resize the caravan images and detail at menu
		if($(window).width() <= 767 )
		{
            $('.products-navigation .product-list .product-list-item .item-img img').css({'width': $('.navbar-default .navbar-header .brand').width()});
        }


        if($(this).hasClass('active'))
        {
            $(this).find('span.caret').removeClass('up-arrow');
            $(this).removeClass('active');
            $('body').removeClass('no-scroll');
            $('.products-navigation').removeClass('show-nav');
        }
        else {
            $(this).addClass('active');
            $('body').addClass('no-scroll');
            $(this).find('span.caret').addClass('up-arrow');
            $('.products-navigation').addClass('show-nav');
		}

		//close other menu
        $('nav#navbar-top-mob  .navbar-collapse').removeClass('mobile-active');
        $('nav#navbar-top-mob .navbar-collapse:not(.mobile-active)').stop().animate({
            right: '-100%'
        });
        $('.single-product .page-nav.navbar-fixed-top input[type="checkbox"]').prop('checked', false);
    });

	//customize for top mobile menu
    $(document).ready(function ($) {

        $('nav#navbar-top-mob button.navbar-toggle').on('click', function (e) {
            event.stopPropagation();
            var selected = $('nav#navbar-top-mob  .navbar-collapse').hasClass('mobile-active');
            $('nav#navbar-top-mob  .navbar-collapse').toggleClass('mobile-active', !selected);

            $('nav#navbar-top-mob .navbar-collapse:not(.mobile-active)').stop().animate({
                right: '-100%'
            });

            $('nav#navbar-top-mob .navbar-collapse.mobile-active').stop().animate({
                right: '15px'
            });
            //close other menu
            $('.all-caravans-menu a.caravans-header').find('span.caret').removeClass('up-arrow');
            $('.all-caravans-menu a.caravans-header').removeClass('active');
            $('body').removeClass('no-scroll');
            $('.products-navigation').removeClass('show-nav');

            $('.single-product .page-nav.navbar-fixed-top input[type="checkbox"]').prop('checked', false);
        });

        //customize the product menu button
        $('.single-product .page-nav.navbar-fixed-top input[type="checkbox"]').click(function(e){

            $('.all-caravans-menu a.caravans-header').find('span.caret').removeClass('up-arrow');
            $('.all-caravans-menu a.caravans-header').removeClass('active');
            $('body').removeClass('no-scroll');
            $('.products-navigation').removeClass('show-nav');
        });
    });

});
