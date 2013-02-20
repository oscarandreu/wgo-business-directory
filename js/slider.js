var j$ = jQuery.noConflict();
var $dialog = j$('<div></div>');

function showImages(id, title)
{
	$dialog.html('<center><img src="images/wait.gif"></center>');
	$dialog.dialog({
		autoOpen: false,
		modal: true,
		resizable: false,
		width: 670,
		height: 410,
		title: title
	});
	$dialog.dialog('open');
	
	
	var jqxhr = j$.ajax( "inc/inc_display_images.php?id="+id ).done(function(data) { 
		
		$dialog.html(data)
		    
		var supportCanvas = 'getContext' in document.createElement('canvas');
	
		var slides = j$('#slideshow li'), current = 0, slideshow = {
			width : 0,
			height : 0
		};
	
		setTimeout(
				function() {
	
					window.console && window.console.time	&& console.time('Generated In');
	
					if (supportCanvas) {
						j$('#slideshow img').each(function() {
	
							if (!slideshow.width) {
								// Taking the dimensions of the
								// first image:
								slideshow.width = this.width;
								slideshow.height = this.height;
							}
	
							// Rendering the modified versions of
							// the images:
							createCanvasOverlay(this);
						});
					}
	
					window.console && window.console.timeEnd
							&& console.timeEnd('Generated In');
	
					j$('#slideshow .arrow').click(
									function() {
										var li = slides.eq(current), canvas = li.find('canvas'), nextIndex = 0;
										
										if (j$(this).hasClass(
												'next')) {
											nextIndex = current >= slides.length - 1 ? 0 : current + 1;
										} else {
											nextIndex = current <= 0 ? slides.length - 1 : current - 1;
										}
	
										var next = slides.eq(nextIndex);
	
										if (supportCanvas) {
											canvas.fadeIn(function() {
														next.show();
														current = nextIndex;
														li.fadeOut(function() {
																	li.removeClass('slideActive');
																	canvas.hide();
																	next.addClass('slideActive');
																});
													});
										} else {s
											current = nextIndex;
											next.addClass('slideActive').show();
											li.removeClass('slideActive').hide();
										}
									});
	
				}, 100);
		
		function createCanvasOverlay(image) {
	
			var canvas = document.createElement('canvas'), canvasContext = canvas.getContext("2d");
			canvas.width = slideshow.width;
			canvas.height = slideshow.height;
			canvasContext.drawImage(image, 0, 0);
			var imageData = canvasContext.getImageData(0, 0, canvas.width, canvas.height), data = imageData.data;
			for ( var i = 0, z = data.length; i < z; i++) {
				data[i] = ((data[i] < 128) ? (2 * data[i] * data[i] / 255)
						: (255 - 2 * (255 - data[i])
								* (255 - data[i]) / 255));
				data[++i] = ((data[i] < 128) ? (2 * data[i]
						* data[i] / 255) : (255 - 2
						* (255 - data[i]) * (255 - data[i]) / 255));
				data[++i] = ((data[i] < 128) ? (2 * data[i]
						* data[i] / 255) : (255 - 2
						* (255 - data[i]) * (255 - data[i]) / 255));
				++i;
			}
			canvasContext.putImageData(imageData, 0, 0);
			image.parentNode.insertBefore(canvas, image);
		}
	});
}
