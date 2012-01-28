jQuery(document).ready(function($) {

	// Post Formats
	if($('#feather_post_formats').length) {
		// Variables
		var post_formats = $('#feather_post_formats');
		var post_format_fields = $('#feather_post_format_aside').parent().find('input');
		// Toggle post formats
		function toggle_post_formats() {
			var post_formats_enabled = post_formats.is(':checked');
			if(post_formats_enabled) {
				post_format_fields.each(function() {
					$(this).prop('disabled',false);
					$(this).siblings('label').css('color','#333');

				});
			} else {
				post_format_fields.each(function() {
					$(this).prop('disabled',true);
					$(this).siblings('label').css('color','#ccc');
				});
			}
		}
		toggle_post_formats();
		// Toggle on click
		post_formats.click(function() { toggle_post_formats(); });
	}

	// Image Select
	if($('.feather-image-button').length) {
		var image_field;
		$('.feather-image-button').each(function() {
			var button = $(this);
			button.click(function() {
				image_field = $(this).siblings('input[type="text"]').attr('name');
				tb_show('','media-upload.php?type=image&TB_iframe=true');
				return false;
			});
		});
		// Populate image field with URL; Remove media library
		window.send_to_editor = function(html) {
			var image_url = $('img',html).attr('src');
			$('input[name="'+image_field+'"]').val(image_url);
			tb_remove();
		}
	}

	// Colorpicker
	if($('.feather-colorpicker').length) {
		$('.feather-colorpicker').each(function() {
			var color_div = $(this);
			var color_input = $(this).siblings('input');
			$(color_div).ColorPicker({
				onBeforeShow: function () {
					$(this).ColorPickerSetColor(color_input.val());
				},
				onChange: function (hsb, hex, rgb) {
					$(color_input).val(hex);
					$(color_div).find('div').css('background-color', '#' + hex);
				}
			});
		});
	}

});
