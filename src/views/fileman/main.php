<!DOCTYPE html5>

<html lang="en">
	<head>
		<title><?php echo $GLOBALS["SHELL_FULL_NAME"]; ?></title>
		<link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
		<?php include_or_detect("views", "shell.css"); ?>
	</head>
	<body>
		<?php include_or_detect("views", "navbar"); ?>
		<?php
		$dir = isset($_GET["dir"]) ? $_GET["dir"] : "./";
		?>
		<div class="container" role="main">

			<div>
			<button type="button" class="btn btn-primary">Upload</button>
			<button type="button" class="btn btn-primary">Create Directory</button>
			<button type="button" class="btn btn-primary">Create File</button>
			</div><br/>

			<form method="get" action="">
				<input type="hidden" name="action" value="fileman">
				<div class="input-group">
					<input type="text" class="form-control" name="dir" placeholder="Directory" value="<?php echo realpath($dir); ?>">
					<span class="input-group-btn">
						<button class="btn btn-primary" type="button">Go to Directory</button>
					</span>
				</div><!-- /input-group -->
			</form>

			<table class="table table-hover table-borderec table-responsive">
				<thead>
					<tr class="active">
						<td>Perm</td>
						<td>Owner</td>
						<td>Size</td>
						<td><b>Name</b></td>
						<td><span class="pull-right"></span></td>
					</tr>
				</thead>
				<tbody>
					<?php
					$files = list_dir($dir);

					foreach ($files as $file)
					{
						$color = "success";
						$url = "";

						if($file["is_dir"])
						{
							$url = "?action=fileman&dir=" . urlencode($file["path"]);
							$color = "info";
						}
						else
						{
							$url = "?action=fileman&file=" . urlencode($file["path"]);
							$color = "success";
						}

						if($file["is_readable"] === false)
						{
							$color = "danger";
						}
					?>

					<tr class="clickable-row <?php echo $color; ?>" data-href="<?php echo $url; ?>">
						<td><?php echo $file["perms"]; ?></td>
						<td><?php echo $file["owner"]; ?></td>
						<td><?php echo $file["size"]; ?></td>
						<td><b><?php echo $file["name"]; ?></b></td>
						<td><span class="pull-right">
							<div class="btn-group" role="group" aria-label="...">
								<button type="button" class="btn btn-primary">Edit</button>
								<button type="button" class="btn btn-primary">View</button>
								<button type="button" class="btn btn-success">Rename</button>
								<button type="button" class="btn btn-danger">Delete</button>
							</div>
						</span></td>
					</tr>

					<?php
					}
					?>
				</tbody>
			</table>
		</div>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>
		<script>
			jQuery(document).ready(function($) {
				$(".clickable-row").click(function() {
					if($(this).data("href") != "")
					{
						window.document.location = $(this).data("href");
					}
				});
			});
		</script>
	</body>
</html>