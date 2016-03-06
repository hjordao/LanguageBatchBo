<?php

namespace Language;

class LanguageApplet
{
	/**
	 * Gets the language files for the applet and puts them into the cache.
	 *
	 * @throws Exception   If there was an error.
	 *
	 * @return void
	 */
	public static function generateAppletLanguageXmlFiles()
	{
		// List of the applets [directory => applet_id].
		$applets = array(
			'memberapplet' => 'JSM2_MemberApplet'
		);

		Log::log(Log::colorize("\nGenerating applet language XMLs:", 'NOTE'));
		$error = false;
		try {
			foreach ($applets as $appletDirectory => $appletLanguageId) {
				Log::log("[APPLET: ".Log::colorize($appletLanguageId, 'WARNING')."]"
					." - [DIR: ".Log::colorize($appletDirectory, 'WARNING')."]\n");
				$languages = self::getAppletLanguages($appletLanguageId);
				if (empty($languages)) {
					$error = true;
					throw new \Exception('There is no available languages for the ' . $appletLanguageId . ' applet.', 100);
				}
				$path = File::getLanguageCachePath('flash');
				foreach ($languages as $language) {
					
						$xmlContent  = self::getAppletLanguageFile($appletLanguageId, $language);
						if(empty($xmlContent)) {
							$error = true;
							throw new \Exception('There is no XMLContent for applet: ('.$appletLanguageId.')'
								.' language: ('.$language.')!', 101);
						} else {
							$xmlFile = File::checkIfFileExists($path, '/lang_'.$language, '.xml');
							if (File::storeLanguageFile($xmlFile, $xmlContent)) {
								Log::log("\t[LANGUAGE: " . Log::colorize(implode(', ', $languages), 'WARNING') 
									. "] ".Log::colorize("OK", 'SUCCESS'));
							} else {
								$error = true;
								Log::log("\t[LANGUAGE: " . Log::colorize(implode(', ', $languages), 'WARNING') 
									. "] ".Log::colorize("NOK", 'FAILURE'));
								throw new \Exception('Unable to save applet: ('.$appletLanguageId.')'
									.'language: ('.$language.') xml ('.$xmlFile.')!', 102);
							}
						}
					
				}
				
				if (!$error) {
					Log::log("\t[XML CACHED: ".Log::colorize($appletLanguageId, 'WARNING')."] "
						.Log::colorize("OK", 'SUCCESS'));
				} else {
					Log::log("\t[XML CACHED: ".Log::colorize($appletLanguageId, 'WARNING')."] "
						.Log::colorize("NOK", 'FAILURE'));
				}
			}
		} catch (\Exception $e) {
			$error = true;
			Log::log("\n\n[".Log::colorize("ERROR", 'FAILURE').": (".$e->getCode().")]"
				." detected \n\tOn file: ".$e->getFile().","
				."\n\tAt line: ".$e->getLine().", with message: "
				.$e->getMessage()."\n\n");
		}
		if (!$error) {
			Log::log(Log::colorize("Applet language XMLs generated.\n", 'NOTE'));
		} else {
			Log::log(Log::colorize("Error during language XMLs generation.\n", 'FAILURE'));
		}
	}

	/**
	 * Gets the available languages for the given applet.
	 *
	 * @param string $applet   The applet identifier.
	 *
	 * @return array   The list of the available applet languages.
	 */
	protected static function getAppletLanguages($applet)
	{		
		$result = ApiCall::call(
			'system_api',
			'language_api',
			array(
				'system' => 'LanguageFiles',
				'action' => 'getAppletLanguages'
			),
			array('applet' => $applet)
		);
		try {	
			CheckErrorResults::checkForApiErrorResult($result);
		} catch (\Exception $e) {
			throw new \Exception('Getting languages for applet (' . $applet . ') was unsuccessful ', 103);
		}
		
		if (empty($result)) {
			return;
		} else {
			return $result['data'];
		}
	}


	/**
	 * Gets a language xml for an applet.
	 *
	 * @param string $applet      The identifier of the applet.
	 * @param string $language    The language identifier.
	 *
	 * @return string|false   The content of the language file or false if weren't able to get it.
	 */
	protected static function getAppletLanguageFile($applet, $language)
	{
		$result = ApiCall::call(
			'system_api',
			'language_api',
			array(
				'system' => 'LanguageFiles',
				'action' => 'getAppletLanguageFile'
			),
			array(
				'applet' => $applet,
				'language' => $language
			)
		);

		try {
			CheckErrorResults::checkForApiErrorResult($result);
		} catch (\Exception $e) {
			throw new \Exception('Getting language xml for applet: (' . $applet . ')'
				. ' on language: (' . $language . ') was unsuccessful: ', 104);
		}

		return $result['data'];
	}
}
