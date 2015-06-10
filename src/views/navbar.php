<nav class="navbar navbar-inverse">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="#"><?php echo $GLOBALS["SHELL_FULL_NAME"]; ?></a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="<?php echo !isset($_REQUEST["action"]) ? "active" : ""; ?>"><a href="?action=home">Home</a></li>
				<li class="<?php echo isset($_REQUEST["action"]) && $_REQUEST["action"] == "fileman" ? "active" : ""; ?>"><a href="?action=fileman">File Manager</a></li>
				<li><a href="?action=sqlexplore">SQL Explorer</a></li>
				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Reverse Shell<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#">Python (TTY)</a></li>
						<li><a href="#">PHP (TTY)</a></li>
						<li><a href="#">PHP fsockopen</a></li>
						<li><a href="#">bash</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>