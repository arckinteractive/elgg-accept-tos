<?php

$guid = get_input('guid');
$privacy = get_entity($guid);

if (!elgg_instanceof($privacy, 'object', 'accept_tos')) {
    register_error(elgg_echo('accept_tos:invalid:guid'));
    forward(REFERER);
}

if (!$privacy->canEdit()) {
    register_error(elgg_echo('accept_tos:error:no_delete'));
    forward(REFERER);
}

if ($privacy->delete()) {
    system_message(elgg_echo('accept_tos:success:delete'));
}
else {
    register_error(elgg_echo('accept_tos:error:generic:delete'));
}

forward(REFERER);