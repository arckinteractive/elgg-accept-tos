<?php

define(ACCEPT_TOS_RELATIONSHIP, 'tos_agreement');
define(ACCEPT_TOS_PRIVACY_RELATIONSHIP, 'privacy_agreement');

require_once 'lib/hooks.php';
require_once 'lib/functions.php';
require_once 'lib/events.php';

function accept_tos_init() {
    elgg_extend_view('css/admin', 'accept_tos/css/admin');
    elgg_extend_view('css/elgg', 'accept_tos/css/elgg');
    elgg_extend_view('css/walled_garden', 'accept_tos/css/elgg');
	elgg_extend_view('register/extend', 'accept_tos/register/extend');
    
    $privacy_control = false;
    if (elgg_get_plugin_setting('control_privacy', 'accept_tos') == 'yes') {
        $privacy_control = true;
    }
    
    // check on every page load
    // we're only concerned if they're logged in
    if (elgg_is_logged_in()) {
        elgg_register_plugin_hook_handler('route', 'all', 'accept_tos_router', 0);
        elgg_register_plugin_hook_handler('index', 'system', 'accept_tos_index', 0);
        
        if ($privacy_control) {
            elgg_register_plugin_hook_handler('route', 'all', 'accept_tos_privacy_router', 0);
            elgg_register_plugin_hook_handler('index', 'system', 'accept_tos_privacy_index', 0);
        }
    }
    
    elgg_register_plugin_hook_handler('register', 'menu:entity', 'accept_tos_entity_menu', 1000);
    elgg_register_plugin_hook_handler('permissions_check', 'all', 'accept_tos_permissions_check', 1000);
    elgg_register_plugin_hook_handler('allowed_handlers', 'accept_tos', 'accept_tos_allowed_handlers');
	elgg_register_plugin_hook_handler('forward', 'all', 'accept_tos_forwarder');
	elgg_register_event_handler('create', 'user', 'accept_tos_register_user', 1000);
    
    elgg_register_entity_url_handler('object', 'accept_tos', 'accept_tos_url_handler');
    elgg_register_entity_url_handler('object', 'accept_privacy', 'accept_tos_privacy_url_handler');
    
    // override site pages
    elgg_register_page_handler('terms', 'accept_tos_terms_pagehandler');
    
    if ($privacy_control) {
        elgg_register_page_handler('privacy', 'accept_tos_terms_privacy_pagehandler');
    }
    
    elgg_register_action('accept_tos/edit', dirname(__FILE__) . '/actions/accept_tos/edit.php', 'admin');
    elgg_register_action('accept_tos/activate', dirname(__FILE__) . '/actions/accept_tos/activate.php', 'admin');
    elgg_register_action('accept_tos/delete', dirname(__FILE__) . '/actions/accept_tos/delete.php', 'admin');
    elgg_register_action('accept_tos/accept', dirname(__FILE__) . '/actions/accept_tos/accept.php');
    
    elgg_register_action('accept_privacy/edit', dirname(__FILE__) . '/actions/accept_privacy/edit.php', 'admin');
    elgg_register_action('accept_privacy/activate', dirname(__FILE__) . '/actions/accept_privacy/activate.php', 'admin');
    elgg_register_action('accept_privacy/delete', dirname(__FILE__) . '/actions/accept_privacy/delete.php', 'admin');
    elgg_register_action('accept_privacy/accept', dirname(__FILE__) . '/actions/accept_privacy/accept.php');
}


function accept_tos_url_handler($tos) {
    return elgg_get_site_url() . 'terms/' . $tos->guid;
}


function accept_tos_privacy_url_handler($privacy) {
    return elgg_get_site_url() . 'privacy/' . $privacy->guid;
}


function accept_tos_terms_pagehandler($page, $handler) {
    
    if ($page[0]) {
        $tos = get_entity($page[0]);
    }
    
    if (!$tos) {
        $tos = accept_tos_get_most_recent_tos();
    }
    
    if (!$tos && is_plugin_enabled('externalpages')) {
        return expages_page_handler($page, $handler);
    }
    
    if (!$tos) {
        return false;
    }
    
    $title = elgg_echo('accept_tos:label:tos');
    $content = elgg_view_title($title);
    $content .= elgg_view_entity($tos, array('full_view' => true));
    
    if (elgg_is_logged_in() || !elgg_get_config('walled_garden')) {
		$body = elgg_view_layout('one_sidebar', array('content' => $content));
		echo elgg_view_page($title, $body);
	} else {
		elgg_load_css('elgg.walled_garden');
		$body = elgg_view_layout('walled_garden', array('content' => $content));
		echo elgg_view_page($title, $body, 'walled_garden');
	}
	return true;
}


function accept_tos_terms_privacy_pagehandler($page, $handler) {
        
    if ($page[0]) {
        $privacy = get_entity($page[0]);
    }
    
    if (!$privacy) {
        $privacy = accept_tos_get_most_recent_privacy();
    }
    
    if (!$privacy && is_plugin_enabled('externalpages')) {
        return expages_page_handler($page, $handler);
    }
    
    if (!$privacy) {
        return false;
    }
    
    $title = elgg_echo('accept_tos:label:privacy');
    $content = elgg_view_title($title);
    $content .= elgg_view_entity($privacy, array('full_view' => true));
    
    if (elgg_is_logged_in() || !elgg_get_config('walled_garden')) {
		$body = elgg_view_layout('one_sidebar', array('content' => $content));
		echo elgg_view_page($title, $body);
	} else {
		elgg_load_css('elgg.walled_garden');
		$body = elgg_view_layout('walled_garden', array('content' => $content));
		echo elgg_view_page($title, $body, 'walled_garden');
	}
	return true;
}

elgg_register_event_handler('init', 'system', 'accept_tos_init');