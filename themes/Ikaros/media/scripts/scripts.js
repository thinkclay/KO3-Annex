/*-----------------------------------------------------------------------------------*/
/*	SLIDER
/*-----------------------------------------------------------------------------------*/ 

$(document).ready(function() {
				
				if ($.fn.cssOriginal!=undefined)
					$.fn.css = $.fn.cssOriginal;

					$('.fullwidthbanner').revolution(
						{	
							delay:9000,												
							startwidth:960,
							startheight:450,
							hideThumbs:200,
							onHoverStop:"on",						// Stop Banner Timet at Hover on Slide on/off
							navigationType:"none",					//bullet, thumb, none, both	 (No Shadow in Fullwidth Version !)
							touchenabled:"on",						// Enable Swipe Function : on/off
							fullWidth:"on"
							
						});	
			});

	
$(document).ready(function() {
				
				if ($.fn.cssOriginal!=undefined)
					$.fn.css = $.fn.cssOriginal;

					$('.banner').revolution(
						{	
							delay:9000,												
							startheight:450,							
							startwidth:960,
							
							hideThumbs:200,
							
							navigationType:"bullet",					//bullet, thumb, none, both		(No Thumbs In FullWidth Version !)
							navigationArrows:"verticalcentered",		//nexttobullets, verticalcentered, none
							navigationStyle:"round",				//round,square,navbar
							
							touchenabled:"on",						// Enable Swipe Function : on/off
							onHoverStop:"on",						// Stop Banner Time at Hover on Slide on/off
							
							navOffsetHorizontal:0,
							navOffsetVertical:-25,
							
							shadow:1,								//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows  (No Shadow in Fullwidth Version !)
							fullWidth:"off"							// Turns On or Off the Fullwidth Image Centering in FullWidth Modus
														
						});	

					});

/*-----------------------------------------------------------------------------------*/
/*	TWITTER
/*-----------------------------------------------------------------------------------*/

getTwitters('twitter', {
        id: 'elemisdesign', 
        count: 2, 
        enableLinks: true, 
        ignoreReplies: false,
        template: '<span class="twitterPrefix"><span class="twitterStatus">%text%</span><br /><em class="twitterTime"><a href="http://twitter.com/%user_screen_name%/statuses/%id%">%time%</a></em>',
        newwindow: true
});

/*-----------------------------------------------------------------------------------*/
/*	FORM
/*-----------------------------------------------------------------------------------*/
jQuery(document).ready(function($){
	$('.forms').dcSlickForms();
});


$(document).ready(function() {
	$('.comment-form input[title]').each(function() {
		if($(this).val() === '') {
			$(this).val($(this).attr('title'));	
		}
		
		$(this).focus(function() {
			if($(this).val() == $(this).attr('title')) {
				$(this).val('').addClass('focused');	
			}
		});
		$(this).blur(function() {
			if($(this).val() === '') {
				$(this).val($(this).attr('title')).removeClass('focused');	
			}
		});
	});
});


/*-----------------------------------------------------------------------------------*/
/*	TOGGLE
/*-----------------------------------------------------------------------------------*/
$(document).ready(function(){
//Hide the tooglebox when page load
$(".togglebox").hide();
//slide up and down when click over heading 2
$("h4").click(function(){
// slide toggle effect set to slow you can set it to fast too.
$(this).toggleClass("active").next(".togglebox").slideToggle("slow");
return true;
});
});

/*-----------------------------------------------------------------------------------*/
/*	TABS
/*-----------------------------------------------------------------------------------*/
 $(document).ready( function() {
      $('#services-container').easytabs({
	      animationSpeed: 300,
	      updateHash: false
      });
    });
    
/*-----------------------------------------------------------------------------------*/
/*	TESTIMONIALS
/*-----------------------------------------------------------------------------------*/  
 $(document).ready( function() {
      $('#testimonials-container').easytabs({
	      animationSpeed: 500,
	      updateHash: false,
	      cycle: 5000
      });
      
    });
    
    
/*-----------------------------------------------------------------------------------*/
/*	POSTS GRID
/*-----------------------------------------------------------------------------------*/ 
jQuery(document).ready(function($){
 var $container = $('.posts-grid');
	$container.imagesLoaded( function(){
		$container.isotope({
			itemSelector : '.post'
		});	
	});
});

/*-----------------------------------------------------------------------------------*/
/*	PORTFOLIO GRID
/*-----------------------------------------------------------------------------------*/ 

$(document).ready(function(){
 var $container = $('#portfolio .items');
	$container.imagesLoaded( function(){
		$container.isotope({
			itemSelector : '.item',
			layoutMode : 'fitRows'
		});	
	});
			
	$('.filter li a').click(function(){
		
		$('.filter li a').removeClass('active');
		$(this).addClass('active');
		
		var selector = $(this).attr('data-filter');
		$container.isotope({ filter: selector });
		
		return false;
	});
});

/*-----------------------------------------------------------------------------------*/
/*	VIDEOCASE
/*-----------------------------------------------------------------------------------*/ 

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {
        "use strict";
        if (this == null) {
            throw new TypeError();
        }
        var t = Object(this);
        var len = t.length >>> 0;
        if (len === 0) {
            return -1;
        }
        var n = 0;
        if (arguments.length > 0) {
            n = Number(arguments[1]);
            if (n != n) { // shortcut for verifying if it's NaN
                n = 0;
            } else if (n != 0 && n != Infinity && n != -Infinity) {
                n = (n > 0 || -1) * Math.floor(Math.abs(n));
            }
        }
        if (n >= len) {
            return -1;
        }
        var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
        for (; k < len; k++) {
            if (k in t && t[k] === searchElement) {
                return k;
            }
        }
        return -1;
    }
}

