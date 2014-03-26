<?php

$privacy = elgg_extract('entity', $vars, false);

$title = elgg_get_sticky_value('accept_tos', 'title');
if (!$title) {
    $title = $privacy->title;
}
echo '<label>' . elgg_echo('accept_tos:label:version') . '</label>';
echo elgg_view('input/text', array(
    'name' => 'title',
    'value' => $privacy->title
));

echo '<br><br>';

$description = elgg_get_sticky_value('accept_tos', 'description');
if (!$description) {
    $description = $privacy->description;
}
echo '<label>' . elgg_echo('accept_tos:label:tos') . '</label>';
echo elgg_view('input/longtext', array(
    'name' => 'description',
    'value' => $description
));

echo '<br><br>';

echo '<div class="elgg-foot">';
if ($privacy) {
    echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $privacy->guid));
}

echo elgg_view('input/submit', array('value' => elgg_echo('submit')));
echo '</div>';

elgg_clear_sticky_form('accept_tos');