<?php
/*�scar Andreu */

if (!function_exists('add_action'))
	require_once(__DIR__.'/../../../../wp-config.php');

global $wpdb;

$id = $_GET['id'];
$images = null;

if (! empty($id)) {
   //looking for item images
	$item = new ItemsClass();
	$item->id = $id;
	$images = $item->getImages();
}

if($images != null && count($images) > 0){
?>
	<div id="slideshow" style="width: 635px; height: 335px;">
		<ul class="slides" style="width: 625px; height: 320px;">
<?php foreach ( $images as $image) 
{
	echo '<li><img src="'.$image->path.'" width="620" height="320" alt="'.$image->alt_text.'" /></li>';
} 
?>
		</ul>
		<span class="arrow previous"></span>
		<span class="arrow next"></span>
	</div>
<?php 
}else{
	echo '<img src="'.WGO_BASE_URL.'images/no-image-available.png">';
}
?>