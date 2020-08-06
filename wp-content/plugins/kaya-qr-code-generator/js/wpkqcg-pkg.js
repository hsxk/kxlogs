/* Kaya QR Code Generator JavaScript */

/**
 * Generate QR Code Image.
 */
function wpkqcg_qrcode_encode(tab_data)
{
	//	get QR Code content to encrypt
	var img_id		= tab_data[0];
	var qr_data		= tab_data[1];
	var qr_ecclevel	= tab_data[2];
	var img_size	= tab_data[3];
	var img_color	= tab_data[4];
	var img_bgcolor	= tab_data[5];
	
	// set image code color
	if ('' === img_color)
	{
		img_color = '#000000';
	}
	else if ('#' !== img_color.charAt(0))
	{
		img_color = "#" + img_color;
	}
	
	// set image background color
	if ('' === img_bgcolor)
	{
		img_bgcolor = '#FFFFFF';
	}
	else if ('#' !== img_bgcolor.charAt(0))
	{
		img_bgcolor = "#" + img_bgcolor;
	}
	
	// set image size
	if ('' === img_size)
	{
		//	get generation options without resize
		var options = {ecclevel: qr_ecclevel, customcolor: img_color, custombgcolor: img_bgcolor};
		//	generate img
		var qrcode_img = QRCode.generatePNG(qr_data, options);
	}
	else
	{
		//	get generation options with img resized
		var qrcode_matrix = QRCode.generate(qr_data, {ecclevel: qr_ecclevel});
		var matrix_size = qrcode_matrix.length + 8;
		
		if (img_size > matrix_size)
		{
			var module_resize = Math.round(img_size/matrix_size);
		}
		else
		{
			var module_resize = 1;
		}
		
		var options = {ecclevel: qr_ecclevel, modulesize: module_resize, customcolor: img_color, custombgcolor: img_bgcolor};
		//	generate img
		var qrcode_img = QRCode.generatePNG(qr_data, options);
	}
	//	include to img src
	document.getElementById(img_id).src = qrcode_img;
	
	return false;
}

/**
 * Get values to generate QR Code Image.
 */
function wpkqcg_qrcode_display()
{
	var qrcode = document.getElementsByClassName('wpkqcg_qrcode');
	if (qrcode.length !== 0)
	{
		for (var i = 0; i < qrcode.length; ++i)
		{
			var qrcode_img		= qrcode[i];
			var qrcode_ecclevel	= document.getElementById(qrcode_img.id + '_ecclevel');
			var qrcode_size		= document.getElementById(qrcode_img.id + '_size');
			var qrcode_color	= document.getElementById(qrcode_img.id + '_color');
			var qrcode_bgcolor	= document.getElementById(qrcode_img.id + '_bgcolor');
			var qrcode_content	= document.getElementById(qrcode_img.id + '_content');
			var qrcode_data		= [qrcode_img.id, qrcode_content.value, qrcode_ecclevel.value, qrcode_size.value, qrcode_color.value, qrcode_bgcolor.value];
			wpkqcg_qrcode_encode(qrcode_data);
		}
	}
}
