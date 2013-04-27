

/* ZETA SLIDER - VERSION */
(function($){
		
	$.fn.zetaSlider = function(options){
		
		//define script properties
		var defaults = {
			slideMargin:20,
			warningMessage:true,
			warningMessageTimeout:5000,
			topMargin:50
		};
		var o = $.extend(defaults, options);
			
		//declare/cache window variables
		var $body = $('*');
		var $slider = $(this);
		var $holder = $slider.find('div.zetaHolder');
		var $empty = $slider.find('div.zetaEmpty');
		var $wrapper = $slider.find('div.zetaWrapper');
		var $warning = $slider.find('div.zetaWarning');
		var $controls = $slider.find('div.zetaControls');
		var $thumbs = $slider.find('ul.zetaThumbs');
		var $slides, $activeThumb = null;
		var topOpen = ($slider.hasClass('zetaTop') ? true : false);
		var touchM = "ontouchstart" in window;

		//declare other variables
		var checkWidthI, loadSlideI, sliderW, sliderL, isDragging, _loading, _index, _sTime, _accel,  _iPos, _sPos, _zPos, _yPos, _lDrag, gPadding = parseInt($holder.css('padding-left')), isClosed = true, hashLib = new Array(), emptyOffset = -1, emptySpeed = 801;

		//prevent mouse dragging on IE
		if($.browser.msie)
			document.ondragstart = function(){ return false; }

		$empty.css('overflow', 'hidden')

		$slides = $empty.children('div');
		_loading = _index = 0;
		sliderW = sliderL = 1;
		loadSlide();

	    $wrapper.bind('touchstart', touchStart);
		if(!$.browser.msie)
			window.addEventListener('touchend', touchEnd, true);

		if(o.warningMessage && $.cookie('warCookie') == null)
			$warning.delay(1500).fadeIn().delay(o.warningMessageTimeout).fadeOut();

		$.cookie('warCookie', 'used', {expires:7, path: '/'});


		//function that loads a slide, then triggers the loading of the next one
		function loadSlide(){

			if(_loading == 0)
				checkWidthI = setInterval(checkWidth, 100);

			var $slide = $slides.eq(_loading);

			if($slide.hasClass('zetaTextBox')){

				continueLoading($slide);

			}else if($slide.hasClass('zetaVideoBox')){

				var iframe = $slide.data('type') == 'vimeo' ? '<iframe src="http://player.vimeo.com/video/'+ $slide.data('id') +'" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>' : '<iframe width="100%" height="100%" src="http://www.youtube.com/embed/'+ $slide.data('id') +'" frameborder="0" allowfullscreen></iframe>';
				$slide.append(iframe);
				continueLoading($slide);

			}else{
				
				continueLoading($slide);
								
			}

		}

		//function that checks if all elements are loaded, and if not, it continues the loading process
		function continueLoading($slide){
		
			sliderL = $slide.outerWidth()+o.slideMargin+gPadding;
			$slide.fadeIn();

			if(++_loading < $slides.length)
				loadSlideI = setTimeout(loadSlide, 300);
			else
				setTimeout(function(){
					clearInterval(checkWidthI);
				}, 1000)
			
			$slide.find('img').bind('dragstart', function(event){
				event.preventDefault();
				return false;
			});

			/*$slide.find('a').bind('click', function(event) {						
				event.preventDefault();						
				return false;
			});*/

		}

		//function that rechecks the width of the slider, to prevent all bugs
		function checkWidth(){
			sliderW = 1;

			$slides.each(function(){
				sliderW += $(this).outerWidth()+o.slideMargin;
			});

			$empty.width(sliderW);
		}

		function resizeScreen() {
			checkWidth();
			emptyOffset=-1;
			$empty.stop().animate({'left': $slides.eq(_index).position().left*(-1)+gPadding}, 500, 'easeInOutSine');
		}
		var TO = false;
		$(window).resize(function(){
			if(!isClosed && topOpen)
				$thumbs.css('marginTop', $holder.outerHeight() + o.topMargin);
			if(TO !== false)
			    clearTimeout(TO);
			TO = setTimeout(resizeScreen, 200); 
		});

		function nextZeta(){

			if(isDragging)
				recalculateIndex();

			if(_index+1 < $slides.length){
				_index++;
				$empty.stop().animate({'left': $slides.eq(_index).position().left*(-1)+gPadding}, 500, 'easeInOutSine');
			}

			return false;

		}
		function prevZeta(){

			if(isDragging)
				recalculateIndex();

			if(_index-1 >= 0){
				_index--;
				$empty.stop().animate({'left': $slides.eq(_index).position().left*(-1)+gPadding}, 500, 'easeInOutSine');
			}

			return false;

		}

		//bind mouse & key listeners
		$slider.find('a.zetaBtnNext').bind('click', nextZeta);
		$slider.find('a.zetaBtnPrev').bind('click', prevZeta);

		$(document).keydown(function(e){
			if(e.keyCode == 39){
				nextZeta();
			}else if(e.keyCode == 37){
				prevZeta();
			}
		});

		//bind dragging events
		if(!touchM){
			$wrapper.mousedown(function(event){
				wrapMouseDown(event, event.clientX, event.clientY, false);
				return false;
			}).mouseup(function(event){
				wrapMouseUp(event, event.clientX, false);
				return false;
			});
			$(document).mouseup(function(event){
				if(isDragging)
					wrapMouseUp(event, event.clientX, false);
				return false;
			});
		}

		//add touch support
	    function touchStart(event){
	    	if(event.originalEvent.touches.length == 1){
	    		wrapMouseDown(event.originalEvent, event.originalEvent.touches[0].pageX, event.originalEvent.touches[0].pageY, true);
	    	} else {
	    		return false;
	    	}
	    }
	    function touchEnd(event){
	    	event.preventDefault();
	    	wrapMouseUp(event.originalEvent, event.changedTouches[0].pageX, true);
	    }
	    function touchMove(event){
			if(event.touches.length > 1){
				return false;
			} else {

				if(Math.abs(event.touches[0].pageY - _yPos) > Math.abs(event.touches[0].pageX - _iPos) + 3) {
					return false;
				}
				
				event.preventDefault();	

				if(event.touches[0].pageY > $empty.offset().top && event.touches[0].pageY < $empty.height() + $empty.offset().top){
					scrollSlider(event, event.touches[0].pageX);
				}

			}
	    }

		//start/stop dragging mouse events functions
		function wrapMouseDown(event, clientX, clientY, touch){

			isDragging = true;

			$empty.removeClass('isDraggingFalse');
			$body.addClass('isDraggingTrue');

			_sTime = (new Date).getTime();
			_accel = _zPos = _iPos = clientX;
			_yPos = clientY;
			_sPos = $empty.position().left;

			if(!touch)
				$(document).bind('mousemove', function(event){
					event.preventDefault();
					scrollSlider(event, event.clientX);
				});
			else
				document.addEventListener('touchmove', touchMove, true);

		}
		function wrapMouseUp(event, clientX, touch){

			$body.removeClass('isDraggingTrue');
			$empty.addClass('isDraggingFalse');

			if(!touch)
				$(document).unbind('mousemove');
			else
				document.removeEventListener('touchmove', touchMove, true);

			if(_iPos != clientX && clientX != true) {

				var dist = (_lDrag - _accel);		
				var dur =  Math.max(40, ((new Date).getTime() - _sTime));

				_endPos = $empty.position().left;

				_bF = 0.0010;
				var f = 0.5, m = 2, v0 = Math.abs(dist) / dur;	

				var _tO = 0;
				if(v0 <= 2) {
					f = _bF * 3.5;
					_tO = 0;
				} else if(v0 > 2 && v0 <= 3) {
					f = _bF * 4;
					_tO = 200;
				} else if(v0 > 3){
					_tO = 300;
					if(v0 > 4) {
						v0 = 4;
						_tO = 400;
						f = _bF * 6;
					}
					f = _bF * 5;
				}							
							
				var S = ((v0 * v0 * m) / (2 * f)) * (dist < 0 ? -1 : 1);
				var T = v0 * m / f + _tO;	
				var P = Math.floor(_endPos + S);

				if(P < sliderW*(-1)+sliderL)
					$empty.stop().animate({'left': $slides.eq($slides.length-1).position().left*(-1)+gPadding}, T, 'easeOutCubic', function(){
							recalculateIndex();
						});
				else if(P > gPadding)
					$empty.stop().animate({'left': $slides.eq(0).position().left*(-1)+gPadding}, T, 'easeOutCubic', function(){
							recalculateIndex();
						});
				else
					$empty.stop().animate({'left':P}, T, 'easeOutCubic', function(){
							recalculateIndex();
						});	

			}

		}

		//function that handles the slider scrolling
		function scrollSlider(event, clientX){

			var tS = (new Date).getTime();
			_lDrag = clientX;
			
			if (tS - _sTime > 350) {
				_sTime = tS;
				_accel = clientX;						
			}

			var P = clientX - _zPos + _sPos;
			if(!(P > gPadding+100) && !(P < sliderW*(-1)+sliderL))
				$empty.css('left', P);

		}

		//function that recalculates the index after a user dragging process
		function recalculateIndex(){
			
			isDragging = false;

			var k = 0;

			while($slides.eq(k).position().left*(-1) > $empty.position().left){
				k++;
			}
			
			_index = k-1 > 0 ? k-1 : k;

		}

	}

})(jQuery);


/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright Â© 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: dur
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert(jQuery.easing.default);
		return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});

/*!
 * jQuery Cookie Plugin
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
(function($) {
    $.cookie = function(key, value, options) {

        // key and at least value given, set cookie...
        if (arguments.length > 1 && (!/Object/.test(Object.prototype.toString.call(value)) || value === null || value === undefined)) {
            options = $.extend({}, options);

            if (value === null || value === undefined) {
                options.expires = -1;
            }

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }

            value = String(value);

            return (document.cookie = [
                encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path    ? '; path=' + options.path : '',
                options.domain  ? '; domain=' + options.domain : '',
                options.secure  ? '; secure' : ''
            ].join(''));
        }

        // key and possibly options given, get cookie...
        options = value || {};
        var decode = options.raw ? function(s) { return s; } : decodeURIComponent;

        var pairs = document.cookie.split('; ');
        for (var i = 0, pair; pair = pairs[i] && pairs[i].split('='); i++) {
            if (decode(pair[0]) === key) return decode(pair[1] || ''); // IE saves cookies with empty string as "c; ", e.g. without "=" as opposed to EOMB, thus pair[1] may be undefined
        }
        return null;
    };
})(jQuery);

