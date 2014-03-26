<?php

$guid = get_input('guid');
$tos = get_entity($guid);

if (!elgg_instanceof($tos, 'object', 'accept_tos')) {
    register_error(elgg_echo('accept_tos:invalid:guid'));
    forward(REFERER);
}

if (!$tos->canEdit()) {
    register_error(elgg_echo('accept_tos:error:no_delete'));
    forward(REFERER);
}

if ($tos->delete()) {
    system_message(elgg_echo('accept_tos:success:delete'));
}
else {
    register_error(elgg_echo('accept_tos:error:generic:delete'));
}

forward(REFERER);