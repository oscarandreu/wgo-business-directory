<?php
/*�scar Andreu */

if (!function_exists('add_action'))
    require_once(__dir__.'/../../../../wp-config.php');

global $wpdb;

$id = $_GET['id'];
$location = "28.674173,-17.779999";
$zoom = 11;

if (!empty($id)) {
    $item = ItemsClass::getBussinesItem($id);
    $location = $item->coordinates;
    $zoom = 17;
    $items = array($item);
    
}else{
   //looking for location and zoom if city exists
    if(! empty($_GET['city']))
    {
    	$cities =  CityClass::searchCities($_GET['city']);
        $city = $cities[0];
        $location = $city->coordinates;
        $zoom = $city->zoom;
    }
    $items = ItemsClass::getLocationInfo($_GET['category'],$_GET['city'] );
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
          <style type="text/css">
            html { height: 100% }
            body { height: 100%; margin: 0; padding: 0 }
            #map_canvas {width:100%; height: 100%; margin-left:auto;margin-right:auto; }
        </style>
        <script type="text/javascript">

        	var flatMarker = <?php echo (empty($_GET['city']) ? 'true': 'false'); ?>;
            function initialize() {
                var myOptions = {
                    center: new google.maps.LatLng(<?php echo $location ?>),
                    zoom: <?php echo $zoom ?>,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    panControl: false,
                    zoomControl: true,
                    mapTypeControl: true,
                    scaleControl: true,
                    streetViewControl: true,
                    overviewMapControl: false
                };
                var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
                
                var infowindow = new google.maps.InfoWindow();
<?php foreach ($items as $item) {?>
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(<?php echo $item->coordinates ?>),
                    map: map,
                    flat: flatMarker,
                    <?php 
                     if($item->icon != null) 
                         echo 'icon: "'.content_url('plugins/wgo-business-directory/images/').$item->icon.'",'; 
                     ?>
                    title: "<?php echo $item->name ?>"                   
                });            
<?php } ?>  
            }
            
            function loadScript() {
                var script = document.createElement("script");
                script.type = "text/javascript";
                script.src = "http://maps.googleapis.com/maps/api/js?key=AIzaSyDVd08cyhhbGaMdE0X0QiayYca_-QRevyo&sensor=false&callback=initialize&language=es";
                document.body.appendChild(script);
            }     
            
            window.onload = loadScript;

        </script>
    </head>

    <body>
        <div id="map_canvas">
            <!--<img src="<?php echo content_url('plugins/wgo-business-directory/images/') ?>wait.gif">-->
        </div>
    </body>
    <!--
      <input type="submit" value="Submit" onclick="create()"/>
    -->

</html>
