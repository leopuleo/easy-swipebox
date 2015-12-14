;(function($){
	$( document ).ready(function() {

		// Add class .swipebox to all links to images - Exclude the ones with no-swipebox class
		if(easySwipeBox_localize_init_var.autodetect.autodetectImage === true) {
			$('a[href]').filter(function() {
			return /(\.jpg|\.jpeg|\.gif|\.png)/i.test( $(this).attr('href')) && !($(this).hasClass(easySwipeBox_localize_init_var.autodetect.autodetectExclude));
			}).addClass("swipebox");

			// If link has not title, add img title/alt as title to links
			$('a.swipebox').filter(function(){

				// Check if image has title - else alt
				if ($(this).find('img').attr('title')){
					var title_img = $(this).find('img').attr('title');
				} else {
					var title_img = $(this).find('img').attr('alt');
				}

				if ($(this).not("[title]")){
					$(this).attr('title', title_img);
				}
			});
		}

		// Add class .swipebox to all links to Vimeo videos - Exclude the ones with no-swipebox class
		if(easySwipeBox_localize_init_var.autodetect.autodetectVideo === true) {

			$('a[href]').filter(function() {
				return /(?:www\.)?vimeo.com\/([0-9]+)/i.test( $(this).attr('href')) && !($(this).hasClass(easySwipeBox_localize_init_var.autodetect.autodetectExclude));
			}).addClass("swipebox swipebox-video");

			// Add class .swipebox to all links to YouTube videos - Exclude the ones with no-swipebox class
			$('a[href]').filter(function() {
				return /^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/i.test( $(this).attr('href')) && !($(this).hasClass(easySwipeBox_localize_init_var.autodetect.autodetectExclude));
			}).addClass("swipebox swipebox-video");
		}


		// Add SwipeBox Script
		$( '.swipebox' ).swipebox( {
			useCSS : easySwipeBox_localize_init_var.lightbox.useCSS,
			useSVG : easySwipeBox_localize_init_var.lightbox.useSVG,
			initialIndexOnArray : 0,
			removeBarsOnMobile : easySwipeBox_localize_init_var.lightbox.removeBarsOnMobile,
			hideCloseButtonOnMobile : easySwipeBox_localize_init_var.lightbox.hideCloseButtonOnMobile,
			hideBarsDelay : easySwipeBox_localize_init_var.lightbox.hideBarsDelay,
			videoMaxWidth : easySwipeBox_localize_init_var.lightbox.videoMaxWidth,
			vimeoColor : easySwipeBox_localize_init_var.lightbox.vimeoColor,
			beforeOpen: null,
			afterOpen: null,
			afterClose: null,
			nextSlide: null,
			prevSlide: null,
			loopAtEnd: easySwipeBox_localize_init_var.lightbox.loopAtEnd,
			autoplayVideos: easySwipeBox_localize_init_var.lightbox.autoplayVideos,
			queryStringData: {},
			toggleClassOnLoad: ''
		} );

	});
})(jQuery);
