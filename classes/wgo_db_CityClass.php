<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CitiesClass
 *
 * @author Óscar Andreu
 */
class CityClass {

    public $id;
    public $name;
    public $zoom;
    public $coordinates;
    public $weather_logo;
    public $weather_code;

    
    public static function searchCities($id = '', $name = '') {
    	global $wpdb;
    
    	$where = "WHERE 1=1";
    	if ($id != '')
    		$where .= " AND id='$id'";
    
    	if ($name != '')
    		$where .= " AND name='$name'";
    
    	$query = "SELECT * FROM " . $wpdb->prefix . "wgo_cities $where";
    	//echo '<p>'.$query;
    
    	return $wpdb->get_results($query);
    }
       
    public static function getCityList($selected = '', $onChangeFunction = '') {
    	$cities = CityClass::searchCities();
    	
    	return GuiHelper::getSelectList($cities, 'municipio', 'wgo_listing_cities_id', $selected, $onChangeFunction);
    }
    
    public static function getWeatherLogos() {
    	foreach (self::searchCities() as $city)
    	{    		
    		$city->weather_logo =  Utils::get_images('http://www.aemet.es/es/eltiempo/prediccion/municipios/mostrarwidget/'.$city->weather_code.'?w=g1p01010000ohmffffffw186z173x4f86d9t95b6e9');
    		CityClass::update($city);
    	}
    }
	
    public static function citySelect($state_province_field, $selected = "") {
        foreach (CityClass::getCityList() as $cities) {
            if (($selected == $city->id) || ($selected == $city->name)) {
                $select_status = "selected ";
            } else {
                $select_status = '';
            }
            $lResult.="<option $select_status value='$city->id'>$city->name</option>";
        }

        return $lResult;
    }

    public static function update( $city) {
    	global $wpdb;
    
    	$wpdb->update(
    			$wpdb->prefix . 'wgo_cities', 
    			array(
    					'id' => $city->id,
    					'name' => $city->name,
    					'zoom' => $city->zoom,
    					'coordinates' => $city->coordinates,
    					'weather_logo' => $city->weather_logo,
    					'weather_code' => $city->weather_code
    			),
    			array( 'id' => $city->id )
    	);
    	//echo var_dump($wpdb->last_query);
    }
    
    public static function Cast(CityClass &$object=NULL){
    	return $object;
    }
}

?>
