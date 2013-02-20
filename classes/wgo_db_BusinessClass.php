<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

/**
 * Description of BusinessClass
 *
 * @author Diario
 */
class ItemsClass {

	//Number of items per page
	public static  $_elementos_pagina = 20;
	public $id;
	public $name;
	public $logo_url;
	public $description;
	public $phone;
	public $movil;
	public $fax;
	public $url;
	public $email;
	public $address;
	public $city_id;
	public $state;
	public $zip;
	public $cat_id;
	public $pkg_id;
	public $time_listed;
	public $durmonths;
	public $time_expired;
	public $coordinates;
	public $path;
	public $alt_text;

	private  $_images;

	public static function searchInfo($package, $category, $city_id, $registroInicial, &$count, $active = "1") {
		global $wpdb;

		//Avoid sql injection
		$category = mysql_real_escape_string($category);
		$package = mysql_real_escape_string($package);
		$registroInicial = mysql_real_escape_string($registroInicial);
		$active = mysql_real_escape_string($active);
		$city_id = mysql_real_escape_string($city_id);
		$count = mysql_real_escape_string($count);


		if (! empty($category)) {
			$category = "a.cat_id='" . $category . "' AND ";
		}
		if (! empty($package)) {
			$package = "a.pkg_id='" . $package . "' AND ";
		}
		if (! empty($city_id)) {
			$city_id = "b.id='" . $city_id . "' AND ";
		}

		$query_count =
		"SELECT count(a.id) as count
		FROM " . $wpdb->prefix . "wgo_listings a LEFT JOIN " . $wpdb->prefix . "wgo_cities b ON a.city_id = b.id
		WHERE $package $category $city_id active=$active";//time_expired > NOW() AND 

		//echo $query_count;
		$count_res = $wpdb->get_results($query_count);
		$count = $count_res[0]->count;

		$query =
			"SELECT a.id, a.name, a.logo_url, a.description, a.phone, a.fax,  a.movil, a.url, a.email, a.address, b.name as city, a.state, a.zip, a.pkg_id, a.coordinates, a.twitter_url, a.facebook_url, a.youtube_url, a.rss_url
			FROM " . $wpdb->prefix . "wgo_listings a LEFT JOIN " . $wpdb->prefix . "wgo_cities b ON a.city_id = b.id
			WHERE $package $category $city_id active=$active
			ORDER BY a.pkg_id DESC, a.name
			LIMIT $registroInicial, " . ItemsClass::$_elementos_pagina;// time_expired > NOW() AND

		//echo $query;
		return $wpdb->get_results($query);
	}

	public static function getLocationInfo($category, $city_id) {
		global $wpdb;

		//Avoid sql injection
		$category = mysql_real_escape_string($category);
		$city_id = mysql_real_escape_string($city_id);


		if (! empty($category)) {
			$category = "a.cat_id='" . $category . "' AND ";
		}
		if (! empty($city_id)) {
			$city_id = "b.id='" . $city_id . "' AND ";
		}

		$query =
		"SELECT a.name, a.logo_url, a.coordinates, c.icon, a.description
		FROM " . $wpdb->prefix . "wgo_listings a
		LEFT JOIN " . $wpdb->prefix . "wgo_cities b ON a.city_id = b.id
		LEFT JOIN " . $wpdb->prefix . "wgo_categories c ON a.cat_id = c.id
		WHERE $category $city_id active=1";//time_expired > NOW() AND 

		//echo $query;
		return $wpdb->get_results($query);
	}

