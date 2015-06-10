<?php
ini_set('error_reporting', E_ALL);

// bootstrap functions - kick off the shell's execution

function is_dev_mode()
{
	return file_exists("actions/");
}

function include_or_detect($dir, $name, $vars=array())
{
	if(is_dev_mode())
	{
		$full = $dir . ($dir == "" ? "" : "/") . $name . ".php";

		if(file_exists($full))
		{
			extract($vars);
			include($full);
			return true;
		}
	}

	return false;
}

include_or_detect("", "config");
include_or_detect("shell", "main");

?>