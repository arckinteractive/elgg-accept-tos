<?php

echo elgg_view('accept_tos/admin/navigation');

// require users to agree to each new version of the TOS?
echo elgg_view('input/dropdown', array(
    'name' => 'params[require_most_recent]',
    'value' => $vars['entity']->require_most_recent ? $vars['entity']->require_most_recent : 'yes',
    'options_values' => array(
        'yes' => elgg_echo('option:yes'),
        'no' => elgg_echo('option:no')
    )
));

echo '&nbsp;<label>' . elgg_echo('accept_tos:require_most_recent:label') . '</label>';

echo elgg_view('output/longtext', array(
    'value' => elgg_echo('accept_tos:require_most_recent:help'),
    'class' => 'elgg-subtext'
));

echo '<br><br>';


// require users to agree to each new version of the TOS?
echo elgg_view('input/dropdown', array(
    'name' => 'params[control_privacy]',
    'value' => $vars['entity']->control_privacy ? $vars['entity']->control_privacy : 'no',
    'options_values' => array(
        'yes' => elgg_echo('option:yes'),
        'no' => elgg_echo('option:no')
    )
));

echo '&nbsp;<label>' . elgg_echo('accept_tos:label:control_privacy') . '</label>';

echo elgg_view('output/longtext', array(
    'value' => elgg_echo('accept_tos:help:control_privacy'),
    'class' => 'elgg-subtext'
));

echo '<br><br>';


// require users to agree to each new version of the TOS?
echo elgg_view('input/dropdown', array(
    'name' => 'params[privacy_require_most_recent]',
    'value' => $vars['entity']->privacy_require_most_recent ? $vars['entity']->privacy_require_most_recent : 'no',
    'options_values' => array(
        'yes' => elgg_echo('option:yes'),
        'no' => elgg_echo('option:no')
    )
));

echo '&nbsp;<label>' . elgg_echo('accept_tos:label:privacy_require_most_recent') . '</label>';

echo elgg_view('output/longtext', array(
    'value' => elgg_echo('accept_tos:help:privacy_require_most_recent'),
    'class' => 'elgg-subtext'
));