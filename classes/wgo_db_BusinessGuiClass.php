<?php

//Dibuja un elemento en la parte administrativa
function drawAdminItem($item) {
	$lResult.= "
		<tr class='datarow'>
			<td>$item->name</td>
			<td>$item->logo</td>
			<td>
				<strong>Descripci&oacute;n:</strong> $item->description<br/>
				<strong>Tel&eacute;fono:</strong> $item->phone<br/>
				<strong>Fax:</strong> $item->fax<br/>
				<strong>movil:</strong> $item->movil<br/>
				<br/><strong>URL:</strong> $item->url<br/>
				<br/><strong>Email:</strong> $item->email<br/>
				<br/><strong>Direcci&oacute;n:</strong> $item->address, $item->city_id, $item->state $item->zip
			</td>
			<td>$item->cat_id</td>
			<td>$item->pkg_id</td>
			<td>$item->time_listed</td>
			<td>$item->time_expired</td>
			<td align='center'>
				<form method='post' action='?page=wgo_settings_edit_listings'>
					<input type='hidden' name='action' value='edit_listing' />
					<input type='hidden' name='wgo_listing_id' value='$item->id' />
					<input type='submit' class='button-secondary' value='Edit Listing' />
				</form>
				<form method='post' action='?page=wgo_settings_edit_listings'>
					<input type='hidden' name='action' value='delete_listing' />
					<input type='hidden' name='wgo_listing_id' value='$item->id' />
					<input type='submit' class='button-secondary' value='Delete Listing' />
				</form>
				<form method='post' action='?page=wgo_settings_edit_images'>
					<input type='hidden' name='action' value='edit_images' />
					<input type='hidden' name='wgo_listing_id' value='$item->id' />
					<input type='submit' class='button-secondary' value='Add/Edit Images' />
				</form>
			</td>
		</tr>";

	return $lResult;
}

//Dibuja un item
function drawItem($item) {
	if (! empty($item->logo_url)) {
		$logo = '<img src="' . stripslashes($item->logo_url) . '"/>';
	} else {
		$logo = '<img src="' . plugins_url() . '/wgo-business-directory/images/no-image-available.png"/>';
	}

	$selected = 'wgo-listing-wrapper';
	if ($item->pkg_id > 1)
		$selected .= '-Destacado';

	$lResult.='<div id="'.$selected.'">';
	if ($item->pkg_id > 1)
		$lResult.="<div id='wgo-listing-destacado'><img src='".content_url('plugins/wgo-business-directory/images/')."destacado.png'></div>";

	if ( is_user_logged_in()){
		$lResult .= '<form method="post" action="'.admin_url('admin.php?page=wgo_settings_edit_listings').'">
		<input type="hidden" name="action" value="edit_listing" />
		<input type="hidden" name="wgo_listing_id" value="'.$item->id.'" />
		<input type="submit" class="button-primary" value="Modificar datos" />
		</form>';
	}
	
	$lResult.='
	<div class="wgo-logo">
		<a href="'.$item->url.'" target="_blank">'.$logo.'</a>
		<div style="clear:both;"></div>
	</div>
	<div class="wgo-content">
	<div class="listing-title"><a href="'.$item->url.'" target="_blank">'.$item->name.'</a>';
	
	$lResult .= '</div>
	<div class="listing-description">'.$item->description.'</div>
	<div class="listing-contact-left">
	<div class="listing-direccion"><strong>Dirección: </strong>'.$item->address.' - '.$item->city.', '.$item->zip.' '.$item->state.'</div>
	</div>
	<br>';
	if (! empty($item->url)) {
		$lResult.=
		'<div class="listing-web"><strong>Web: </strong><a href="'.$item->url.'" target="_blank">'.str_replace("http://", "", $item->url).'</a></div>';
	}

	$lResult.= '
	</div>
	<div class="tabs_panel">
	<ul class="tabs">
	<li id="tab_view_contact_'.$item->id.'" class="tab_view"><a href="javascript:hide_all('.$item->id.'),show(\'contact\','.$item->id.');" rel="nofollow" >Contacto</a></li>';
	if($item->coordinates != null)
		$lResult.= '
		<li id="tab_view_map_'.$item->id.'" class="tab_view"><a href="javascript:hide_all('.$item->id.'),showMap('.$item->id.');" rel="nofollow" >Ver mapa</a></li>';
	/*$lResult.= '
	<li id="tab_view_share_'.$item->id.'" class="tab_view"><a href="javascript:hide_all('.$item->id.'),show(\'share\','.$item->id.');" rel="nofollow" >Compartir</a></li>
	<li id="tab_view_pics_'.$item->id.'" class="tab_view"><a href="javascript:showImages('.$item->id.',\''.$item->name.'\');" rel="nofollow" >Imágenes</a></li>';
	*/
	$lResult.= ' </ul>
	</div>';

	$lResult.= '
	<div class="frameToChange" id="view_contact_'.$item->id.'" style="display:none;clear:both;">
	<div id="cerrar"><img id="icon_close" src="'.content_url('plugins/wgo-business-directory/images/').'ico-close.png" onClick="javascript:hide_all('.$item->id.');" /></div>
	<div class="listing-telefonos"><strong>Teléfono: </strong>'.$item->phone.'<br /><strong>Fax: </strong>'.$item->fax.'<br /><strong>Móvil: </strong>'.$item->movil.'</div>
	<div class="listing-email"><strong>Email: </strong><a href="mailto:"'.$item->email.'">'.$item->email.'</a></div>
	</div>';

	if($item->coordinates != null)
		$lResult.= '
		<div class="frameToChange" id="map_container_'.$item->id.'" style="display: none;clear:both;" >
		<div id="cerrar"><img id="icon_close" src="'.content_url('plugins/wgo-business-directory/images/').'ico-close.png" onClick="javascript:hide_all('.$item->id.');" /></div>
		<iframe class="iframeMap" id="view_map_'.$item->id.'"></iframe>
		</div>';
/*
	if (! (empty($item->facebook_url) && empty($item->twitter_url) && empty($item->youtube_url) && empty($item->rss_url))){
		$lResult.= '
		<div class="frameToChange" id="view_share_'.$item->id.'" style="display: none;clear:both;" >
		<div id="cerrar"><img id="icon_close" src="'.content_url('plugins/wgo-business-directory/images/').'ico-close.png" onClick="javascript:hide_all('.$item->id.');" /></div>
		<div id="scWidget">
		<div class="compartir">';
		if (! empty($item->facebook_url)) $lResult.= '<a href="'.$item->facebook_url.'" ><img src="'.content_url('plugins/wgo-business-directory/images/').'facebook.png" border="0" ></a> ';
		if (! empty($item->twitter_url)) $lResult.= '<a href="'.$item->twitter_url.'" ><img src="'.content_url('plugins/wgo-business-directory/images/').'twitter.png" border="0" ></a> ';
		if (! empty($item->youtube_url)) $lResult.= '<a href="'.$item->youtube_url.'" ><img src="'.content_url('plugins/wgo-business-directory/images/').'youtube.png" border="0" ></a> ';
		if (! empty($item->rss_url)) $lResult.= '<a href="'.$item->rss_url.'" ><img src="'.content_url('plugins/wgo-business-directory/images/').'feed.png" border="0" ></a> ';
		$lResult.= '		</div>
		</div>
		</div>';
	}
*/
	$lResult.= '<div class="frameToChange" id="view_images_'.$item->id.'" style="display: none;clear:both;" >

	<div id="cerrar"><img id="icon_close" src="'.content_url('plugins/wgo-business-directory/images/').'ico-close.png" onClick="javascript:hide_all('.$item->id.');" /></div>
	'.$logo.
	'</div>
	</div>';

	return $lResult;
}

