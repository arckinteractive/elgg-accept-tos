<?php

$guid = get_input('guid');
$tos = get_entity($guid);

if (!elgg_instanceof($tos, 'object', 'accept_tos')) {
    register_error(elgg_echo('accept_tos:invalid:guid'));
    forward(REFERER);
}

if (!$tos->canEdit()) {
    register_error(elgg_echo('accept_tos:error:no_edit'));
    forward(REFERER);
}

echo elgg_view_form('accept_tos/edit', array(), array(
    'entity' => $tos
));