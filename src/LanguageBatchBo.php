<?php

namespace Language;

/**
 * Business logic related to generating language files.
 */
class LanguageBatchBo
{

	public static function generateLanguageFiles()
	{
		return LanguageFile::generateLanguageFiles();
	}
	
	public static function generateAppletLanguageXmlFiles()
	{
		return LanguageApplet::generateAppletLanguageXmlFiles();
	}

}
