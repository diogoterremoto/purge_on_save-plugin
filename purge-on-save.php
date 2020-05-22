<?php

/*
	Plugin Name: Purge on Save
	Plugin URI: https://github.com/diogoterremoto/purge_on_save-wordpress-plugin
	Description: Send a HTTP request with PURGE method on every post save (creation or update) to clean a service cache.
	Version: 1.0.0
	Author:  Diogo Terremoto <diogompt@me.com>
	Author URI:  https://diogoterremoto.xyz
*/

if (!function_exists("purge_cache")) {
	function purge_cache() {

		$origins = array();

		// Get all origins
		foreach($_ENV as $variableKey => $variableValue) {

			// Check if variable is a service origin
			$isOrigin = preg_match("/_ORIGIN$/", $variableKey);

			if ($isOrigin) {
				// Get service name by getting the first word before the underscore
				$service = explode("_", $variableKey, 2)[0];

				// Add found origin to array
				$origins[$service] = $variableValue;
			}
		}

		// Evaluate cleaning of each origin
		foreach($origins as $service => $origin) {

			$shouldClean = getenv("PURGE_" . $service . "_CACHE_ON_SAVE") == "true";

			if ($origin && $shouldClean) {
				// Define request
				$url = $origin;
				$args = array("method" => "PURGE");

				// Make request
				$res = wp_remote_request($url, $args);

				// Check for sucess
				if(!is_wp_error($res) && ($res["response"]["code"] == 200 || $res["response"]["code"] == 201)) {
					error_log("Cache from " . $service . " was successfuly purged!");
				} else {
					$jsonResponse = json_encode($res);
					error_log("Couldn't purge " . $service . " cache. Response from server was: " . $jsonResponse);
				}
			}
		}
	};

	// Attach purging of cache on every post save
	add_action("save_post", "purge_cache");
}

