<?php

$tab = get_input('tab', 'list');

echo elgg_view('elggx_userpoints/tabs', [
	'tab' => $tab,
]);

if (elgg_view_exists("elggx_userpoints/{$tab}")) {
	echo elgg_view("elggx_userpoints/{$tab}");
}