$(document).ready(function(){
 var $container = $('#videocase .items');
	$container.imagesLoaded( function(){
		$container.isotope({
			itemSelector : '.item',
			layoutMode : 'fitRows'
		});	
	});
			
	$('.filter li a').click(function(){
		
		$('.filter li a').removeClass('active');
		$(this).addClass('active');
		
		var selector = $(this).attr('data-filter');
		$container.isotope({ filter: selector });
		
		return false;
	});
	
	
	var _videocontainer = $('#videocontainer');
	var _addressArr = [];
	$('.items li').each(function(index) {
		$(this).attr('rel', index);
		_addressArr[index] = $(this).data('address');
	});
	
	var _descArr = [];
	$('.description li').each(function(index) {
		_descArr[index] = $(this);
		$(this).hide();
		$(this).on('click', function(event) {
		  	alert('click description');
		});
	});
	
	var _currentNum = 0;
	var isInit = false;
	_videocontainer.fitVids();
	
	var _videoArr = [];
	$('.video').each(function(index) {
	  	_videoArr[index] = $(this)
		if(index!=0) $(this).hide();
	});
	
	$.address.init(function(event) {
	}).change(function(event) {
		var _address = $.address.value().replace('/', '');
		if(_address){
			if(_address!=""&&_currentNum!=_addressArr.indexOf(_address))loadAsset(_addressArr.indexOf(_address));			
		}else{		
			$.address.path(_addressArr[0]);			
		} 
	})	
	
	
	$('.items li').on('click', function(event) {
		loadAsset($(this).attr('rel'));
		return false;
	});
	
	function loadAsset(n){
		$('html, body').animate({scrollTop: _videocontainer.offset().top-30}, 600);
		_index = n;	   
		var _pv = _videoArr[_currentNum];
		if(_pv)_pv.animate({opacity: 0}, 300, function() {
			var _ph = _pv.height();
			_pv.hide();				
			_pv.remove();
			var _h = _videoArr[_index].show().css('opacity', 0).height();
			_videoArr[_index].css('height', _ph);
			_videoArr[_index].animate({opacity: 1, height: _h}, 600, function() {
				_videoArr[_index].css('height', 'auto');
				_videocontainer.append(_pv);
				// _videocontainer.fitVids();			
			})
		})		
		$.address.path(_addressArr[_index])
		_currentNum = _index;		
		return false;
	}
	
});
			
/*-----------------------------------------------------------------------------------*/
/*	FANCYBOX
/*-----------------------------------------------------------------------------------*/

$(document).ready(function() {
			
			$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({

					arrows : false,
					padding: 10,
					closeBtn: false,
					openEffect : 'fade',
					closeEffect : 'fade',
					prevEffect : 'fade',
					nextEffect : 'fade',
					helpers : {
						media : {},
						buttons	: {},
						thumbs : {
							width  : 50,
							height : 50
						},
						title : {
							type : 'outside'
						},
						overlay : {
            				opacity: 0.9
        				}	
					},
					beforeLoad: function() {
            var el, id = $(this.element).data('title-id');

            if (id) {
                el = $('#' + id);
            
                if (el.length) {
                    this.title = el.html();
                }
            }
        }
				});
		});
		

/*-----------------------------------------------------------------------------------*/
/*	IMAGE HOVER
/*-----------------------------------------------------------------------------------*/		
		
$(document).ready(function() {	
$('.featured a').prepend('<span class="overlay more"></span>');
});

$(document).ready(function() {
        $('.featured').mouseenter(function(e) {

            $(this).children('a').children('span').fadeIn(300);
        }).mouseleave(function(e) {

            $(this).children('a').children('span').fadeOut(200);
        });
    });			

$(document).ready(function() {
        $('.items li').mouseenter(function(e) {

            $(this).children('a').children('div').fadeIn(300);
        }).mouseleave(function(e) {

            $(this).children('a').children('div').fadeOut(200);
        });
    });	

/*-----------------------------------------------------------------------------------*/
/*	BUTTON HOVER
/*-----------------------------------------------------------------------------------*/


jQuery(document).ready(function($)  {
$(".button, .btn-submit, .meta-nav-prev, .meta-nav-next").css("opacity","1.0");
$(".button, .btn-submit, .meta-nav-prev, .meta-nav-next").hover(function () {
$(this).stop().animate({ opacity: 0.85 }, "fast");  },
function () {
$(this).stop().animate({ opacity: 1.0 }, "fast");  
}); 
});

jQuery(document).ready(function($)  {
$(".social li a").css("opacity","1.0");
$(".social li a").hover(function () {
$(this).stop().animate({ opacity: 0.75 }, "fast");  },
function () {
$(this).stop().animate({ opacity: 1.0 }, "fast");  
}); 
});

/*-----------------------------------------------------------------------------------*/
/*	VIDEO
/*-----------------------------------------------------------------------------------*/

jQuery(document).ready(function() {
    		jQuery('.media, .featured').fitVids();
    	});	


/*-----------------------------------------------------------------------------------*/
/*	SELECTNAV
/*-----------------------------------------------------------------------------------*/

$(document).ready(function() {
		
			selectnav('tiny', {
				label: '--- Navigation --- ',
				indent: '-'
			});

			
		});

/*-----------------------------------------------------------------------------------*/
/*	MENU
/*-----------------------------------------------------------------------------------*/
ddsmoothmenu.init({
	mainmenuid: "menu",
	orientation: 'h',
	classname: 'menu',
	contentsource: "markup"
})

