;(function($){
	$( document ).ready(function() {

		// Add class .swipebox to all links to images
		$('a[href]').filter(function() {
			return /(\.jpg|\.jpeg|\.gif|\.png)/i.test( $(this).attr('href'));
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
		
	});
})(jQuery);

