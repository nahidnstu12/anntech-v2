/*jshint jquery:true */

$(document).ready(function($) {
	"use strict";

	/* global google: false */
	/* global DevSolutionSkill: false */
	/*jshint -W018 */

	/*-------------------------------------------------*/
	/* =  portfolio isotope
	/*-------------------------------------------------*/

	var winDow = $(window);
		// Needed variables
		var $container=$('.iso-call');
		var $filter=$('.filter');

		try{
			$container.imagesLoaded( function(){
				$container.trigger('resize');
				$container.isotope({
					filter:'*',
					layoutMode:'masonry',
					animationOptions:{
						duration:750,
						easing:'linear'
					}
				});

				setTimeout(Resize, 1500);
			});
		} catch(err) {
		}

		winDow.on('resize', function(){
			var selector = $filter.find('a.active').attr('data-filter');

			try {
				$container.isotope({ 
					filter	: selector,
					animationOptions: {
						duration: 750,
						easing	: 'linear',
						queue	: false,
					}
				});
			} catch(err) {
			}
			return false;
		});
		
		// Isotope Filter 
		$filter.find('a').on('click', function(){
			var selector = $(this).attr('data-filter');

			try {
				$container.isotope({ 
					filter	: selector,
					animationOptions: {
						duration: 750,
						easing	: 'linear',
						queue	: false,
					}
				});
			} catch(err) {

			}
			return false;
		});


	var filterItemA	= $('.filter li a');

		filterItemA.on('click', function(){
			var $this = $(this);
			if ( !$this.hasClass('active')) {
				filterItemA.removeClass('active');
				$this.addClass('active');
			}
		});


	/*-------------------------------------------------*/
	/* =  nav animate
	/*-------------------------------------------------*/

	var OpenMenu = $('a.open-close-menu'),
		CloseMenu = $('a.close-menu'),
		MenuBox = $('.fixed-vertical-header');

	OpenMenu.on('click', function(event) {
		event.preventDefault();
		MenuBox.addClass('active');
		$('.fixed-header').addClass('active');
	});

	CloseMenu.on('click', function(event) {
		event.preventDefault();
		MenuBox.removeClass('active');
		$('.fixed-header').removeClass('active');
	});


	/*-------------------------------------------------*/
	/* =  search animate
	/*-------------------------------------------------*/

	var Opensearch = $('a.open-search'),
		Closesearch = $('a.close-search'),
		searchBox = $('.search-area');

	Opensearch.on('click', function(event) {
		event.preventDefault();
		searchBox.addClass('active');
	});

	Closesearch.on('click', function(event) {
		event.preventDefault();
		searchBox.removeClass('active');
	});

	/* ---------------------------------------------------------------------- */
	/*	magnific-popup
	/* ---------------------------------------------------------------------- */

	// Example with multiple objects
	$('a.zoom').magnificPopup({
		type: 'iframe'
	});

	// Example with multiple objects
	$('a.image-popup').magnificPopup({
		type: 'image'
	});

	/*-------------------------------------------------*/
	/* = skills animate
	/*-------------------------------------------------*/

	var skillBar = $('.skills-box');
	skillBar.appear(function() {

		var animateElement = $(".meter > p");
		animateElement.each(function() {
			$(this)
				.data("origWidth", $(this).width())
				.width(0)
				.animate({
					width: $(this).data("origWidth")
				}, 1200);
		});

	});

	/*-------------------------------------------------*/
	/* =  count increment
	/*-------------------------------------------------*/

	$('.statistic-post').appear(function() {
		$('.timer').countTo({
			speed: 4000,
			refreshInterval: 60,
			formatter: function (value, options) {
				return value.toFixed(options.decimals);
			}
		});
	});
	
	/*-------------------------------------------------*/
	/* =  OWL carousell
	/*-------------------------------------------------*/

	var owlWrap = $('.owl-wrapper');

	if (owlWrap.length > 0) {

		if (jQuery().owlCarousel) {
			owlWrap.each(function(){

				var carousel= $(this).find('.owl-carousel'),
					dataNum = $(this).find('.owl-carousel').attr('data-num'),
					dataNum2,
					dataNum3;

				if ( dataNum == 1 ) {
					dataNum2 = 1;
					dataNum3 = 1;
				} else if ( dataNum == 2 ) {
					dataNum2 = 2;
					dataNum3 = dataNum - 1;
				} else {
					dataNum2 = dataNum - 1;
					dataNum3 = dataNum - 2;
				}

				carousel.owlCarousel({
					autoPlay: 10000,
					navigation : true,
					items : dataNum,
					itemsDesktop : [1199,dataNum2],
					itemsDesktopSmall : [991,dataNum3],
					itemsTablet : [768, dataNum3],
				});

			});
		}
	}

	/*-------------------------------------------------*/
	/* =  count increment
	/*-------------------------------------------------*/

	$('.stat-box').appear(function() {
		DevSolutionSkill.init('circle1'); 
		DevSolutionSkill.init('circle2'); 
		DevSolutionSkill.init('circle3'); 
		DevSolutionSkill.init('circle4');
	});
	
	/* ---------------------------------------------------------------------- */
	/*	Contact Map
	/* ---------------------------------------------------------------------- */

	try {
		var fenway = [42.345573,-71.098326]; //Change a map coordinate here!
		var markerPosition = [42.345573,-71.098326]; //Change a map marker here!
    	$('#map')
			.gmap3({
				center:fenway,
				zoom: 13,
				mapTypeId: "shadeOfGrey", // to select it directly
				scrollwheel: false,
				mapTypeControlOptions: {
					mapTypeIds: [google.maps.MapTypeId.ROADMAP, "shadeOfGrey"]
				}
			})
			.styledmaptype(
			"shadeOfGrey",
			[
				{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":60}]},
				{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":86}]},
				{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
				{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":90}]},
				{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":87},{"weight":1.2}]},
				{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":90}]},
				{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":91}]},
				{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":87}]},
				{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":99},{"weight":0.2}]},
				{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":88}]},
				{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":86}]},
				{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":79}]},
				{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":87}]}
			],
			{name: "Shades of Grey"});
			$('#map')
				.gmap3({
					center: fenway,
					zoom: 13,
					mapTypeId : google.maps.MapTypeId.ROADMAP
				})
				.marker({
					position: markerPosition,
					icon: 'images/marker.png'
			});
	} catch(err) {

	}
	
	/*-------------------------------------------------*/
	/* =  flexslider
	/*-------------------------------------------------*/

	var SliderPost = $('.flexslider');

	SliderPost.flexslider({
		slideshowSpeed: 10000,
		easing: "swing"
	});

	/* ---------------------------------------------------------------------- */
	/*	Contact Form
	/* ---------------------------------------------------------------------- */

	var submitContact = $('#submit_contact'),
		message = $('#msg');

	submitContact.on('click', function(e){
		e.preventDefault();

		var $this = $(this);
		
		$.ajax({
			type: "POST",
			url: 'contact.php',
			dataType: 'json',
			cache: false,
			data: $('#contact-form').serialize(),
			success: function(data) {

				if(data.info !== 'error'){
					$this.parents('form').find('input[type=text],textarea,select').filter(':visible').val('');
					message.hide().removeClass('alert-success').removeClass('alert-danger').addClass('alert-success').html(data.msg).fadeIn('slow').delay(5000).fadeOut('slow');
				} else {
					message.hide().removeClass('alert-success').removeClass('alert-danger').addClass('alert-danger').html(data.msg).fadeIn('slow').delay(5000).fadeOut('slow');
				}
			}
		});
	});

	/* ---------------------------------------------------------------------- */
	/*	Header animate after scroll
	/* ---------------------------------------------------------------------- */

	(function() {

		var docElem = document.documentElement,
			didScroll = false,
			changeHeaderOn = 40;
			document.querySelector( 'header, a.go-top' );
		function init() {
			window.addEventListener( 'scroll', function() {
				if( !didScroll ) {
					didScroll = true;
					setTimeout( scrollPage, 100 );
				}
			}, false );
		}
		
		function scrollPage() {
			var sy = scrollY();
			if ( sy >= changeHeaderOn ) {
				$( 'header' ).addClass('active');
				$( 'a.go-top' ).addClass('active');
			}
			else {
				$( 'header' ).removeClass('active');
				$( 'a.go-top' ).removeClass('active');
			}
			didScroll = false;
		}
		
		function scrollY() {
			return window.pageYOffset || docElem.scrollTop;
		}
		
		init();
		
	})();

});

function Resize() {
	$(window).trigger('resize');
}
