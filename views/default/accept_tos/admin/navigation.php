<?php

$tabs = array(
    array(
        'name' => 'settings',
        'text' => elgg_echo('settings'),
        'href' => 'admin/plugin_settings/accept_tos',
        'selected' => (current_page_url() == elgg_normalize_url('admin/plugin_settings/accept_tos'))
    ),
    array(
        'name' => 'accept_tos',
        'text' => elgg_echo('accept_tos:manage:tos'),
        'href' => 'admin/accept_tos',
        'selected' => (current_page_url() == elgg_normalize_url('admin/accept_tos'))
    ),
    array(
        'name' => 'accept_privacy',
        'text' => elgg_echo('accept_tos:manage:privacy'),
        'href' => 'admin/accept_privacy',
        'selected' => (current_page_url() == elgg_normalize_url('admin/accept_privacy'))
    ),
);

echo elgg_view('navigation/tabs', array('tabs' => $tabs));

echo '<br><br>';