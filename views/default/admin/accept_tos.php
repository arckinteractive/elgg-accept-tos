<?php

echo elgg_view('accept_tos/admin/navigation');


echo elgg_view('output/url', array(
    'text' => elgg_echo('accept_tos:add'),
    'href' => 'admin/accept_tos/add',
    'class' => 'elgg-button elgg-button-action'
));

echo '<br><br>';


$options = array(
    'type' => 'object',
    'subtype' => 'accept_tos',
    'full_view' => false,
    'count' => true,
);

$count = elgg_get_entities($options);
unset($options['count']);

if ($count) {
    echo elgg_view('object/accept_tos/list_header');
    echo elgg_list_entities($options);
}
else {
    echo elgg_echo('accept_tos:noresults');
}