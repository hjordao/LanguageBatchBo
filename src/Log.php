<?php

namespace Language;

class Log
{
	public static function writeLog($message)
	{
		if(is_array($message)) {
			
		} else {
			echo $message."\n";
		}
	}
}
