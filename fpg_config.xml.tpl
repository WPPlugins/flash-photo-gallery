<gallery
base = ""
background = "$FPG_bgcolor"
banner = "$FPG_image_border_color"
text = "$FPG_textcolor"
link = "$FPG_textcolor"
alink = "$FPG_textcolor"
vlink = "$FPG_textcolor"
date = "">
	<sitename>$FPG_title</sitename>
	<photographer>$FPG_subtitle1</photographer>
	<contactinfo>$FPG_subtitle2</contactinfo>
	<email></email>
	<security><![CDATA[]]> </security>

<banner font = "Arial" fontsize = "3" color = "$FPG_image_border_color"> </banner>
<thumbnail base ="$FPG_base"> </thumbnail> 
<large base ="$FPG_base"> </large>
<images id = "images">

$FPG_image_tags</images>
</gallery>

<settings>
	<var symboltype="number" name="bordersize" value="$FPG_image_border_thickness" />
	<var symboltype="string" name="imagePreloadColor" value="$FPG_textcolor" />
	<var symboltype="number" name="navSmSize" value="$FPG_thumbnail_size" />
	<var symboltype="number" name="navSpacing" value="$FPG_thumbnail_spacing" />
	<var symboltype="number" name="navBordersize" value="$FPG_thumbnail_border_thickness" />
	<var symboltype="boolean" name="showNavNumbers" value="$FPG_thumbnail_show_number" />
	<var symboltype="boolean" name="showController" value="$FPG_controller_show" />
	<var symboltype="boolean" name="showHelpButton" value="$FPG_controller_show_info" />
	<var symboltype="boolean" name="showNavButton" value="$FPG_controller_show_thumbnail" />
	<var symboltype="number" name="showTime" value="$FPG_controller_pause" />
	<var symboltype="number" name="controlScale" value="$FPG_controller_size" />
	<var symboltype="boolean" name="playSound" value="false" />
	<var symboltype="number" name="soundLoop" value="3" />
	<var symboltype="string" name="mp3file" value="useraudio.mp3" />
	<var symboltype="boolean" name="embedFont" value="true" />
</settings> 