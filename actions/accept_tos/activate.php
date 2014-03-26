<?php

$guid = get_input('guid');
$tos = get_entity($guid);

if (!elgg_instanceof($tos, 'object', 'accept_tos')) {
    register_error(elgg_echo('accept_tos:invalid:guid'));
    forward(REFERER);
}

if (!accept_tos_can_activate($tos)) {
    register_error(elgg_echo('accept_tos:error:no_activate'));
    forward(REFERER);
}

// de-activate previously activated TOS
$prev_active = elgg_get_entities_from_metadata(array(
    'type' => 'object',
    'subtype' => 'accept_tos',
    'metadata_name_value_pairs' => array(
        'name' => 'accept_tos_activated',
        'value' => 1
    ),
    'limit' => false
));


// disable permissions checks as we can't edit an activated TOS
$ia = elgg_set_ignore_access(true);
foreach ($prev_active as $e) {
    $e->accept_tos_activated = 0;
    $e->access_id = ACCESS_PRIVATE;
    $e->save();
}

$tos->accept_tos_activated = 1;
$tos->access_id = ACCESS_PUBLIC;
$tos->save();

elgg_set_ignore_access($ia);

system_message(elgg_echo('accept_tos:success:activated'));

forward(REFERER);