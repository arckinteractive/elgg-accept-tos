<?php

$tos = $vars['entity'];

$title_link = elgg_view('output/url', array(
    'text' => $tos->title,
    'href' => $tos->getURL()
));


$date = date('m/d/Y, H:i', $tos->time_updated);

$menu = elgg_view_menu('entity', array(
	'entity' => $tos,
	'handler' => 'accept_tos',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$status = accept_tos_get_status($tos);

if (!$vars['full_view']) {
?>

<div class="accept-tos-list clearfix">
    <div class="accept-tos-version"><?php echo $title_link; ?></div>
    <div class="accept-tos-date"><?php echo $date; ?></div>
    <div class="accept-tos-date"><?php echo $status; ?></div>
    <div class="accept-tos-menu"><?php echo $menu; ?></div>
</div>

<?php
return;
}


/** FULL VIEW **/
$required = $tos->needsAgreement(elgg_get_logged_in_user_entity());

if ($required) {
    echo elgg_view('output/longtext', array(
        'value' => elgg_echo('accept_tos:agreement:required'),
        'class' => 'accept-tos-agreement-required'
    ));
}


echo elgg_echo('accept_tos:header:title') . ': ' . ($tos->title);
echo '<br>';
echo elgg_echo('accept_tos:header:date') . ': ' . date('m/d/Y, H:i', $tos->time_updated);

echo elgg_view('output/longtext', array(
    'value' => $tos->description
));


if ($required) {
    echo elgg_view_form('accept_tos/accept', array(), array('entity' => $tos));
}