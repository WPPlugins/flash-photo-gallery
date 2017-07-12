<?php

global $blog_id;

function get_fpg_path($pathname, $wr){
	global $blog_id;
	if($pathname == 'FPG_base_dir' && $wr == 'w'){
		if (function_exists('get_blog_option')) {
			$path = '../'.get_blog_option($blog_id, 'upload_path').'/';
		} else {
			$wud = wp_upload_dir();
			$path = $wud['basedir'].'/';
		}	
	}
	if($pathname == 'FPG_base_dir' && $wr == 'r'){
		if (function_exists('get_blog_option')) {
			$path = get_blog_option($blog_id, 'fileupload_url').'/';
		} else {
			$wud = wp_upload_dir();
			$path = $wud['baseurl'].'/';
		}	
	}
	if($pathname == 'FPG_path' && $wr == 'w'){
		$path = PLUGINDIR.'/'.'flash-photo-gallery'.'/';
	}
	if($pathname == 'FPG_path' && $wr == 'r'){
		$path = get_option('siteurl').'/'.PLUGINDIR.'/'.'flash-photo-gallery'.'/';
	}
	return $path;
}

?>