;(function($){
	$( document ).ready(function() {

		// Add class .swipebox to all links to Vimeo videos
		$('a[href]').filter(function() {
			return /(?:www\.)?vimeo.com\/([0-9]+)/i.test( $(this).attr('href'))
		}).addClass("swipebox swipebox-video");

		// Add class .swipebox to all links to Youtube videos
		$('a[href*="youtube.com/watch"]').addClass('swipebox swipebox-video');
		$('a[href*="youtu.be"]').addClass('swipebox swipebox-video');
		
	});
})(jQuery);

