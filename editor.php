<?php

   // The Event Location Metabox
   function wpt_events_location() {
   
   	global $post;
   
   	$dir = plugin_dir_url(__FILE__);
   
   	$cover_image_path = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');

   	if(empty($cover_image_path)){
   		echo 'Please, first add image.';
   		return;
   	}
   	
   	$fields_query = array(
   			'meta_key'         => 'cover_id',
   			'meta_value'       => $post->ID,
   			'post_type'        => 'fields',
   			'posts_per_page' => 100,
   	);
   	$fields = get_posts($fields_query);
   	
   	$rowsInput = array();
   	$iri = 0;
   	foreach ($fields as $field){
   
   		$custom_field = get_post_custom($field->ID);
  		
   		if(empty($custom_field['input_text_color'][0])){
   			$custom_field['input_text_color'][0] = "000000";
   		}
   		if(empty($custom_field['input_text_size'][0])){
   			$custom_field['input_text_size'][0] = "35";
   		}
   		if(empty($custom_field['input_text_font_id'][0])){
   			$custom_field['input_text_font_id'][0] = "1";
   		}
   		if(empty($custom_field['input_text_angle'][0])){
   			$custom_field['input_text_angle'][0] = "0";
   		}
   		if(empty($custom_field['input_text_margin_left'][0])){
   			$custom_field['input_text_margin_left'][0] = "10";
   		}
   		if(empty($custom_field['input_text_margin_top'][0])){
   			$custom_field['input_text_margin_top'][0] = "10";
   		}
   		
   		if(empty($custom_field['input_text_shadow_x'][0])){
   			$custom_field['input_text_shadow_x'][0] = "0";
   		}
   		if(empty($custom_field['input_text_shadow_y'][0])){
   			$custom_field['input_text_shadow_y'][0] = "0";
   		}
   		if(empty($custom_field['input_text_shadow_blur'][0])){
   			$custom_field['input_text_shadow_blur'][0] = "0";
   		}
   		if(empty($custom_field['input_text_shadow_color'][0])){
   			$custom_field['input_text_shadow_color'][0] = "000000";
   		}
   		
   		$rowsInput[$iri]['id'] = $field->ID;
   		$rowsInput[$iri]['name'] = $custom_field['input_name'][0];
   		$rowsInput[$iri]['text_color'] = $custom_field['input_text_color'][0];
   		$rowsInput[$iri]['text_font_id'] = $custom_field['input_text_font_id'][0];
   		$rowsInput[$iri]['text_size'] = $custom_field['input_text_size'][0];
   		$rowsInput[$iri]['text_angle'] = $custom_field['input_text_angle'][0];
   		$rowsInput[$iri]['text_margin_left'] = $custom_field['input_text_margin_left'][0];
   		$rowsInput[$iri]['text_margin_top'] = $custom_field['input_text_margin_top'][0];
   		$rowsInput[$iri]['text_example'] = $custom_field['input_text_example'][0];
   		
   		$rowsInput[$iri]['text_shadow_x'] = $custom_field['input_text_shadow_x'][0];
   		$rowsInput[$iri]['text_shadow_y'] = $custom_field['input_text_shadow_y'][0];
   		$rowsInput[$iri]['text_shadow_blur'] = $custom_field['input_text_shadow_blur'][0];
   		$rowsInput[$iri]['text_shadow_color'] = $custom_field['input_text_shadow_color'][0];
   		
   		$rowsInput[$iri]['text_centred'] = $custom_field['input_text_centred'][0];
   		
   		$iri++;
   	}
   	
   	if(empty($rowsInput)){
   		echo 'Please, fields for cover.';
   		return;
   	}
   	
   	$rowsInput = array_reverse($rowsInput);
   	
   	list($width_canvas, $height_canvas, $type, $attr) = getimagesize($cover_image_path[0]);
   	$width_canvas = $cover_image_path[1];
   	$height_canvas = $cover_image_path[2];
   	?>
<script src="<?php echo $dir; ?>js/jquery.min.js"></script>
<link rel="stylesheet" media="screen" type="text/css" href="<?php echo $dir; ?>css/grid12.css" />
<link rel="stylesheet" media="screen" type="text/css" href="<?php echo $dir; ?>css/colorpicker.css" />
<style>
   .colorSelector div {
   		width: 100%;
   		height: 29px;
   		border:1px solid #ccc;
   }
   .form-control {
   		font-size: 13px;
    	border: 1px solid #ccc;
    	height: 29px;
		font-size: 14px;
		color: #555;
		width:100%;
	}
	label{
		font-weight:bold;
	}
	.angle_info{
		font-weight:bold;
		color:green;
	}
	.margin_top_info{
		font-weight:bold;
		color:green;
	}
	.margin_left_info{
		font-weight:bold;
		color:green;
	}
	.text_color_info{
		font-weight:bold;
		color:#fff;
	}
	
   <?php foreach(getAllFonts() as $font):?>
   @font-face
   {
   font-family: '<?php echo $font['name'];?>';
   src: url('<?php echo $font['url'];?>');
   }
   <?php endforeach; ?>
</style>
<script src="<?php echo $dir; ?>js/colorpicker.js"></script>

      <div class="row">
         <?php
         $ir_inputs=0;
            foreach($rowsInput as $rowInput) {
             ?>
         <div class="col-md-12" id="rowControls<?php echo $rowInput['id'];?>" style="<?php if($ir_inputs != 0){ ?>display:none;<?php } ?>">
            <label class="control-label"><?php echo $rowInput['name'];?> (settings)</label>
            <br />
            <div class="row">
               <div class="col-md-2"> 
                  Angle: <span id="angle_info<?php echo $rowInput['id'];?>" class="angle_info"><?php echo $rowInput['text_angle'];?></span>
                  <input class="form-control" id="angle-control<?php echo $rowInput['id'];?>" name="InputID[<?php echo $rowInput['id'];?>][text_angle]" value="<?php echo $rowInput['text_angle'];?>" min="0" max="360" type="range"> 
               </div>
               <?php
                  if($rowInput['text_centred'] == 0){
                  ?>
               <div class="col-md-2">
                  Left: <span id="margin_left_info<?php echo $rowInput['id'];?>" class="margin_left_info"><?php echo $rowInput['text_margin_left'];?></span>
                  <input class="form-control" id="left-control<?php echo $rowInput['id'];?>" name="InputID[<?php echo $rowInput['id'];?>][text_margin_left]" value="<?php echo $rowInput['text_margin_left'];?>" min="0" max="<?php echo $width_canvas;?>" type="range">
               </div>
               <?php
                  }
                  ?>
               <div class="col-md-2">
                  Top: <span id="margin_top_info<?php echo $rowInput['id'];?>" class="margin_top_info"><?php echo $rowInput['text_margin_top'];?></span>
                  <input class="form-control" id="top-control<?php echo $rowInput['id'];?>" name="InputID[<?php echo $rowInput['id'];?>][text_margin_top]" value="<?php echo $rowInput['text_margin_top'];?>" min="0" max="<?php echo $height_canvas;?>" type="range">
               </div>
               <div class="col-md-2">
                  Text size: 
                  <select id="text-font-size<?php echo $rowInput['id'];?>" name="InputID[<?php echo $rowInput['id'];?>][text_size]" class="form-control">
                     <option value="<?php echo $rowInput['text_size'];?>"><?php echo $rowInput['text_size'];?></option>
                     <?php
                        for ($i = 9; $i <= 55; $i++) {
                        	?>
                     <option value="<?php echo $i;?>"><?php echo $i;?></option>
                     <?php
                        }?>
                  </select>
               </div>
               <div class="col-md-2">
                  Font:
                  <select id="font-control<?php echo $rowInput['id'];?>" name="InputID[<?php echo $rowInput['id'];?>][text_font_id]" class="form-control">
                     <option value="<?php echo $rowInput['text_font_id'];?>"><?php echo get_the_title($rowInput['text_font_id']);?></option>
                     <option value="Tahoma">- - - -</option>
                     <option value="Tahoma">Tahoma</option>
                     <?php 
                       foreach(getAllFonts() as $font):
                        ?>
                     <option value="<?php echo $font['id'];?>"><?php echo $font['name'];?></option>
                     <?php
                        endforeach;
                        ?>
                  </select>
               </div>
               <div class="col-md-2">
                  Color:
                  <div class="colorSelector" id="csID<?php echo $rowInput['id'];?>">
                     <div style="background-color: #<?php if(empty($rowInput['text_color'])){echo '000';}else{echo $rowInput['text_color'];}?>">
                     <span id="text_color_info<?php echo $rowInput['id'];?>" class="text_color_info">#<?php echo $rowInput['text_color'];?></span>
                     </div>
                  </div>
               </div>
               </div>
               <div class="row">
               
               <div class="col-md-2">
               Shadow Active:
               <select id="shadow-active-control<?php echo $rowInput['id'];?>" name="InputID[<?php echo $rowInput['id'];?>][text_shadow_acitve]" class="form-control">
               <option value="0">No</option>
               <option value="1">Yes</option>
               </select> 
               </div>
               
                <div class="col-md-2">
                  ShadowX: 
                  <input class="form-control" id="shadow-x-control<?php echo $rowInput['id'];?>" name="InputID[<?php echo $rowInput['id'];?>][text_shadow_x]" value="<?php echo $rowInput['text_shadow_x'];?>" min="-10" max="10" type="range">
               </div>
               <div class="col-md-2">
                  ShadowY: 
                  <input class="form-control" id="shadow-y-control<?php echo $rowInput['id'];?>" name="InputID[<?php echo $rowInput['id'];?>][text_shadow_y]" value="<?php echo $rowInput['text_shadow_y'];?>" min="-10" max="10" type="range">
               </div>
               <div class="col-md-2">
                  Shadow Blur: 
                  <input class="form-control" id="shadow-blur-control<?php echo $rowInput['id'];?>" name="InputID[<?php echo $rowInput['id'];?>][text_shadow_blur]" value="<?php echo $rowInput['text_shadow_blur'];?>" min="0" max="15" type="range">
               </div>
               
               <div class="col-md-2">
                  Shadow Color:
                  <div class="colorSelector" id="cs_shadowID<?php echo $rowInput['id'];?>">
                     <div style="background-color: #<?php if(empty($rowInput['text_shadow_color'])){echo '000';}else{echo $rowInput['text_shadow_color'];}?>">
                     <span id="text_color_shadow_info<?php echo $rowInput['id'];?>" class="text_color_info">#<?php echo $rowInput['text_shadow_color'];?></span>
                     </div>
                  </div>
               </div>
               
               
            </div>
            <br />
            <input type="hidden" name="InputID[<?php echo $rowInput['id'];?>][text_shadow_color]" value="<?php echo $rowInput['text_shadow_color'];?>" id="text_shadow_color<?php echo $rowInput['id'];?>" />
            <input type="hidden" name="InputID[<?php echo $rowInput['id'];?>][text_color]" value="<?php echo $rowInput['text_color'];?>" id="text_color<?php echo $rowInput['id'];?>" />
         </div>
         <?php
         $ir_inputs++;
            }
            ?>
      </div>
      <input type="hidden" value="<?php echo $post->ID;?>" name="cover_id" />
      <input type="hidden" name="ajax_action" value="ajaxSaveCover" />
      <button id="ajaxSubmit" class="button button-primary button-large">Save preview settings</button>
      <br />
      <br />
      
<script src="<?php echo $dir; ?>js/fabric.js"></script>
<div class="row"> 
<div class="col-md-12"> 
<canvas width="<?php echo $width_canvas;?>" height="<?php echo $height_canvas;?>" id="ceb"></canvas>
</div>
</div>

<script language="javascript" type="text/javascript">

var $ = function(id){return document.getElementById(id)};          
var src = '<?php echo $cover_image_path[0]; ?>';
            
var scg_fonts = {
<?php foreach(getAllFonts() as $font):?>
<?php echo $font['id'];?>:"<?php echo $font['name'];?>",
<?php endforeach; ?>
};

<?php
foreach($rowsInput as $rowInput) {
?>
var text_shadow<?php echo $rowInput['id'];?> = {
color: '#<?php echo $rowInput['text_shadow_color'];?>', 
blur: <?php echo $rowInput['text_shadow_blur'];?>,
offsetX: <?php echo $rowInput['text_shadow_x'];?>,
offsetY: <?php echo $rowInput['text_shadow_y'];?>
};
<?php
}
?>

jQuery(document).ready(function($) {
			  var canvas = this.__canvas = new fabric.Canvas('ceb');
			  fabric.Object.prototype.transparentCorners = false;
			   canvas.setBackgroundColor({source: src}, function () {
				  canvas.renderAll();
			  });
			  
				<?php
				foreach($rowsInput as $rowInput) {
				?>
			    var text<?php echo $rowInput['id'];?> = new fabric.Text('<?php echo $rowInput['text_example'];?>', {
				fontSize: <?php if(empty($rowInput['text_size'])){echo '17';} else { echo $rowInput['text_size']; }?>,
				<?php
				if($rowInput['text_centred'] == 0){
				?>
				left: <?php echo $rowInput['text_margin_left'];?>,
				<?php
				}else{
			   ?>
				lockMovementX: true,
				<?php
				 }
				 ?>				
				top: <?php echo $rowInput['text_margin_top'];?>,
				lineHeight: 1,
				originX: 'left',
				fontFamily: <?php if($rowInput['text_font_id']==0){echo "'Tahoma'";} else { ?>scg_fonts[<?php echo $rowInput['text_font_id']; ?>]<?php }?>,
				lockScalingX : true,
				lockScalingY : true,
				fill: '#<?php echo $rowInput['text_color'];?>',
				angle: <?php echo $rowInput['text_angle'];?>,
				rowInputId: <?php echo $rowInput['id'];?>
			 });
			canvas.add(text<?php echo $rowInput['id'];?>);


			text<?php echo $rowInput['id'];?>.setShadow({
				color: text_shadow<?php echo $rowInput['id'];?>.color,
				blur: text_shadow<?php echo $rowInput['id'];?>.blur,
				offsetX: text_shadow<?php echo $rowInput['id'];?>.offsetX,
				offsetY: text_shadow<?php echo $rowInput['id'];?>.offsetY
			});
			
			
			<?php
				if($rowInput['text_centred'] != 0){
				?>
				text<?php echo $rowInput['id'];?>.centerH();
				text<?php echo $rowInput['id'];?>.setCoords();
				canvas.renderAll();
			   <?php
				}
				?>
			<?php
			}
			?>
			
			
			<?php
			foreach($rowsInput as $rowInput) {
			?>
			var angleControl<?php echo $rowInput['id'];?> = $('#angle-control<?php echo $rowInput['id'];?>');
			  angleControl<?php echo $rowInput['id'];?>.change(function(){
				text<?php echo $rowInput['id'];?>.setAngle(parseInt(this.value, 10)).setCoords();
				$("#angle_info<?php echo $rowInput['id'];?>").html(parseInt(this.value, 10));
				canvas.renderAll();
			  });
			
			 var topControl<?php echo $rowInput['id'];?> = $('#top-control<?php echo $rowInput['id'];?>');
			  topControl<?php echo $rowInput['id'];?>.change(function() {
				text<?php echo $rowInput['id'];?>.setTop(parseInt(this.value, 10)).setCoords();
				canvas.renderAll();
			  });
			  
			    <?php
				if($rowInput['text_centred'] == 0){
				?>
			  var leftControl<?php echo $rowInput['id'];?> = $('#left-control<?php echo $rowInput['id'];?>');
			  leftControl<?php echo $rowInput['id'];?>.change(function() {
				text<?php echo $rowInput['id'];?>.setLeft(parseInt(this.value, 10)).setCoords();
				console.log('left-controL: '+parseInt(this.value, 10));
				canvas.renderAll();
			  });
			   <?php
				}
				?>
		  	 var textControl<?php echo $rowInput['id'];?> = $('#text-font-size<?php echo $rowInput['id'];?>');
			  textControl<?php echo $rowInput['id'];?>.change(function() {
				text<?php echo $rowInput['id'];?>.setFontSize(parseInt(this.value, 10)).setCoords();
				console.log('change-font-size');
				canvas.renderAll();
			  });
			  var fontControl<?php echo $rowInput['id'];?> = $('#font-control<?php echo $rowInput['id'];?>');
			  fontControl<?php echo $rowInput['id'];?>.change(function() {
				text<?php echo $rowInput['id'];?>.setFontFamily(scg_fonts[this.value]);
				console.log('change-font-style');
				canvas.renderAll();
			  });
			  
			  var shadowxControl<?php echo $rowInput['id'];?> = $('#shadow-x-control<?php echo $rowInput['id'];?>');
			  shadowxControl<?php echo $rowInput['id'];?>.change(function() {
				
				text_shadow<?php echo $rowInput['id'];?>.offsetX = this.value;
				
				text<?php echo $rowInput['id'];?>.setShadow({
					color: text_shadow<?php echo $rowInput['id'];?>.color,
					blur: text_shadow<?php echo $rowInput['id'];?>.blur,
					offsetX: text_shadow<?php echo $rowInput['id'];?>.offsetX,
					offsetY: text_shadow<?php echo $rowInput['id'];?>.offsetY
				});
				
				canvas.renderAll();
			  });
			  
			  var shadowyControl<?php echo $rowInput['id'];?> = $('#shadow-y-control<?php echo $rowInput['id'];?>');
			  shadowyControl<?php echo $rowInput['id'];?>.change(function() {

				text_shadow<?php echo $rowInput['id'];?>.offsetY = this.value;

				text<?php echo $rowInput['id'];?>.setShadow({
					color: text_shadow<?php echo $rowInput['id'];?>.color,
					blur: text_shadow<?php echo $rowInput['id'];?>.blur,
					offsetX: text_shadow<?php echo $rowInput['id'];?>.offsetX,
					offsetY: text_shadow<?php echo $rowInput['id'];?>.offsetY
				});
				
				canvas.renderAll();
			  });

			  var shadowblurControl<?php echo $rowInput['id'];?> = $('#shadow-blur-control<?php echo $rowInput['id'];?>');
			  shadowblurControl<?php echo $rowInput['id'];?>.change(function() {

				text_shadow<?php echo $rowInput['id'];?>.blur = this.value;

				text<?php echo $rowInput['id'];?>.setShadow({
					color: text_shadow<?php echo $rowInput['id'];?>.color,
					blur: text_shadow<?php echo $rowInput['id'];?>.blur,
					offsetX: text_shadow<?php echo $rowInput['id'];?>.offsetX,
					offsetY: text_shadow<?php echo $rowInput['id'];?>.offsetY
				});
				
				canvas.renderAll();
			  });

			  var shadowActiveControl<?php echo $rowInput['id'];?> = $('#shadow-active-control<?php echo $rowInput['id'];?>');
			  shadowActiveControl<?php echo $rowInput['id'];?>.change(function() {

				shadowblurControl<?php echo $rowInput['id'];?>.val(0);
				shadowyControl<?php echo $rowInput['id'];?>.val(0);
				shadowxControl<?php echo $rowInput['id'];?>.val(0);
				  
				text_shadow<?php echo $rowInput['id'];?>.blur = 0;
				text_shadow<?php echo $rowInput['id'];?>.color = "#000000";
				text_shadow<?php echo $rowInput['id'];?>.offsetX = 0;
				text_shadow<?php echo $rowInput['id'];?>.offsetY = 0;
				
				text<?php echo $rowInput['id'];?>.setShadow({
					color: text_shadow<?php echo $rowInput['id'];?>.color,
					blur: text_shadow<?php echo $rowInput['id'];?>.blur,
					offsetX: text_shadow<?php echo $rowInput['id'];?>.offsetX,
					offsetY: text_shadow<?php echo $rowInput['id'];?>.offsetY
				});
				
				canvas.renderAll();
			  });

			  
			<?php
			}
			?>

			 function updateControls() {
				<?php
				foreach($rowsInput as $rowInput) {
				?>
				var angle = (text<?php echo $rowInput['id'];?>.getAngle() % 360).toFixed(2);
				angleControl<?php echo $rowInput['id'];?>.val(text<?php echo $rowInput['id'];?>.getAngle());
				$("#angle_info<?php echo $rowInput['id'];?>").html(parseInt(text<?php echo $rowInput['id'];?>.getAngle(), 10));
				<?php
				if($rowInput['text_centred'] == 0){
				?>
				leftControl<?php echo $rowInput['id'];?>.val(text<?php echo $rowInput['id'];?>.getLeft());
				$("#margin_left_info<?php echo $rowInput['id'];?>").html(parseInt(text<?php echo $rowInput['id'];?>.getLeft(), 10));
				 <?php
				}
				?>
				textControl<?php echo $rowInput['id'];?>.val(text<?php echo $rowInput['id'];?>.getFontSize());
				$("#margin_top_info<?php echo $rowInput['id'];?>").html(parseInt(text<?php echo $rowInput['id'];?>.getTop(), 10));
				topControl<?php echo $rowInput['id'];?>.val(text<?php echo $rowInput['id'];?>.getTop());
				<?php
				}
				?>
			  }
			
			canvas.on({
				'object:moving': updateControls,
				'object:resizing': updateControls,
				'object:rotating': updateControls,
				'object:selected': selectedObject
			  });
			  
				function selectedObject(e) {
					<?php
					foreach($rowsInput as $rowInput) {
					?>
					$("#rowControls<?php echo $rowInput['id'];?>").hide();
					<?php }?>
					$("#rowControls" + e.target.rowInputId).show();
				}
			  
			   window.setTimeout(function(){
				   canvas.renderAll();
			   }, 69);



				$("#ajaxSubmit").click(function(e) {
					//canvasURL = canvas.toDataURL();
					canvasImage = canvas.toDataURL("image/png");
   					e.preventDefault();
   					process();
   				});
   			
   	function process(){
   				var canvasImage = canvas.toDataURL("image/png");
   				var debug=$('#post').serialize();
   				$.ajax({
   					type:'post',
   					url:'<?php echo get_site_url();?>/wp-content/plugins/phpscg/ajax.php',
   					data:debug+"&base_64="+canvasImage,
   					beforeSend:function(){
   						$("#results").html('Generating preview...');
   					},
   					complete:function(){
   						
   					},
   					success:function(result){
   						$("#results").html('<img src="' + result + '"  />');
   						$("#results").html('<div class="alert alert-success">Preview settings are saved.</div>');
   						//window.location.href = "<?php echo get_site_url();?>/wp-admin/post.php?post=<?php echo $post->ID;?>&action=edit";
   						console.log(result);
   						
   					}
   				});
   	}
   	<?php
      foreach($rowsInput as $rowInput) {
      ?>
   					$('#csID<?php echo $rowInput['id'];?>').ColorPicker({
   						color: '#<?php if(empty($rowInput['text_color'])){echo '000';}else{echo $rowInput['text_color'];}?>', 
   						onShow: function (colpkr) {
   							$(colpkr).fadeIn(500);
   							return false;
   						},
   						onHide: function (colpkr) {
   							$(colpkr).fadeOut(500);
   							return false;
   						},
   						onChange: function (hsb, hex, rgb) {
							
   							text<?php echo $rowInput['id'];?>.setColor("#" + hex);
   							canvas.renderAll();
   							
   							document.getElementById("text_color<?php echo $rowInput['id'];?>").setAttribute('value',hex);
   							$('#csID<?php echo $rowInput['id'];?> div').css('backgroundColor', '#' + hex);
   						}
   					});
   					
   					$('#cs_shadowID<?php echo $rowInput['id'];?>').ColorPicker({
   						color: '#<?php if(empty($rowInput['text_shadow_color'])){echo '000';}else{echo $rowInput['text_shadow_color'];}?>', 
   						onShow: function (colpkr) {
   							$(colpkr).fadeIn(500);
   							return false;
   						},
   						onHide: function (colpkr) {
   							$(colpkr).fadeOut(500);
   							return false;
   						},
   						onChange: function (hsb, hex, rgb) {
							
   							text_shadow<?php echo $rowInput['id'];?>.color = "#"+hex;

   							text<?php echo $rowInput['id'];?>.setShadow({
   								color: text_shadow<?php echo $rowInput['id'];?>.color,
   								blur: text_shadow<?php echo $rowInput['id'];?>.blur,
   								offsetX: text_shadow<?php echo $rowInput['id'];?>.offsetX,
   								offsetY: text_shadow<?php echo $rowInput['id'];?>.offsetY
   							});
   							canvas.renderAll();
   							 
   							document.getElementById("text_shadow_color<?php echo $rowInput['id'];?>").setAttribute('value',hex);
   							$('#cs_shadowID<?php echo $rowInput['id'];?> div').css('backgroundColor', '#' + hex);
   						}
   					});
   	<?php
      }
      ?>


			   
});
</script>

<?php
}

