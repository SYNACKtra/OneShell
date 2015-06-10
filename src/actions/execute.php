<?php
function handle_execute_action_post($command)
{
	set_response(fuhosin_exec($command));
}
?>