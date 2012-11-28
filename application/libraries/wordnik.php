<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class wordnik {

	public static $POST = "POST";
	public static $GET = "GET";
	public static $PUT = "PUT";
	public static $DELETE = "DELETE";

	function __construct()
	{
		// Get variables from config file
		get_instance()->config->load('wordnik', TRUE);
		$config = get_instance()->config->item('wordnik');

		// Parse config variables
		foreach ($config as $key => $val)
		{
			$this->$key = $val;
		}
	}

	public function callAPI($resourcePath, $method, $queryParams, $headerParams)
	{
		$headers = array();
		$headers[] = "Content-type: application/json";

		if($headerParams != null) {
			foreach ($headerParams as $key => $val) {
				$headers[] = "$key: $val";
			}
		}

		// TODO: alter to have static $queryParams
		$url = $config['apiServer'] . $resourcePath;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		// return the result on success, rather than just TRUE
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		if(!empty($queryParams))
		{
			$url = ($url . '?' . http_build_query($queryParams));
		}

		curl_setopt($curl, CURLOPT_URL, $url);

		// Make the request
		$response = curl_exec($curl);
		$response_info = curl_getinfo($curl);

		// Handle the response
		if ($response_info['http_code'] == 0) {
			throw new Exception("TIMEOUT: api call to " . $url .
				" took more than 5s to return" );
		} else if ($response_info['http_code'] == 200) {
			$data = json_decode($response);
		} else if ($response_info['http_code'] == 401) {
			throw new Exception("Unauthorized API request to " . $url .
					": ".json_decode($response)->message );
		} else if ($response_info['http_code'] == 404) {
			$data = null;
		} else {
			throw new Exception("Can't connect to the api: " . $url .
				" response code: " .
				$response_info['http_code']);
		}

		return $data;

	}

	public static function toPathValue($object)
	{
		if (is_array($object))
		{
			return implode(',', $object);
		}
		else {
			return $object;
		}
	}

	function getDefinitions($wordInput)
	{
		// Parse inputs
		$resourcePath = "/word.{format}/{word}/definitions";
		$resourcePath = str_replace("{format}", "json", $resourcePath);
		$method = "GET";
	 	$queryParams = array();
	 	$headerParams = array();

		// Map wordInput methods to API query parameters
		if($wordInput != null && $wordInput->limit != null) {
			$queryParams["limit"] = $this->toPathValue($wordInput->limit);
		}
		if($wordInput != null && $wordInput->partOfSpeech != null) {
			$queryParams["partOfSpeech"] = $this->toPathValue($wordInput->partOfSpeech);
		}
		if($wordInput != null && $wordInput->includeRelated != null) {
			$queryParams["includeRelated"] = $this->toPathValue($wordInput->includeRelated);
		}
		if($wordInput != null && $wordInput->sourceDictionaries != null) {
			$queryParams["sourceDictionaries"] = $this->toPathValue($wordInput->sourceDictionaries);
		}
		if($wordInput != null && $wordInput->useCanonical != null) {
			$queryParams["useCanonical"] = $this->toPathValue($wordInput->useCanonical);
		}
		if($wordInput != null && $wordInput->includeTags != null) {
			$queryParams["includeTags"] = $this->toPathValue($wordInput->includeTags);
		}
		if($wordInput != null && $wordInput->word != null) {
			$resourcePath = str_replace("{word}", $wordInput->word, $resourcePath);	
		}

		// Make the API Call
		$response = $this->callAPI($resourcePath, $method, $queryParams, $headerParams);
	 	if(!$response){
	 		return null;
	 	}

		// Parse response
		$responseObjects = array();

		return $responseObjects;
	}

}

/* End of file wordnik.php */