function saveBase64Image($img){

	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	
	$data = base64_decode($img);
	$im = imagecreatefromstring($data);
	
	if ($im !== false) {
	
		header('Content-Type: image/jpg');
	
		$upload_dir = wp_upload_dir();
		$randomName = "Generated-Cover-".date("m.d.y").'-'.time();
		imagejpeg($im, $upload_dir['path']."/$randomName.jpg", 100);
		imagedestroy($im);
	
		return $upload_dir['url']."/$randomName.jpg";
	
	} else {
		return false;
	}
}

function ajaxSaveCover(){

	if(!isset($_POST['cover_id'])){
		echo 'Please try again. Invalid id.';
	}
	
	$cover_id = (int) htmlspecialchars($_POST['cover_id']);
	
	if(!is_array($_POST["InputID"])){
		die("error");
	}
	
	foreach($_POST["InputID"] as $key => $value){
		
		$key = (int) $key;
		$text_angle = (int) htmlspecialchars($_POST["InputID"][$key]['text_angle']);
		$text_margin_left = (int) htmlspecialchars($_POST["InputID"][$key]['text_margin_left']);
		$text_margin_top = (int) htmlspecialchars($_POST["InputID"][$key]['text_margin_top']);
		$text_font_id = (int) htmlspecialchars($_POST["InputID"][$key]['text_font_id']);
		$text_size = (int) htmlspecialchars($_POST["InputID"][$key]['text_size']);
		$text_color = htmlspecialchars($_POST["InputID"][$key]['text_color']);
		
		$text_shadow_color = htmlspecialchars($_POST["InputID"][$key]['text_shadow_color']);
		$text_shadow_x = (int) htmlspecialchars($_POST["InputID"][$key]['text_shadow_x']);
		$text_shadow_y = (int) htmlspecialchars($_POST["InputID"][$key]['text_shadow_y']);
		$text_shadow_blur = (int) htmlspecialchars($_POST["InputID"][$key]['text_shadow_blur']);
		
		update_post_meta($key, "input_text_angle", $text_angle);
		update_post_meta($key, "input_text_margin_left", $text_margin_left);
		update_post_meta($key, "input_text_margin_top", $text_margin_top);
		update_post_meta($key, "input_text_font_id", $text_font_id);
		update_post_meta($key, "input_text_size", $text_size);
		update_post_meta($key, "input_text_color", $text_color);
		
		update_post_meta($key, "input_text_shadow_color", $text_shadow_color);
		update_post_meta($key, "input_text_shadow_x", $text_shadow_x);
		update_post_meta($key, "input_text_shadow_y", $text_shadow_y);
		update_post_meta($key, "input_text_shadow_blur", $text_shadow_blur);
		update_post_meta($key, "cover_example_preview", saveBase64Image($_POST['base_64']));
		
	}
	
	die("success");
	
}
add_action('scg_ajax_ajaxSaveCover', 'ajaxSaveCover');