<?php
function display_wgo_listings($atts) {
    global $wpdb;
    
    include_once(WGO_BD_BASE_DIR.'inc/functions.php');
    
    $cat = $_GET['cat'];
    $city = $_GET['city'];
    $show_map = true;//get_option('wgo_use_map');
    
    $title .= isset($cat) ? CategoryClass::getCategoryName($cat) : 'Empresas';
    $return .= '
    	<base href="'. WGO_BASE_URL.'"/>
    	<h1 id="directoryTitle" class="entry_title">Directorio de ' . $title . '</h1>';
    
    if($show_map)
    {
    	 $return .= '
	    	<div id="mainMapPanel" >
	    		<iframe 
	    			width="100%" 
	    			height="500" 
	    			class="mainMapFrame"  
	    			id="view_map_main" 
	    			src="'.content_url('plugins/wgo-business-directory/inc/').'inc_display_map.php?category='.$cat.'&city='.$city.'">
	    		</iframe>
	    	</div>';
    }
  
    
    $registroInicial = isset($_GET['i']) ? $_GET['i'] : 0;

    $first = isset($_GET['first']) ? false : true;
    if ($first) {
    	$return .= wgo_get_find_function();
		if($show_map)
    		   	$return .= wgo_map_function();
        $return .= wgo_show_function();
        $return .= wgo_hide_function();
    }
    
	$callBackFunction = 'buscar(true,\'frontEnd\')';//$show_map ? 'buscar(true,\'frontEnd\')' : 'buscar(true,\'frontEnd\')';
    $return .= CategoryClass::getCategoryList($cat, $callBackFunction);
    $return .= CityClass::getCityList($city, $callBackFunction);

    $return .= '<div id="panel_empresas">';
    $return .= doQuery($atts[package], $cat, $city, $registroInicial);
    $return .= '</div>';

    return $return;
}

?>