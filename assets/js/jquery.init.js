;(function($){
	$( document ).ready(function() {

		// Add class swipebox to all links to images
		$('a[href]').filter(function() {
			return /(jpg|gif|png)$/.test( $(this).attr('href'))
		}).addClass("swipebox");

		// Add class swipebox to all links to videos
		$('a[href*="youtube.com"]').addClass('swipebox-video');
		$('a[href*="vimeo.com"]').addClass('swipebox-video');

		// If link has not title, add img alt as title to links
		$('a.swipebox').filter(function(){
			var title_img = $(this).find('img').attr('alt');
			if ($(this).not("[title]")){
				$(this).attr('title', title_img);
			}
		});

		// Add SwipeBox Script
		$( ".swipebox" ).swipebox();
		$( ".swipebox-video" ).swipebox();
	});
})(jQuery);