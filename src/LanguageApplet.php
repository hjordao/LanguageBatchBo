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

		echo "\nGenerating applet language XMLs:\n";
		$error = 0;
		foreach ($applets as $appletDirectory => $appletLanguageId) {
			echo "[APPLET: $appletLanguageId] - [DIR: $appletDirectory]\n";
			$languages = self::getAppletLanguages($appletLanguageId);
			if (empty($languages)) {
				$error = 1;
				throw new \Exception('There is no available languages for the ' . $appletLanguageId . ' applet.', 100);
			} else {
				echo "\t[LANGUAGE: " . implode(', ', $languages) . "]";
			}
			$path = File::getLanguageCachePath('flash');
			foreach ($languages as $language) {
				try {
					$xmlContent  = self::getAppletLanguageFile($appletLanguageId, $language);
					if(empty($xmlContent)) {
						$error = 1;
						throw new \Exception('There is no XMLContent for applet: ('.$appletLanguageId.')'
							.' language: ('.$language.')!', 101);
					} else {
						$xmlFile = File::checkIfFileExists($path, '/lang_'.$language, '.xml');
						if (File::storeLanguageFile($xmlFile, $xmlContent)) {
							echo " OK\n";
						} else {
							$error = 1;
							throw new \Exception('Unable to save applet: ('.$appletLanguageId.')'
								.'language: ('.$language.') xml ('.$xmlFile.')!', 102);
						}
					}
				} catch (\Exception $e) {
					$error = 1;
					echo "\n\n[!ERROR: (".$e->getCode().")]"
						." detected \n\tOn file: ".$e->getFile().","
						."\n\tAt line: ".$e->getLine().", with message: "
						.$e->getMessage()."\n\n";
				}
			}
			echo "\t[XML CACHED: $appletLanguageId] ";
			if (!$error) {
				echo "OK\n";
			} else {
				echo "NOK\n";
			}
		}
		
		if (!$error) {
			echo "\nApplet language XMLs generated.\n\n";
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
			ApiErrorResult::checkForApiErrorResult($result);
		} catch (\Exception $e) {
			throw new \Exception('Getting languages for applet (' . $applet . ') was unsuccessful ' . $e->getMessage());
		}

		return $result['data'];
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
			ApiErrorResult::checkForApiErrorResult($result);
		} catch (\Exception $e) {
			throw new \Exception('Getting language xml for applet: (' . $applet . ')'
				. ' on language: (' . $language . ') was unsuccessful: ' . $e->getMessage());
		}

		return $result['data'];
	}
}
