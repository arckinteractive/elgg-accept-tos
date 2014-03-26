<?php

$privacy = $vars['entity'];

echo '<br><br>';

echo elgg_view('input/checkbox', array(
    'name' => 'tos_accept',
    'value' => 1
));
echo '<label>' . elgg_echo('accept_tos:label:privacy:accept:terms') . '</label>*';

echo '<br><br>';

echo '<label>' . elgg_echo('accept_tos:label:redirect_to') . '</label>';
// redirect to...
$original_dest = elgg_get_site_url();
if ($_SESSION['agreed_tos_destination']) {
    $original_dest = $_SESSION['agreed_tos_destination'];
    unset($_SESSION['agreed_tos_destination']);
}

$options = array(
    'name' => 'forward_to',
    'value' => $original_dest,
    'options' => array(
        elgg_echo('accept_tos:home') => elgg_get_site_url(),
        elgg_echo('accept_tos:profile_edit') => elgg_get_site_url() . 'profile/' . elgg_get_logged_in_user_entity()->username . '/edit',
    )
);

if (!elgg_http_url_is_identical($original_dest, elgg_get_site_url())) {
    $options['options'] = array_merge(
            array(elgg_echo('accept_tos:original_destination', array($original_dest)) => $original_dest),
            $options['options']
            );
}

echo elgg_view('input/radio', $options);
echo '<br><br>';

echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $privacy->guid));
echo elgg_view('input/submit', array('value' => elgg_echo('submit')));

if (elgg_is_active_plugin('reportedcontent')) {
    echo elgg_view('output/url', array(
        'text' => elgg_echo('accept_tos:decline'),
        'href' => 'reportedcontent/add?address=' . urlencode(current_page_url()) . '&title=' . urlencode('Privacy Policy'),
        'class' => 'elgg-button elgg-button-delete elgg-requires-confirmation',
        'is_trusted' => true,
        'rel' => elgg_echo('accept_tos:decline:why'),
    ));
}