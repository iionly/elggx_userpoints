<?php

if ($vars['size'] == 'large') {
    if (elgg_get_plugin_setting('profile_display', 'elggx_userpoints')) {
?>

        <div class="userpoints_profile">
            <div><span><?php echo elgg_echo('elggx_userpoints:upperplural') . ': ' . $vars['entity']->userpoints_points;?></span></div>
        </div>

    <?php } ?>
<?php } ?>
