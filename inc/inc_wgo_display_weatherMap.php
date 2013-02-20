<?php

function wgo_display_weather_map() {
echo '
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<title>Mapa del tiempo</title>
	<style type="text/css">
		#WgoWm_iframe {
			margin:0px 0px; padding:0px;
			text-align:center;
		}
	</style>
	
	<script type="text/javascript">

	var sdsv$ = jQuery.noConflict();
	
	function initialize() {
		var frame = document.getElementById(\'WgoWm_ajax_request\');
	    sdsv$(frame).attr(\'src\', \''.content_url('plugins/wgo-business-directory/inc/inc_wgo_display_weather_map.php').'\');
	    showMap();
	}

	function showMap()
	{
		var frame = document.getElementById(\'WgoWm_ajax_request\');
		sdsv$(frame).attr(\'width\', \'750\');
		sdsv$(frame).attr(\'height\', \'780\');
		var div = document.getElementById(\'wait_div\');
		div.innerHTML = \'\';
	}
	</script>

</head>
<body  onload="initialize()">	
	<div id="WgoWm_iframe">
		<iframe 
			id="WgoWm_ajax_request" 
			width="0" 
		    height="0" 
		    scrolling="no"
		    >
		</iframe>
	</div>
	
	<div id="wait_div">Recopilando información... <img src="'.content_url('plugins/wgo-business-directory/images/wait.gif').'"></div>
	Información elaborada por la Agencia Estatal de Meteorología. Ministerio de Agricultura, Alimentación y Medio Ambiente
</body>

</html>
';
}



