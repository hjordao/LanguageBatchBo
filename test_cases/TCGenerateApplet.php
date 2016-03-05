<?php

class TCGenerateApplet extends PHPUnit_Framework_TestCase
{
    public function testGenerateApplet() 
    {
		$applet   = new \Language\LanguageApplet();
		$applet_1 = $applet->generateAppletLanguageXmlFiles();
		$applet_2 = $applet->generateAppletLanguageXmlFiles();
		$this->assertEquals($applet_2, $applet_1);
	}
}
