<?php
/* 
 * Oscar Andreu Martinez
 */

class Utils
{
	public static function curl_download($Url){
	
		// is cURL installed yet?
		if (!function_exists ('curl_init')){
			return ('Sorry cURL is not installed!');
		}

		// OK cool - then let's create a new cURL resource handle
		$ch = curl_init();
	
		// Now set some options (most are optional)
	
		// Set URL to download
		curl_setopt($ch, CURLOPT_URL, $Url);
	
		// Set a referer
		curl_setopt($ch, CURLOPT_REFERER, "http://diariodeavisos.com");
	
		// User agent
		curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	
		// Include header in result? (0 = yes, 1 = no)
		curl_setopt($ch, CURLOPT_HEADER, 0);
	
		// Should cURL return or print out the data? (true = return, false = print)
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
		// Timeout in seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	
		// Download the given URL, and return output
		$output = curl_exec($ch);

		// Close the cURL resource, and free system resources
		curl_close($ch);
	
		return $output;
	}
	
	public static function get_images($url){
		$data = self::curl_download($url);		

		$data = preg_replace('#<a href=.*?</a>#', '', $data);
		$data = preg_replace("#(.*)<body.*?>(.*?)</body>(.*)#is", '$2', $data);
		$data = str_replace('src="', 'src="http://www.aemet.es', $data);
		$data = str_replace('window.open(\'', 'window.open(\'http://www.aemet.es', $data);		
		
		$data = preg_replace('#(<table id.*?style=)"(.*?)"#', '$1"margin: 0pt auto;background-color: #FFFFFF;"', $data);
		$data = preg_replace('#summary=".*?"#is', '', $data);
		$data = preg_replace('#<thead>.*?</thead>#is', '', $data);
		$data = preg_replace('#<tfoot>.*?</tfoot>#is', '', $data);
		
		$data = str_replace('"', '\"', $data);
		$sustituye = array("\r\n", "\n\r", "\n", "\r");
		
		return utf8_encode (str_replace($sustituye, "", $data));
	}
	
	public static function get_content($url){
		$data = self::curl_download($url);
	
		$data = str_replace('src="', 'src="http://www.aemet.es', $data);
		$data = str_replace('window.open(\'', 'window.open(\'http://www.aemet.es', $data);
		$data = str_replace('"', '\"', $data);
		$sustituye = array("\r\n", "\n\r", "\n", "\r");
		return str_replace($sustituye, "", $data);
	}
}
?>