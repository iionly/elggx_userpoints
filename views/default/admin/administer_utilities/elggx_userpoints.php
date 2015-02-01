<?php

$tab = get_input('tab') ? get_input('tab') : 'list';

$params = array(
        'tabs' => array(
                array('title' => elgg_echo('elggx_userpoints:list'), 'url' => "$url" . '?tab=list', 'selected' => ($tab == 'list')),
                array('title' => elgg_echo('elggx_userpoints:detail'), 'url' => "$url" . '?tab=detail', 'selected' => ($tab == 'detail')),
                array('title' => elgg_echo('elggx_userpoints:moderate'), 'url' => "$url" . '?tab=moderate', 'selected' => ($tab == 'moderate')),
                array('title' => elgg_echo('elggx_userpoints:add'), 'url' => "$url" . '?tab=add', 'selected' => ($tab == 'add')),
                array('title' => elgg_echo('elggx_userpoints:settings'), 'url' => "$url" . '?tab=settings', 'selected' => ($tab == 'settings')),
                array('title' => elgg_echo('elggx_userpoints:actions'), 'url' => "$url" . '?tab=actions', 'selected' => ($tab == 'actions')),
        )
);

echo elgg_view('navigation/tabs', $params);

        switch($tab) {
                case 'list':
                        echo elgg_view("elggx_userpoints/list");
                        break;
                case 'detail':
                        echo elgg_view("elggx_userpoints/detail");
                        break;
                case 'moderate':
                        echo elgg_view("elggx_userpoints/moderate");
                        break;
                case 'add':
                        echo elgg_view("elggx_userpoints/add");
                        break;
                case 'settings':
                        echo elgg_view("elggx_userpoints/settings");
                        break;
                case 'actions':
                        echo elgg_view("elggx_userpoints/actions");
                        break;
        }
