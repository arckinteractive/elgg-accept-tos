<?php

echo elgg_view('accept_tos/admin/navigation');


echo elgg_view('output/url', array(
    'text' => elgg_echo('accept_tos:privacy:add'),
    'href' => 'admin/accept_privacy/add',
    'class' => 'elgg-button elgg-button-action'
));

echo '<br><br>';


$options = array(
    'type' => 'object',
    'subtype' => 'accept_privacy',
    'full_view' => false,
    'count' => true,
);

$count = elgg_get_entities($options);
unset($options['count']);

if ($count) {
    echo elgg_view('object/accept_privacy/list_header');
    echo elgg_list_entities($options);
}
else {
    echo elgg_echo('accept_tos:noresults');
}