function doQuery($package, $category, $city, $registroInicial) {
	global $wpdb;

	$count = 0;
	$items = ItemsClass::searchInfo($package, $category, $city, $registroInicial, $count);

	$return .= getPanelPaginacion($registroInicial, $count, 'Empresas', 'frontEnd');

	foreach ($items as $item) {
		$return .= drawItem($item);
	}

	return $return;
}

function getAdminListingData($package, $category, $city, $registroInicial, $active = "1"){
	global $wpdb;

	$count = 0;
	$items = ItemsClass::searchInfo($package, $category, $city, $registroInicial, $count, $active);

	$return .= getPanelPaginacion($registroInicial, $count, 'Empresas', 'backEnd');

	$return .=  '
		<div id="adminListingData" class="adminListingData">
			<table class="pbladmin">
				<tr class="headrow">
					<td>Nombre</td>
					<td>Logo</td>
					<td>Listing Content</td>
					<td width="35">Categor&iacute;a</td>
					<td>Paquete</td>
					<td>Listado</td><td>Caduca</td>
					<td>&nbsp;</td>
				</tr>';	
	
	foreach ($items as $item) {
		$return .= drawAdminItem($item);
	}
	
	$return .=  '</table></div>';

	return $return;
}

function DrawItemEditable($item, $action){
	
	$location = "28.674173,-17.8";
	$mensaje_boton = ($action =="update_listing") ? "Actualizar" : "Crear elemento";
	$form_action = ($action =="update_listing") ? "wgo_settings_edit_listings" : "wgo_settings_listings";
	?>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script	type="text/javascript"	src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

	jQuery(document).ready(function() {
		var uploadID = '';
	
		jQuery('#').click(function() {
			uploadID = jQuery(this).prev('input');
			formfield = jQuery('#wgo_listing_logo_url').attr('name');
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
			return false;
		}); 
		
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			uploadID.val(imgurl);
			tb_remove();
		}
	
	});

	var geocoder = new google.maps.Geocoder();
	
	function geocodePosition(pos) {
	  geocoder.geocode({
	    latLng: pos
	  }, function(responses) {
	    if (responses && responses.length > 0) {
	      updateMarkerAddress(responses[0]);
	    } else {
	      updateMarkerAddress('Cannot determine address at this location.');
	    }
	  });
	}

	function updateMarkerPosition(latLng) {
	  document.getElementById('item_location').innerHTML = [
	    latLng.lat(),
	    latLng.lng()
	  ].join(', ');
	}

	function updateMarkerAddress(data) {
		document.getElementById('wgo_listing_address').value = data.address_components[1].long_name + ', ' + data.address_components[0].long_name;
	  //document.getElementById('wgo_listing_state').value = data.address_components[3].long_name;
	  //document.getElementById('wgo_listing_zip').value = data.address_components[5].long_name;
	
	  //jQuery("select[name='wgo_listing_cities_id'] option[value='"+data.address_components[2]+"']").attr("selected", true);
	  //jQuery("select[name='wgo_listing_cities_id'] option[contains='"+data.address_components[2]+"']").attr("selected", true);
	}

	function initialize() {
		  var latLng = new google.maps.LatLng(<?php echo empty($item->coordinates) ? $location : $item->coordinates ?>);
		  var map = new google.maps.Map(document.getElementById('mapCanvas'), {
		    zoom: 15,
		    center: latLng,
		    mapTypeId: google.maps.MapTypeId.ROADMAP
		  });
		  var marker = new google.maps.Marker({
		    position: latLng,
		    title: '<?php echo $item->name ?>',
		    map: map,
		    draggable: true
		  });

  
	  google.maps.event.addListener(marker, 'dragend', function() {
	    geocodePosition(marker.getPosition());
	  });
	}

	// Onload handler to fire off the app.
	google.maps.event.addDomListener(window, 'load', initialize);
