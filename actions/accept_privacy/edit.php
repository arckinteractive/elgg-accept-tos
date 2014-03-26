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
    $privacy = get_entity($guid);
    if (!elgg_instanceof($privacy, 'object', 'accept_privacy') || !$privacy->canEdit()) {
        register_error(elgg_echo('accept_tos:permissions'));
        forward(REFERER);
    }
}
else {
    $privacy = new AcceptPrivacy();
    $privacy->container_guid = elgg_get_site_entity()->guid;
    $privacy->access_id = ACCESS_PRIVATE;
    $privacy->owner_guid = elgg_get_logged_in_user_guid();
}

$privacy->title = $title;
$privacy->description = $description;

if (!$privacy->save()) {
    register_error(elgg_echo('accept_tos:error:save_failure'));
    forward(REFERER);
}

system_message(elgg_echo('accept_tos:edit:success'));

elgg_clear_sticky_form('accept_tos');

forward('admin/accept_privacy');