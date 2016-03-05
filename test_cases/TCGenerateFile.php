<?php

class TCGenerateFile extends PHPUnit_Framework_TestCase
{
    public function testGenerateFile() 
    {
		$file   = new \Language\LanguageFile();
		$file_1 = $file->generateLanguageFiles();
		$file_2 = $file->generateLanguageFiles();
		$this->assertEquals($file_2, $file_1);
	}
}