</script>

  <style>
  #mapCanvas {
    width: 380px;
    height: 450px;
    float: right;
  }

  #infoPanel {
  	float: left;
    margin-left: 10px;
    margin-bottom: 5px;
  }
  </style>
  
 <div id="editable_listing">
	<div id="mapCanvas"></div>
	<div id="infoPanel">
	<form method="post" action="?page=<?php echo $form_action;?>">
	<input type="hidden" name="action" value="<?php echo $action;?>" />
	<input type="hidden" name="id" value="<?php echo $item->id;?>" />
	<table class="form-table">
			<tr valign="top">
				<th scope="row">Nombre</th>
				<td><input type="text" name="wgo_listing_name" value="<?php echo $item->name; ?>" size="56" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Logo</th>
				<td>
					<input type="text" name="wgo_listing_logo_url" value="<?php echo $item->logo_url; ?>" size="56" />
					<spam class="submit">
						<input id="upload_image_button" type="button" value="Seleccionar" />
					</spam>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Descripción</th>
				<td><textarea id ="wgo_listing_description" name="wgo_listing_description" cols="50" rows="2"> <?php echo $item->description; ?> </textarea></td>
			</tr>
			<tr valign="top">
				<th scope="row">Teléfono</th>
				<td><input type="text" id ="wgo_listing_phone" name="wgo_listing_phone" value="<?php echo $item->phone; ?>" size="56" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Fax</th>
				<td><input type="text" id ="wgo_listing_fax"  name="wgo_listing_fax" value="<?php echo $item->fax; ?>" size="56" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Teléfono móvil</th>
				<td><input type="text" id ="wgo_listing_movil"  name="wgo_listing_movil" value="<?php echo $item->movil; ?>" size="56" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Página web</th>
				<td><input type="text" id ="wgo_listing_url"  name="wgo_listing_url" value="<?php echo $item->url; ?>" size="56" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">e-mail</th>
				<td><input type="text" id ="wgo_listing_email" name="wgo_listing_email" value="<?php echo $item->email; ?>" size="56" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Dirección</th>
				<td>
					<input type="text" id ="wgo_listing_address" name="wgo_listing_address" value="<?php echo $item->address; ?>" size="56" />
				 	<input type = "checkbox" id = "lock_direction" value = "false" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Municipio/Provincia/Código postal</th>
				<td>
				<?php 
					echo CityClass::getCityList($item->city_id);
				?> 
				<input type='text' id ='wgo_listing_state' name='wgo_listing_state' value='<?php echo $item->state; ?>' />
				<input type="text" id ="wgo_listing_zip" name="wgo_listing_zip" value="<?php echo $item->zip; ?>" size="10" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Categoría</th>
				<td><?php echo CategoryClass::getCategoryList($item->cat_id); ?> </td>
			</tr>
			<tr valign="top">
				<th scope="row">Modalidad de anuncio</th>
				<td><input type="hidden" name="initial_pkg_id"
					value="<?php echo $item->pkg_id; ?>" /> <?php echo PackageClass::getPackageList($item->pkg_id); ?>
				</td>
			</tr>
			<input type="hidden" name="initial_time_listed" value="<?php echo $item->time_listed; ?>" />
			<input type="hidden" name="initial_time_expired" value="<?php echo $item->time_expired; ?>" />
			<input type="hidden" name="item_location" value="<?php echo $item->time_listed; ?>" />
	
		</table>
		<p class="submit">
        	<input type="submit" class="button-primary" value="<?php echo $mensaje_boton; ?>" />
        </p>
        </form>
	</div>
</div>
<!-- editable_listing -->

<?php
}
?>