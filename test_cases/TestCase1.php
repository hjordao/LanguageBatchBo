<?php

class TestCase1 extends PHPUnit_Framework_TestCase
{
    public function testGetConfig() 
    {
		$config = new \Language\Config();
		$conf_t = $config->get('system.translated_applications');
		$this->assertEquals($conf_t, ['portal' => ['en', 'hu']]);
	}
}
