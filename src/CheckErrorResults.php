<?php

namespace Language;

class CheckErrorResults
{

	/**
	 * Checks the api call result.
	 *
	 * @param mixed  $result   The api call result to check.
	 *
	 * @throws Exception   If the api call was not successful.
	 *
	 * @return void
	 */
	public static function checkForApiErrorResult($result)
	{
		try {
			// Error during the api call.
			if ($result === false || !isset($result['status']) || empty($result)) {
				throw new \Exception('Error during the api call', 300);
			}
			// Wrong response.
			if ($result['status'] != 'OK') {
				//Is not defined, but stay here because i don't the original Api class 
				if (!empty($result['error_type'])) {
					throw new \Exception('Wrong response: Type('.$result['error_type'].')', 301);
				}
				//Is not defined, but stay here because i don't the original Api class
				if (!empty($result['error_code'])) {
					throw new \Exception('Wrong response: Code('.$result['error_code'].')', 302);
				}
				if (is_array($result['data'])) {
					throw new \Exception('Wrong response: Data1('.print_r($result['data'], true).')', 303);
				}
				if (is_string($result['data'])) {
					throw new \Exception('Wrong response: Data2('.$result['data'].')', 304);
				}
			}
			// Wrong content.
			if ($result['data'] === false) {
				throw new \Exception('Wrong content!', 305);
			}
		} catch (\Exception $e) {
			echo "\n\n[!ERROR: (".$e->getCode().")]"
				." detected \n\tOn file: ".$e->getFile().","
				."\n\tAt line: ".$e->getLine().", with message: "
				.$e->getMessage()."\n\n";
		}
	}
	
	/**
	 * Checks the config get result.
	 *
	 * @param mixed  $result   The config get result to check.
	 *
	 * @throws Exception   If the config get not successful.
	 *
	 * @return void
	 */
	public static function checkForConfigGetErrorResult($result)
	{
		try {
			// Error during the config get.
			if (is_array($result) && ($result === false || empty($result))) {
				throw new \Exception('Error during the get call', 400);
			}
			if (is_string($result) && $result == '') {
				throw new \Exception('Error during the get call', 401);
			}
			if (!isset($result)) {
				throw new \Exception('Error during the get call', 402);
			}
		} catch (\Exception $e) {
			Log::log("\n\n[".Log::colorize("ERROR", 'FAILURE').": (".$e->getCode().")]"
				." detected \n\tOn file: ".$e->getFile().","
				."\n\tAt line: ".$e->getLine().", with message: "
				.$e->getMessage()."\n\n");
		}
	}
	
	
}