	public static function getBussinesItem($id) {
		global $wpdb;

		$id = mysql_real_escape_string($id);

		return $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "wgo_listings WHERE id = ".$id);
	}

	//Lazy load de las im�genes
	public function getImages(){
		if($this->images == null)
		{
			global $wpdb;

			$id = mysql_real_escape_string($this->id);
			$this->images = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "wgo_images WHERE id_listing = ".$id);
		}

		return $this->images;
	}

	public function getAllowedImagesCount(){
	  //TODO: Check the type of account and return the number of image uploads allowed
	  return 6;
  }
	
	public function saveImages() {
		global $wpdb;
		
		$wpdb->insert(
		  $wpdb->prefix . 'wgo_images', array(
					'id_listing' => $this->id,
					'path' => $this->path,
					'alt_text' => $this->alt_text
			)
		);
	}
	
	//Check data before save it
	public function validateData()
	{		
		if($this->name==""){
			$lResult.="<li>Debe introducir el nombre de la empresa</li>";
// 			$lResult.="<li>".__("Bussines name can't be empty",'wgo')."</li>";
		}
		if($this->description==""){
			$lResult.="<li>La descripción no puede estar vacio</li>";
// 			$lResult.="<li>".__("Description can't be empty",'wgo')."</li>";
		}
		if($this->phone==""){
			$lResult.="<li>El teléfono no puede estar vacio</li>";
// 			$lResult.="<li>".__("Telephone number can't be empty",'wgo')."</li>";
		}
		if($this->email==""){
			$lResult.="<li>El e-mail no puede estar vacio</li>";
// 			$lResult.="<li>".__("e-mail can't be empty",'wgo')."</li>";
		}
		if($this->address==""){
			$lResult.="<li>La dirección no puede estar vacio</li>";
// 			$lResult.="<li>".__("Address can't be empty",'wgo')."</li>";
		}
		if($this->city_id==""){
			$lResult.="<li>El municipio no puede estar vacio</li>";
// 			$lResult.="<li>".__("Bussines name can't be empty",'wgo')."</li>";
		}
		if($this->state==""){
			$lResult.="<li>La provincia no puede estar vacio</li>";
// 			$lResult.="<li>".__("Bussines name can't be empty",'wgo')."</li>";
		}
		if($this->zip==""){
			$lResult.="<li>El código postal no puede estar vacio</li>";
// 			$lResult.="<li>".__("Zip can't be empty",'wgo')."</li>";
		}
		if($this->cat_id==""){
			$lResult.="<li>Debe seleccionar una categoría</li>";
// 			$lResult.="<li>".__("Cathegory can't be empty",'wgo')."</li>";
		}
		if($this->pkg_id==""){
			$lResult.="<li>Debe seleccionar una modalidad</li>";
// 			$lResult.="<li>".__("Paid cathegory can't be empty",'wgo')."</li>";
		}
		
		return $lResult;
	}
	
	
	public function save() {
		global $wpdb;

		$wpdb->insert($wpdb->prefix . 'wgo_listings',$this->getArrayData());
		//echo var_dump($wpdb->last_query);
	}
	
	public function update() {
		global $wpdb;
	
		$where = array( 'id' => $this->id);
		$wpdb->update($wpdb->prefix . 'wgo_listings', $this->getArrayData(), $where);
		//echo var_dump($wpdb->last_query);
	}
	
	private function getArrayData(){
		return array(
				'id' => mysql_real_escape_string($this->id),
				'name' => mysql_real_escape_string($this->name),
				'logo_url' => mysql_real_escape_string($this->logo_url),
				'description' => mysql_real_escape_string($this->description),
				'phone' => mysql_real_escape_string($this->phone),
				'fax' => mysql_real_escape_string($this->fax),
				'movil' => mysql_real_escape_string($this->movil),
				'url' => mysql_real_escape_string($this->url),
				'email' => mysql_real_escape_string($this->email),
				'address' => mysql_real_escape_string($this->address),
				'city_id' => mysql_real_escape_string($this->city_id),
				'state' => mysql_real_escape_string($this->state),
				'zip' => mysql_real_escape_string($this->zip),
				'cat_id' =>  mysql_real_escape_string($this->cat_id),
				'pkg_id' => mysql_real_escape_string($this->pkg_id),
				'time_listed' => mysql_real_escape_string($this->time_listed),
				'time_expired' => mysql_real_escape_string($this->time_expired),
				'active' => 1
		);
	}

	public static function delete($id) {
		global $wpdb;			
		$wpdb->query("DELETE FROM " . $wpdb->prefix . "wgo_listings WHERE id= ".mysql_real_escape_string($id));
	}

	public static function activateItem($id) {
		$wpdb->update($wpdb->prefix . 'wgo_listings', array('active' => 1), array('id' => mysql_real_escape_string($id)));
	}

}

?>
