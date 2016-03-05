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
			$apps = Config::get('system.translated_applications');
			if(!empty($apps)) {
				echo "\nGenerating language files\n";
				foreach ($apps as $application => $languages) {
					echo "[APPLICATION: " . $application . "]\n";
					foreach ($languages as $language) {
						echo "\t[LANGUAGE: " . $language . "]";
						if (self::getLanguageFile($application, $language)) {
							echo " OK\n";
						} else {
							throw new \Exception('Unable to generate language file!', 202);
						}
					}
				}
			} else {
				throw new \Exception('Empty applications returned from method Config::get', 201);
			}
		} catch (\Exception $e) {
			echo "\n\n[!ERROR: (".$e->getCode().")]"
				." detected \n\tOn file: ".$e->getFile().","
				."\n\tAt line: ".$e->getLine().", with message: "
				.$e->getMessage()."\n\n";
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
		
		//echo "languageResponse: ".print_r($languageResponse, true)."\n";

		try {
			ApiErrorResult::checkForApiErrorResult($languageResponse);
		} catch (\Exception $e) {
			throw new \Exception('Error during getting language file: (' . $application . '/' . $language . ')');
		}

		// If we got correct data we store it.
		$path = File::getLanguageCachePath($application);
		$destination = File::checkIfFileExists($path, $language, '.php');
		$fullfilename = $path.'/'.$language.'.php';
		// Write language translation to destiantion file
		$result = File::storeLanguageFile($fullfilename, $languageResponse['data']);
		return $result;
	}
	
}
