function getJsonFromUrl(query) {
	query = decodeURIComponent(query);
	var result = {};
	query.split("&").forEach(function(part) {
		var item = part.split("=");
		//if (typeof(item2[1]) != "undefined"){
		result[item[0]] = decodeURIComponent(item[1]);
	});
	return result;
}

function generateCoverImage(data) {

	var div = document.createElement("canvas");
	div.setAttribute("id", "ceb");
	div.setAttribute("width", data.width);
	div.setAttribute("height", data.height);
	div.setAttribute("style", "display:none;");
	document.body.appendChild(div);

	var canvas = this.__canvas = new fabric.Canvas('ceb');
	fabric.Object.prototype.transparentCorners = false;
	canvas.setBackgroundColor({
		source : data.img
	}, function() {
		canvas.renderAll();
	});
	
	if(typeof data.post.InputID == "object") {
		
		var x_broi;
		var inputs = data.post.InputID; 
		
		for (x_broi in inputs) {
			
			//console.log(inputs[x_broi].text);
			console.log(data.fields[x_broi]);
			//console.log(parseInt(data.fields[x_broi].text_angle));
			//console.log(data.fonts[data.fields[x_broi].text_font_id]);
			var text_align = 'left';
			if(data.fields[x_broi].text_centred != 0){
				text_align = 'center';
			}
			var text_fabric = new fabric.Text(inputs[x_broi].text, {
				fontSize: data.fields[x_broi].text_size,
				left: parseInt(data.fields[x_broi].text_margin_left),
				top: parseInt(data.fields[x_broi].text_margin_top),
				lineHeight: 1,
				originX: 'left',
				textAlign: text_align,
				fontFamily: ""+data.fonts[data.fields[x_broi].text_font_id]+"",
				fill: '#'+data.fields[x_broi].text_color,
				angle: parseInt(data.fields[x_broi].text_angle),
				shadow:{color:"#"+data.fields[x_broi].text_shadow_color,blur:data.fields[x_broi].text_shadow_blur,offsetX:data.fields[x_broi].text_shadow_x,offsetY:data.fields[x_broi].text_shadow_y},
				rowInputId: x_broi
			});
			
			canvas.add(text_fabric);
			
			if(data.fields[x_broi].text_centred != 0){
				text_fabric.centerH();
				text_fabric.setCoords();
				canvas.renderAll();
			}
			
			
			//console.log(data.fields[x_broi].text_shadow_color); 
			/*
			text_fabric.setShadow({
				color: data.fields[x_broi].text_shadow_color,
				blur: data.fields[x_broi].text_shadow_blur,
				offsetX: data.fields[x_broi].text_shadow_x,
				offsetY: data.fields[x_broi].text_shadow_y
			});
			*/
		}
	}
	
	window.setTimeout(function() {
		canvasImage = canvas.toDataURL("image/png");
		var url = "wp-admin/admin-ajax.php";
		jQuery(document).ready(
				function($) {
					$.ajax({
						type : "POST",
						url : url,
						data : "action=ajaxGenerateCover&base_64="
								+ canvasImage,
						success : function(generatedImage) {
							$("#scg-generated-image").hide().attr("src",
									generatedImage).fadeIn("slow");
						}
					});
				});

	}, 500);
}