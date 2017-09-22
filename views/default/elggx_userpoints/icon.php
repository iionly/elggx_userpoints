<?php

if (elgg_get_context() !== 'profile' || elgg_extract('size', $vars) !== 'large') {
	return;
}

if (!elgg_get_plugin_setting('profile_display', 'elggx_userpoints')) {
	return;
}

?>
<div class="userpoints_profile mtm">
	<div><span><?php echo elgg_echo('elggx_userpoints:upperplural') . ': ' . $vars['entity']->userpoints_points;?></span></div>
</div>
