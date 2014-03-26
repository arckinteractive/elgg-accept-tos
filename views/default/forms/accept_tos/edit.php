<?php

$tos = elgg_extract('entity', $vars, false);

$title = elgg_get_sticky_value('accept_tos', 'title');
if (!$title) {
    $title = $tos->title;
}
echo '<label>' . elgg_echo('accept_tos:label:version') . '</label>';
echo elgg_view('input/text', array(
    'name' => 'title',
    'value' => $tos->title
));

echo '<br><br>';

$description = elgg_get_sticky_value('accept_tos', 'description');
if (!$description) {
    $description = $tos->description;
}
echo '<label>' . elgg_echo('accept_tos:label:tos') . '</label>';
echo elgg_view('input/longtext', array(
    'name' => 'description',
    'value' => $description
));

echo '<br><br>';

echo '<div class="elgg-foot">';
if ($tos) {
    echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $tos->guid));
}

echo elgg_view('input/submit', array('value' => elgg_echo('submit')));
echo '</div>';

elgg_clear_sticky_form('accept_tos');