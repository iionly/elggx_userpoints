<?php
/**
 * Elggx Userpoints Plugin
 *
 * Index page Toppoints widget for Widget Manager plugin
 *
 */

$count = sanitise_int($vars["entity"]->toppoints_count, false);
if(empty($count)){
        $count = 10;
}

?>
<div>
        <?php echo elgg_echo("elggx_userpoints:settings:toppoints:num"); ?><br />
        <?php echo elgg_view("input/text", array("name" => "params[toppoints_count]", "value" => $count, "size" => "4", "maxlength" => "4")); ?>
</div>
