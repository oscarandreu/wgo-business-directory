<?php
/* Oscar Andreu */

// header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
 global $wpdb;
 
if (!function_exists('add_action'))
	require_once(__dir__.'/../../../../wp-config.php');
include_once(__dir__.'/classes/Utils.php');

$location = "28.65,-17.86";
$zoom = 11;
//CityClass::getWeatherLogos();
//looking for location and zoom if city exists
if(! empty($_GET['city']))
{
	$city = CityClass::searchCities($_GET['city']);
	$location = $city->coordinates;
	$zoom = $city->zoom;
}else {	
	$items = CityClass::searchCities();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<title>Mapa del tiempo</title>
	
	<link href="<?php echo plugins_url().'/wgo-business-directory/css/weather.css' ?>"	rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" media="screen" href="http://www.aemet.es/css/estilos_201202151052.css" />
		
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<?php echo '<script src="'.plugins_url().'/wgo-business-directory/js/markerwithlabel.js"></script>'; ?>
	
	<script type="text/javascript">
	
	function initialize() {
	  var myOptions = {
	    zoom: <?php echo $zoom ?>,
	    center: new google.maps.LatLng(<?php echo $location ?>),
	    mapTypeId: google.maps.MapTypeId.HYBRID
	  }
	  var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
	<?php 
	
	echo 'var arr = '.json_encode($items).';';
	
	echo 'var arrLogos = new Array('.count($items).');';
	for($i = 0; $i < count($items); $i++){
		echo "arrLogos[$i]='".$items[$i]->weather_logo."'\n";	
	}
	?>
	
	infos = [];
	
	for(var i=0;i<arr.length;i++){
	    var obj = arr[i];     
	
		  var pictureLabel = document.createElement("div");
		  pictureLabel.innerHTML = arrLogos[i];
	
		  var pos = obj.coordinates.split(",");
		  
		  var marker = new MarkerWithLabel({		
		    position: new google.maps.LatLng(pos[0], pos[1]),
		    map: map,
		    content: '<iframe id=\"iframe_aemet_id38037\" name=\"iframe_aemet_id38037\" src=\"http://www.aemet.es/es/eltiempo/prediccion/municipios/mostrarwidget/'+obj.weather_code+'?w=g4p11111110ohmffffffw600z313x4f86d9t95b6e9\" width=\"600\" height=\"313\" frameborder=\"0\" scrolling=\"no\"></iframe>',
		    labelContent: pictureLabel,
		    icon : '../images/null.gif',
		    flat: true,
		    labelStyle: {opacity: 0.90}
		  });
	
		  google.maps.event.addListener(marker, 'click', function() {			  
			   /* close the previous info-window */
			   closeInfos();			 
			   /* the marker's content gets attached to the info-window: */
			   var info = new google.maps.InfoWindow({content: this.content});			 
			   /* trigger the infobox's open function */
			   info.open(map,this);			 
			   /* keep the handle, in order to close it on next click event */
			   infos[0]=info;			 
			});
		}
	
	}
	
	function closeInfos(){
		 
		   if(infos.length > 0){
		 
		      /* detach the info-window from the marker */
		      infos[0].set("marker",null);
		 
		      /* and close it */
		      infos[0].close();
		 
		      /* blank the array */
		      infos.length = 0;
		   }
		}
	
	</script>
</head>
	<body onload="initialize()"> 
<!-- <body> -->
	<div style="color:#013984; font-size:10px; font-style:italic; font-weight:bold; text-align:right; margin-bottom:15px;">Pinche en los iconos para tener una previsión del tiempo más detallada</div>
	<div id="map_canvas"></div>
</body>

</html>
