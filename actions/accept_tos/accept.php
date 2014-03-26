<?php

$user = elgg_get_logged_in_user_entity();
$guid = get_input('guid');
$accepted = get_input('tos_accept', false);
$forward_to = get_input('forward_to', $_SESSION['agreed_tos_destination']);

if (!$accepted) {
    register_error(elgg_echo('accept_tos:not_accepted'));
    forward(REFERER);
}

$tos = get_entity($guid);
if (!elgg_instanceof($tos, 'object', 'accept_tos')) {
    register_error(elgg_echo('accept_tos:invalid:guid'));
    forward(REFERER);
}

$tos->userAccepts($user);

system_message(elgg_echo('accept_tos:success:accepted'));
forward($forward_to);