<!DOCTYPE html5>

<html lang="en">
	<head>
		<title><?php echo $GLOBALS["SHELL_FULL_NAME"]; ?></title>
		<link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
		<?php include_or_detect("views", "shell.css"); ?>
	</head>
	<body>
		<?php include_or_detect("views", "navbar"); ?>

		<div class="container" role="main">
			<?php include_or_detect("views", "infopanel"); ?>
			
			<?php include_or_detect("views", "responsebox"); ?>
		</div>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>
	</body>
</html>