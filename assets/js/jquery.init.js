;(function($){
	$( document ).ready(function() {

		// Add class swipebox to all links to images
		$('a[href]').filter(function() {
			return /(jpg|gif|png)$/.test( $(this).attr('href'))
		}).addClass("swipebox");

		// Add class swipebox to all links to videos
		$('a[href*="youtube.com"]').addClass('swipebox-video');
		$('a[href*="vimeo.com"]').addClass('swipebox-video');

		// Add img alt attirbute as title attribute to links 
		$('a.swipebox').filter(function(){
			var title_img = $(this).find('img').attr('alt');
			$(this).attr('title', title_img);
		});

		// Add SwipeBox Script
		$( ".swipebox" ).swipebox();
		$( ".swipebox-video" ).swipebox();
	});
})(jQuery);