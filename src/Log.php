<?php

namespace Language;

class Log
{
	public static function log($message)
	{
		if(is_array($message)) {
			echo print_r()."\n";
		} else {
			echo $message."\n";
		}
	}
	
	public static function colorize($text, $status) {
		$out = "";
		switch($status) {
			case "FAILURE":
				$out = "[31m"; //Red
				break;
			case "SUCCESS":
				$out = "[32m"; //Green
				break;
			case "WARNING":
				$out = "[33m"; //Yellow
				break;
			case "NOTE":
				$out = "[34m"; //Blue
				break;
			default:
				throw new Exception("Invalid status: " . $status, 600);
		}
		return chr(27) . "$out" . "$text" . chr(27) . "[0m";
	}
}
