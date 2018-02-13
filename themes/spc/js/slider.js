(function ($) {
	var SLIDER = {};
	
	/**
	* Initialize Slider
	* 
	* @returns Void
	*/
	SLIDER.initHomeSliderDesktop = function(){
		$('.js-home-slider').kwicks({
	//		minSize : '200px',
	//		maxSize : '65.5%',
			minSize : '16.65%',
			spacing : 0,
			behavior: 'menu', // Indicates if/what out-of-the-box behavior should be enabled (e.g: 'slideshow', 'menu').
			interactive: true // Indicates whether or not the mouse interactions described above should be enabled.
		});
	}

	SLIDER.homeSliderDestroy = function(){
		$('.js-home-slider').kwicks('destroy');
	}

	SLIDER.initHomeSliderMobile = function(){
		$('.js-home-slider').kwicks({
			maxSize : '61%',
	        behavior: 'menu',
	        spacing : 0,
	        isVertical: true
		});
	}
        Drupal.behaviors.homeSlider = {
            attach: function (context, settings) {
                if (window.innerWidth > 767) {
                        SLIDER.initHomeSliderDesktop();
                } else {
                        SLIDER.initHomeSliderMobile();
                }
                $('.js-home-slider').on('expand.kwicks', function(e, data) {
                    var $slider = $(this);
                    var index       = $('.js-home-slider').kwicks('expanded');

                    $slider.kwicks('select', index);
                });

                $(window).resize(function(){
                    if (window.innerWidth > 767) {
                        SLIDER.homeSliderDestroy();
                        SLIDER.initHomeSliderDesktop();
                    } else {
                        SLIDER.homeSliderDestroy();
                        SLIDER.initHomeSliderMobile();
                    }

                $('.js-home-slider').kwicks('expand', 0);
        });
             }
             
        }
	
	
	$(document).resize(function(){
		if (window.innerWidth > 767) {
			SLIDER.homeSliderDestroy();
			SLIDER.initHomeSliderDesktop();
		} else {
			SLIDER.homeSliderDestroy();
			SLIDER.initHomeSliderMobile();
		}
		
		$('.js-home-slider').kwicks('expand', 0);
	});
})(jQuery);
