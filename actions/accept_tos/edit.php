<?php

elgg_make_sticky_form('accept_tos');

$guid = get_input('guid');
$title = get_input('title');
$description = get_input('description');

if (empty($title) || empty($description)) {
    register_error(elgg_echo('accept_tos:error:missing_fields'));
    forward(REFERER);
}

if ($guid) {
    $tos = get_entity($guid);
    if (!elgg_instanceof($tos, 'object', 'accept_tos') || !$tos->canEdit()) {
        register_error(elgg_echo('accept_tos:permissions'));
        forward(REFERER);
    }
}
else {
    $tos = new AcceptTOS();
    $tos->container_guid = elgg_get_site_entity()->guid;
    $tos->access_id = ACCESS_PRIVATE;
    $tos->owner_guid = elgg_get_logged_in_user_guid();
}

$tos->title = $title;
$tos->description = $description;

if (!$tos->save()) {
    register_error(elgg_echo('accept_tos:error:save_failure'));
    forward(REFERER);
}

system_message(elgg_echo('accept_tos:edit:success'));

elgg_clear_sticky_form('accept_tos');

forward('admin/accept_tos');