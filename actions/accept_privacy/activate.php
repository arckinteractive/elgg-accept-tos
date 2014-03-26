<?php

$guid = get_input('guid');
$privacy = get_entity($guid);

if (!elgg_instanceof($privacy, 'object', 'accept_privacy')) {
    register_error(elgg_echo('accept_tos:invalid:guid'));
    forward(REFERER);
}

if (!accept_tos_can_activate_privacy($privacy)) {
    register_error(elgg_echo('accept_tos:error:no_activate'));
    forward(REFERER);
}

// de-activate previously activated TOS
$prev_active = elgg_get_entities_from_metadata(array(
    'type' => 'object',
    'subtype' => 'accept_privacy',
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

$privacy->accept_tos_activated = 1;
$privacy->access_id = ACCESS_PUBLIC;
$privacy->save();

elgg_set_ignore_access($ia);

system_message(elgg_echo('accept_tos:success:activated'));

forward(REFERER);