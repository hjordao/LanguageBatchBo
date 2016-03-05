<?php

class TCGenerateLanguageFiles extends PHPUnit_Framework_TestCase
{
    public function generateLanguageFiles() 
    {
		$lang   = new \Language\LanguageBatchBo();
		$lang_t = $lang->generateLanguageFiles();
		$this->assertEquals($lang_t, "X");
	}
}
