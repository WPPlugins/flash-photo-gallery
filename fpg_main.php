<?php
/*
Plugin Name: Flash Photo Gallery
Plugin URI: http://webdlabs.com/projects/flash-photo-gallery
Description: Creates a Flash Photo Gallery like one provided in Adobe Photoshop CS2 Flash Web Photo Gallery templates. 
Author: Akshay Raje
Version: 0.7
Author URI: http://webdlabs.com

*/

require_once('fpg_includes.php');

function FPG_menu(){ 
	global $wp_version;
	if (function_exists('add_submenu_page')) {
		wp_enqueue_script('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('colorpicker');	
		wp_enqueue_script('fpg_scripts','/'.PLUGINDIR.'/'.'flash-photo-gallery/fpg_scripts.js');
		wp_enqueue_style('thickbox');
		if((float)substr($wp_version, 0, 3) >= 2.7) { 
        	add_submenu_page('upload.php', 'Flash Photo Gallery', 'Flash Photo Gallery', 2,'flash-photo-gallery/fpg_main','FPG_page');
		} else {
        	add_submenu_page('edit.php', 'Flash Photo Gallery', 'Flash Photo Gallery', 2,'flash-photo-gallery/fpg_main','FPG_page');			
		}
    }
}

function FPG_func($atts) {
	extract(shortcode_atts(array(
		'id' => 'no id',
		'width' => '100%',
		'height' => '',
	), $atts));
	if( false == ($FPG_config_file = @file_get_contents(get_fpg_path('FPG_base_dir','r').'flash-photo-gallery/'.$id))) {
		return "Gallery '$id' not found.";
	} else {
		$FPG_swfobject = htmlspecialchars(file_get_contents(get_fpg_path('FPG_path','r').'fpg_swfobject.inc.tpl'));	
		$galleryswf = get_fpg_path('FPG_path','r').'gallery.swf';
		$FPG_config_file = get_fpg_path('FPG_base_dir','r').'flash-photo-gallery/'.$id;
		$FPG_rand = 'FPG_'.rand(10,99);
		$FPG_info = explode('_', $id);
		$dimensions = explode('x', $FPG_info[1]);
		$dimensions[0] = $width;
		if($height != '') $dimensions[1] = $height;
		eval("\$FPG_swfobject = \"$FPG_swfobject\";");
		return htmlspecialchars_decode($FPG_swfobject);
	}
}

function get_file_option_list($path){
	$scandirarray = scandir($path);
	$filelist = '';
	for($i = 0; $i < sizeof($scandirarray); ++$i){
		if($scandirarray[$i] != "." && $scandirarray[$i] != "..") {
			$filelist .= "		<option value=\"".$scandirarray[$i]."\";>";
			$FPG_fileinfo = explode('_', $scandirarray[$i]);
			$FPG_title = str_replace('.xml','',$FPG_fileinfo[2]);
			$FPG_title = str_replace('-',' ',$FPG_title);			
			$FPG_filedate = $FPG_fileinfo[0];
			$filelist .= $FPG_title.' (Created: '.date('M j, y h:i A',$FPG_filedate).')';
			$filelist .= "</option>\n";
		}
	}
	if($filelist == '') $filelist = "<option value=\"Null\">No gallaries found</option>";
	return $filelist;
}

function create_FPG_config() {
	global $FPG_height;
	global $FPG_width;	
	$FPG_config = htmlspecialchars(file_get_contents('../'.get_fpg_path('FPG_path','w').'fpg_config.xml.tpl'));
	$FPG_title = $_REQUEST['FPG_title'];
	$FPG_bgcolor = $_REQUEST['FPG_bgcolor'];
	$FPG_textcolor = $_REQUEST['FPG_textcolor'];
	$FPG_subtitle1 = $_REQUEST['FPG_subtitle1'];
	$FPG_subtitle2 = $_REQUEST['FPG_subtitle2'];
	$FPG_image_border_color = $_REQUEST['FPG_image_border_color'];
	$FPG_image_border_thickness = $_REQUEST['FPG_image_border_thickness'];
	$FPG_thumbnail_size = $_REQUEST['FPG_thumbnail_size'];
	$FPG_thumbnail_spacing = $_REQUEST['FPG_thumbnail_spacing'];
	$FPG_thumbnail_border_thickness = $_REQUEST['FPG_thumbnail_border_thickness'];
	if ($_REQUEST['FPG_thumbnail_show_number'] == 1) {$FPG_thumbnail_show_number = 'true';} else {$FPG_thumbnail_show_number = 'false';};
	if ($_REQUEST['FPG_controller_show'] == 1) {$FPG_controller_show = 'true';} else {$FPG_controller_show = 'false';};
	if ($_REQUEST['FPG_controller_show_info'] == 1) {$FPG_controller_show_info = 'true';} else {$FPG_controller_show_info = 'false';};
	if ($_REQUEST['FPG_controller_show_thumbnail'] == 1) {$FPG_controller_show_thumbnail = 'true';} else {$FPG_controller_show_thumbnail = 'false';};	
	$FPG_controller_pause = $_REQUEST['FPG_controller_pause'];
	$FPG_controller_size = $_REQUEST['FPG_controller_size'];
	$FPG_base = get_fpg_path('FPG_base_dir','r');	
	
	$FPG_image_array = explode('|',$_REQUEST['FPG_image_url_collection']);
	$FPG_image_title_array = explode('|',$_REQUEST['FPG_image_title_collection']);
	for($i = 0; $i < sizeof($FPG_image_array); ++$i){
		$FPG_image_tags .= "<image\n";
		$FPG_image_tags_array[$i]['path'] = explode(get_fpg_path('FPG_base_dir','r'), $FPG_image_array[$i]);
		$FPG_image_tags .= "path = \"" . $FPG_image_tags_array[$i]['path'][1] . "\"\n";
		$FPG_image_tags_array[$i]['width'] = getimagesize($FPG_image_array[$i]);
		$FPG_image_tags .= "width = \"" . $FPG_image_tags_array[$i]['width'][0] . "\"\n";
		if ($FPG_image_tags_array[$i]['width'][0] > $FPG_width) $FPG_width = $FPG_image_tags_array[$i]['width'][0];
		$FPG_image_tags_array[$i]['height'] = getimagesize($FPG_image_array[$i]);
		$FPG_image_tags .= "height = \"" . $FPG_image_tags_array[$i]['height'][1] . "\"\n";
		if ($FPG_image_tags_array[$i]['height'][1] > $FPG_height) $FPG_height = $FPG_image_tags_array[$i]['height'][1];
		$find = array('.jp','.gif','.png');
		$replace = array('-'.get_option('thumbnail_size_w').'x'.get_option('thumbnail_size_h').'.jp','-'.get_option('thumbnail_size_w').'x'.get_option('thumbnail_size_h').'.gif','-'.get_option('thumbnail_size_w').'x'.get_option('thumbnail_size_h').'.png');
		$FPG_image_tags .= "thumbpath = \"" . str_replace($find, $replace, $FPG_image_tags_array[$i]['path'][1]) . "\"\n";
		$FPG_image_tags .= "thumbwidth = \"" . get_option('thumbnail_size_w') . "\"\n";
		$FPG_image_tags .= "thumbheight = \"" . get_option('thumbnail_size_h') . "\">\n";
		$FPG_image_tags .= "\t<meta name = \"title\"><![CDATA[".$FPG_image_title_array[$i]."]]></meta>\n";
		$FPG_image_tags .= "</image>\n\n";
	}
	eval("\$FPG_config = \"$FPG_config\";");
	return htmlspecialchars_decode($FPG_config);
}

function rmkdir($pathname, $mode = 0755) {
    is_dir(dirname($pathname)) || rmkdir(dirname($pathname), $mode);
    return is_dir($pathname) || @mkdir($pathname, $mode);
}

$FPG_height = 0;
$FPG_width = 0;

add_action('admin_menu', 'FPG_menu');
add_shortcode('fpg', 'FPG_func');
wp_enqueue_script('swfobject','/'.PLUGINDIR.'/'.'flash-photo-gallery/swfobject.js');

function FPG_page() {
global $FPG_height;
global $FPG_width;
if (function_exists('add_thickbox')) add_thickbox();
?>
<div class="wrap">

	<?php if(function_exists(screen_icon)) {screen_icon();} ?>
	<h2>Flash Photo Gallery</h2>
	<small>Powered by <a href="http://webdlabs.com" target="_blank">webdlabs.com</a>. Please <a href="http://webdlabs.com/projects/donate/" target="_blank">donate (by paypal)</a> if you found this useful.</small>
	
<?php
if ($_REQUEST['do'] == 'create' || $_REQUEST['do'] == 'delete') {
	if ($_REQUEST['do'] == 'create') {
		$FPG_file_contents = create_FPG_config();
		$FPG_height = $FPG_height + ($_REQUEST['FPG_thumbnail_size'] * 3) + 180;
		$FPG_width = $FPG_width + 40;	
		$FPG_config_file = time().'_'.$FPG_width.'x'.$FPG_height.'_'.str_replace(' ','-',$_REQUEST['FPG_title']).'.xml';
		rmkdir(get_fpg_path('FPG_base_dir','w').'flash-photo-gallery');
		file_put_contents(get_fpg_path('FPG_base_dir','w').'flash-photo-gallery'.'/'.$FPG_config_file, $FPG_file_contents);	
	?>
	
	<h3>Gallery '<?php echo($_REQUEST['FPG_title']);?>' created successfully! [<a href="<?php echo get_fpg_path('FPG_path','r');?>fpg_preview.php?path=<?php echo get_fpg_path('FPG_base_dir','r').'flash-photo-gallery/'.$FPG_config_file;?>" target="_blank">Preview</a>]</h3>
	<p style="margin-top:4px;">
	Just insert the short code - [fpg id="<?php echo(time().'_'.$FPG_width.'x'.$FPG_height.'_'.str_replace(' ','-',$_REQUEST['FPG_title']).'.xml');?>"] in any page or post of your choice to display the photo gallery.<br />
	You can also retrive the gallery codes from 'Manage > Flash Photo Gallery'.
	</p>	

	<?php 
	} elseif ($_REQUEST['do'] == 'delete') {
		$FPG_config_file = $_REQUEST['FPG_file'];
		unlink(get_fpg_path('FPG_base_dir','w').'flash-photo-gallery'.'/'.$FPG_config_file);
		$FPG_fileinfo = explode('_', $FPG_config_file);
		$FPG_title = str_replace('.xml','',$FPG_fileinfo[2]);	
	?>

	<h3>Gallery '<?php echo $FPG_title;?>' deleted! </h3>
	<p style="margin-top:4px;">
	Do you want to create a new photo gallery? <a href="?page=flash-photo-gallery/fpg_main">Click here to proceed...</a>
	</p>
		
<?php } } else { ?>	
	
	<p style="margin-top:4px;">
	This will create a simple flash Photo Gallery like one provided in Photoshop CS2. You can create multiple galleries which can be placed anywhere in your pages or posts.
	</p>

	<h3>Manager your existing galleries</h3>
	<form action="?page=flash-photo-gallery/fpg_main.php" method="post">
	<table class="form-table">
		<tr valign="top">
		<th scope="row">Existing photo galleries</th>
		<td>	
		<select name="FPG_file" id="FPG_file" onchange="get_shortcode(jQuery('#FPG_file option:selected').val());">
		<?php
		echo get_file_option_list(get_fpg_path('FPG_base_dir','w').'/'.'flash-photo-gallery');
		?>
		</select>
		<br />
		Short code: <span id="FPG_shortcode" style="font-weight:bold"></span>
		</td>
		</tr>
	</table>
	<br />
	<input name="do" id="FPG_file_do" type="hidden" value="" />
	<input type="button" class="button" value="Get Code" onclick="get_shortcode(jQuery('#FPG_file option:selected').val()); return false;"/> <input type="button" class="button" value="Preview" onclick="window.open('<?php echo get_fpg_path('FPG_path','r');?>fpg_preview.php?path=<?php echo get_fpg_path('FPG_base_dir','r').'flash-photo-gallery/';?>'+jQuery('#FPG_file option:selected').val()); return false;"/> <input type="submit" class="button" value="Delete" onclick="jQuery('#FPG_file_do').val('delete'); return validate_edit(jQuery('#FPG_file option:selected').val());"/>
	</form>
	
	<h3>Create a new photo gallery</h3>
	<p style="margin-top:4px;">
	Populate a couple of mandatory fields below, then add photos and thats it. You've got yourself a photo gallery. Use the 'Advanced options...' link below to further finetune the look and feel of your photo gallery.
	</p>

	<form action="?page=flash-photo-gallery/fpg_main.php" method="post">

	<table class="form-table">
		<tr valign="top">
		<th scope="row">Name of your photo gallery</th>
		<td><input name="FPG_title" type="text" id="FPG_title" class="code" value="" size="30" autocomplete="off"/><br />
		(Optional but recommended) This will be diaplayed at the top of your gallery.
		</td>
		</tr>

		<tr valign="top">
		<th scope="row">Background color</th>
		<td><input name="FPG_bgcolor" type="text" id="FPG_bgcolor" class="code" value="#FFFFFF" size="10" autocomplete="off"/> <a href="#" onClick="cp.select(document.getElementById('FPG_bgcolor'),'pick1');return false;" name="pick1" id="pick1">Pick</a> background color of the gallery. You may want to keep this same as your theme background.
		</td>
		</tr>	

		<tr valign="top">
		<th scope="row">Text color</th>
		<td><input name="FPG_textcolor" type="text" id="FPG_textcolor" class="code" value="#000000" size="10" autocomplete="off"/> <a href="#" onClick="cp.select(document.getElementById('FPG_textcolor'),'pick2');return false;" name="pick2" id="pick2">Pick</a> text color of text in the gallery.
		</td>
		</tr>	
	</table>

	<br />
	<strong><a href="#" id="FPG_advanced_link" onclick="return false;">Advanced options...</a></strong>
	<div id="FPG_advanced_options" style="display:none">
	<table class="form-table">
		<tr valign="top">
		<th scope="row">Sub titles</th>
		<td>
		<input name="FPG_subtitle1" type="text" id="FPG_subtitle1" class="code" value="" size="30" autocomplete="off"/><br />
		<input name="FPG_subtitle2" type="text" id="FPG_subtitle2" class="code" value="" size="30" autocomplete="off"/><br />
		(Optional) You can put two subtitles below the gallery title. Can be used for photographer's name, location, dates etc.
		</td>
		</tr>

		<tr valign="top">
		<th scope="row">Image display</th>
		<td>
		<input name="FPG_image_border_color" type="text" id="FPG_image_border_color" class="code" value="#F0F0F0" size="10" autocomplete="off"/> <a href="#" onClick="cp.select(document.getElementById('FPG_image_border_color'),'pick3');return false;" name="pick3" id="pick3">Pick</a> color of border around main image<br />
		<input name="FPG_image_border_thickness" type="text" id="FPG_image_border_thickness" class="code" value="8" size="10" autocomplete="off"/> Thickness (in pixels) of border around main image
		</td>
		</tr>	

		<tr valign="top">
		<th scope="row">Thumbnail view</th>
		<td>
		<input name="FPG_thumbnail_size" type="text" id="FPG_thumbnail_size" class="code" value="30" size="10" autocomplete="off"/> Width and height (in pixels) for the thumbnail size<br />
		<input name="FPG_thumbnail_spacing" type="text" id="FPG_thumbnail_spacing" class="code" value="4" size="10" autocomplete="off"/> Width (in pixels) of the spacing between individual thumbnails<br />
		<input name="FPG_thumbnail_border_thickness" type="text" id="FPG_thumbnail_border_thickness" class="code" value="1" size="10" autocomplete="off"/> Thickness (in pixels) of border aroundindividual thumbnails<br />
		<input name="FPG_thumbnail_show_number" type="checkbox" id="FPG_thumbnail_show_number" value="1" checked="checked"/> Show the image numbers before the filename/title
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row">Controls</th>
		<td>
		<input name="FPG_controller_show" type="checkbox" id="FPG_controller_show" value="1" checked="checked"/> Open the controller menu by default<br />
		<input name="FPG_controller_show_info" type="checkbox" id="FPG_controller_show_info" value="1" checked="checked"/> Display info toggle button<br />
		<input name="FPG_controller_show_thumbnail" type="checkbox" id="FPG_controller_show_thumbnail" value="1" checked="checked"/> Display thumbnail toggle button<br />
		<input name="FPG_controller_pause" type="text" id="FPG_controller_pause" class="code" value="4" size="10" autocomplete="off"/> Pause time (in seconds) between each image<br />
		<input name="FPG_controller_size" type="text" id="FPG_controller_size" class="code" value="120" size="10" autocomplete="off"/> Controller scale (in percentage)
		</td>
		</tr>		
		
	</table>
	</div>

	<table class="form-table">
	
		<tr valign="top">
		<th scope="row">Add photos from Media</th>
		<td><div name="FPG_image_url_display" id="FPG_image_url_display" style="width:500px; background-color:#FFFFFF; padding:3px; border:#c6d9e9 1px solid; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px">No images selected</div> 
		<img src='images/media-button-image.gif' alt='Add photos from your media' /> <a href="media-upload.php?TB_iframe=true&amp;type=image&amp;tab=library&amp;height=500&amp;width=640" class="thickbox" title='Add an Image'><strong>Click here to add photos from your media</strong></a>
		</td>
		</tr>	
		
	</table>
	
	<br />
	<input type="hidden" name="do" value="create" />
	<input type="hidden" name="FPG_image_url_collection" id="FPG_image_url_collection" value="" />
	<input type="hidden" name="FPG_image_title_collection" id="FPG_image_title_collection" value="" />	
	<input type="submit" value="Create Gallery" class="button button-primary" onclick="jQuery('#FPG_image_url_collection').val(image_url_collection.join('|')); jQuery('#FPG_image_title_collection').val(image_title_collection.join('|')); return true;"/>
	
	</form>
    
	<textarea class='' rows='0' cols='0' name='content' tabindex='2' id='content' onfocus="image_url_add()" style="width:1px; height:1px; padding:0px; border:none"></textarea>
    <script type="text/javascript">edCanvas = document.getElementById('content');</script>

</div>	

<?php } } ?>