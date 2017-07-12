<?php
$FPG_config_path = $_REQUEST['path'];
$FPG_config_file = basename($FPG_config_path);

$FPG_info = explode('_', $FPG_config_file);	
$title = str_replace('.xml','',$FPG_info[2]);
$dimensions = explode('x', $FPG_info[1]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title>Previewing '<?php echo $title;?>'</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<script type="text/javascript" src="swfobject.js"></script>
	</head>
	<body>
		<script type="text/javascript">	
			var flashvars = {photosXmlFile: "<?php echo $FPG_config_path;?>", configXmlFile:"<?php echo $FPG_config_path;?>"};
			var params = {scale: "noscale"};	
			swfobject.embedSWF("gallery.swf", "FPG_28", "100%", "<?php echo $dimensions[1];?>", "6.0.0", "expressInstall.swf", flashvars, params);
		</script>
		<div id="FPG_28">
			<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
		</div>
	</body>
</html>
