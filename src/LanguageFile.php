<?php

namespace Language;

class LanguageFile
{
	
	/**
	 * Starts the language file generation.
	 *
	 * @return void
	 */
	public static function generateLanguageFiles()
	{
		// The applications where we need to translate.
		try {
			$error = false;
			$apps = Config::get('system.translated_applications');
			if(!empty($apps)) {
				Log::log(Log::colorize("\nGenerating language PHP files:", 'NOTE'));
				foreach ($apps as $application => $languages) {
					Log::log("[APPLICATION: " . Log::colorize($application, 'WARNING') . "]");
					foreach ($languages as $language) {
						if (self::getLanguageFile($application, $language)) {
							Log::log("\t[LANGUAGE: " . Log::colorize($language, 'WARNING') . "] ".
								Log::colorize("OK", 'SUCCESS'));
						} else {
							$error = true;
							Log::log("\t[LANGUAGE: " . $language . "] NOK");
							throw new \Exception('Unable to generate language file!', 200);
						}
					}
				}
			} else {
				$error = true;
				throw new \Exception('Empty applications returned from method Config::get', 201);
			}
		} catch (\Exception $e) {
			Log::log("\n\n[".Log::colorize("ERROR", 'FAILURE').": (".$e->getCode().")]"
				." detected \n\tOn file: ".$e->getFile().","
				."\n\tAt line: ".$e->getLine().", with message: "
				.$e->getMessage()."\n\n");
		}
		if (!$error) {
			Log::log(Log::colorize("Application language PHPs generated.\n", 'NOTE'));
		} else {
			Log::log(Log::colorize("Error during language PHPs generation.\n", 'FAILURE'));
		}
	}

	/**
	 * Gets the language file for the given language and stores it.
	 *
	 * @param string $application   The name of the application.
	 * @param string $language      The identifier of the language.
	 *
	 * @throws CurlException   If there was an error during the download of the language file.
	 *
	 * @return bool   The success of the operation.
	 */
	protected static function getLanguageFile($application, $language)
	{
		$result = false;
		$languageResponse = ApiCall::call(
			'system_api',
			'language_api',
			array(
				'system' => 'LanguageFiles',
				'action' => 'getLanguageFile'
			),
			array('language' => $language)
		);
		try {
			CheckErrorResults::checkForApiErrorResult($languageResponse);
		} catch (\Exception $e) {
			throw new \Exception('Error during getting language file: (' 
				. $application . '/' . $language . ')', 202);
		}

		// If we got correct data we store it.
		$path = File::getLanguageCachePath($application);
		$destination = File::checkIfFileExists($path, $language, '.php');
		$fullfilename = $path.'/'.$language.'.php';
		// Write language translation to destiantion file
		if(!empty($languageResponse)) {
			$result = File::storeLanguageFile($fullfilename, $languageResponse['data']);
			return $result;
		} else {
			return $result;
		}
	}
	
}
