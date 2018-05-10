jQuery(function($) {

	// Shrink navbar on scroll
	
	$(document).scroll(function() {
		if ($(window).scrollTop() >= 200 && $(window).width() > 755) {
			$('body.home').css({'padding-top': '54px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default').css({'height': '80px','transition': 'height .3s'});
			$('nav#navbar-top.navbar-default .navbar-nav > li > a').css({'padding-top': '14px', 'padding-bottom': '13px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .navbar-header').css({'height': '80px','transition': 'height .3s'});
			$('nav#navbar-top.navbar-default .navbar-nav > li.nav-search > a').css({'padding-top': '10px', 'padding-bottom': '13px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .nav li .dropdown-menu').css({'top': '12px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .brand img').css({'margin-top': '20px', 'padding-bottom': '14px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .search-box').css({'padding-top': '0px','height' : '58%','transition': 'padding .3s'});
			$('nav.page-nav.navbar-fixed-top').css({'top': '78px'});
		} else if ($(window).scrollTop() <= 200 && $(window).width() > 755) {
			$('body.home').css({'padding-top': '96px','transition': 'padding .3s'});
			$('body.page').css({'padding-top': '96px','transition': 'padding .3s'});
			$('body.page-template-page-listing').css({'padding-top': '146px','transition': 'padding .3s'});
			$('body.single-product').css({'padding-top': '146px'});
			$('nav#navbar-top.navbar-default').css({'height': '114px','transition': 'height .3s'});
			$('nav#navbar-top.navbar-default .navbar-nav > li > a').css({'padding-top': '31px', 'padding-bottom': '31px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .navbar-header').css({'height': '96px','transition': 'height .3s'});
			$('nav#navbar-top.navbar-default .navbar-nav > li.nav-search > a').css({'padding-bottom': '32px', 'padding-top': '28px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .nav li .dropdown-menu').css({'top': '49px','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .brand img').css({'margin-top': '35px', 'padding-bottom': '0','transition': 'padding .3s'});
			$('nav#navbar-top.navbar-default .search-box').css({'padding-top': '','transition': 'padding .3s'});
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
	$(function(){
	  var $container = $('.item-list'),
	      filters = {};
	
	  $container.isotope({
	    itemSelector : '.item',
	    percentPosition: true,
	    transitionDuration: 0
	  });
	  	  
	  $('.filters-select').change(function(){
	    var $this = $(this);
	    
	    // store filter value in object
	    var group = $this.attr('data-filter-group');
	    
	    filters[ group ] = $this.find(':selected').attr('data-filter-value');

	    // convert object into array
	    var isoFilters = [];
	    for ( var prop in filters ) {
	      isoFilters.push( filters[ prop ] )
	    }
	    console.log(filters);
	    var selector = isoFilters.join('');
	    $container.isotope({ filter: selector });
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


	//customize the "Our Range" custom link at Main Menu
	$('#navbar-top .main-navi-panel .our-range-class.menu-item').click(function()
	{
		if($('#navbar-top .main-navi-panel .our-range-class.menu-item').hasClass('active'))
		{
            $('#navbar-top .main-navi-panel li.menu-item').removeClass('active');
		}
		else
		{
            $('#navbar-top .main-navi-panel li.menu-item').removeClass('active');
            $('#navbar-top .main-navi-panel .our-range-class.menu-item').addClass('active');
		}

        $('#navbar-top .main-navi-panel .our-range-class.menu-item ul.dropdown-menu').hide();
		$('.products-navigation').toggle();

	});



});
