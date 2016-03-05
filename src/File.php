<?php

namespace Language;

class File
{
	
	/**
	 * Gets the directory of the cached language files.
	 *
	 * @param string $application   The application.
	 *
	 * @return string   The directory of the cached language files.
	 */
	public static function getLanguageCachePath($application)
	{
		return Config::get('system.paths.root').'/cache/'.$application;
	}
	
	/**
	 * Verify if file exists.
	 * If there is no folder yet, we'll create it.
	 * @param string $application   The name of the application.
	 * @param string $language      The identifier of the language.
	 *
	 * @return string $destination	The destination of the file.
	 */
	public static function checkIfFileExists($application, $language, $extension) 
	{
		$destination = self::getLanguageCachePath($application).'/'.$language.$extension;
		if (!is_dir(dirname($destination))) {
			try {
				mkdir(dirname($destination), 0755, true);//self::checkForApiErrorResult($languageResponse);
			}
			catch (\Exception $e) {
				throw new \Exception('Error creating file path: ('.
					$destination.')\n For Application: '.$application.
					' and Language: '.$language.'\nErrorCode:('.$e->getMessage().')!\n');
			}
			
		}
		return $destination;
	}
	
	/**
	 * Stores language data file.
	 *
	 * @param string $destination   The full path of the destination.
	 * @param string $languageData  The language translation data.
	 *
	 * @return bool   The success of the operation.
	 */
	public static function storeLanguageFile($destination, $languageData)
	{
		try {
			$result = file_put_contents($destination, $languageData);
		} catch (\Exception $e) {
			throw new Exception('Unable to write language data:'.$languageData.
				' to file destination '.$destination.'\nErrorCode:('.$e->getMessage().')!\n');
		}
		return (bool)$result;
	}
	
}
