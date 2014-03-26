<?php

$guid = get_input('guid');
$privacy = get_entity($guid);

if (!elgg_instanceof($privacy, 'object', 'accept_privacy')) {
    register_error(elgg_echo('accept_tos:invalid:guid'));
    forward(REFERER);
}

if (!$privacy->canEdit()) {
    register_error(elgg_echo('accept_tos:error:no_edit'));
    forward(REFERER);
}

echo elgg_view_form('accept_privacy/edit', array(), array(
    'entity' => $privacy
));