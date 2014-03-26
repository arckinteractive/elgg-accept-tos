<?php

function accept_tos_router($hook, $type, $return, $params) {
    if (elgg_is_admin_logged_in()) {
        return $return;
    }
    
    $user = elgg_get_logged_in_user_entity();
    
    // allow some handlers by default
    $allow = elgg_trigger_plugin_hook('allowed_handlers', 'accept_tos', array(), array());
    
    if (in_array($type, $allow)) {
        return $return;
    }
    
    // now we need to check if we have a tos
    $tos = accept_tos_get_most_recent_tos();
    
    if (!$tos) {
        return $return;
    }
    
    //see if the user has agreed to this version
    if (!$tos->needsAgreement($user)) {
        return $return;
    }
    
    // make sure we can come back to where we were trying to get to
    $_SESSION['agreed_tos_destination'] = current_page_url();
    forward($tos->getURL());
}



function accept_tos_privacy_router($hook, $type, $return, $params) {
    if (elgg_is_admin_logged_in()) {
        return $return;
    }
    
    $user = elgg_get_logged_in_user_entity();
    
    // allow some handlers by default
    $allow = elgg_trigger_plugin_hook('allowed_handlers', 'accept_tos', array(), array());
    
    if (in_array($type, $allow)) {
        return $return;
    }
    
    // now we need to check if we have a tos
    $privacy = accept_tos_get_most_recent_privacy();
    
    if (!$privacy) {
        return $return;
    }
    
    //see if the user has agreed to this version
    if (!$privacy->needsAgreement($user)) {
        return $return;
    }
    
    // make sure we can come back to where we were trying to get to
    $_SESSION['agreed_tos_destination'] = current_page_url();
    forward($privacy->getURL());
}


/**
 *  Make the entity menu only for admins (should be anyway)
 * 
 * @param type $hook
 * @param type $type
 * @param type $return
 * @param type $params
 */
function accept_tos_entity_menu($hook, $type, $return, $params) {
    $allowed_subtypes = array('accept_tos', 'accept_privacy');
    $subtype = $params['entity']->getSubtype();
    if ($params['handler'] != 'accept_tos' || !in_array($subtype, $allowed_subtypes)) {
        return $return;
    }
    
    if (!elgg_is_admin_logged_in()) {
        return array();
    }
    
    // remove anything that isn't edit or delete
    $keep = array('edit', 'delete');
    foreach ($return as $key => $item) {
        if (!in_array($item->getName(), $keep)) {
            unset($return[$key]);
        }
        
        // update the url for edit
        if ($item->getName() == 'edit') {
            $return[$key]->setHref("admin/{$subtype}/edit?guid=" . $params['entity']->guid);
        }
    }
    
    $activation_check = 'accept_tos_can_activate';
    if ($subtype == 'accept_privacy') {
        $activation_check = 'accept_tos_can_activate_privacy';
    }
    
    // add in the activation link if we can activate it
    if ($activation_check($params['entity']) && $params['entity']->canEdit()) {
        $href = elgg_add_action_tokens_to_url("action/{$subtype}/activate?guid=" . $params['entity']->guid);
        $activate = new ElggMenuItem('activate', elgg_echo('accept_tos:activate'), $href);
        $activate->setConfirmText(elgg_echo("accept_tos:activate:confirm:{$subtype}"));
        
        $return[] = $activate;
    }
    
    $view = new ElggMenuItem('view', elgg_echo('view'), $params['entity']->getURL());
    $return[] = $view;
    
    return $return;
}


function accept_tos_permissions_check($hook, $type, $return, $params) {
    $tos = $params['entity'];
    $user = $params['user'];
    
    // check for both tos and privacy subtypes
    if (!elgg_instanceof($tos, 'object', 'accept_tos') && !elgg_instanceof($tos, 'object', 'accept_privacy')) {
        return $return;
    }
	
	if (!elgg_is_logged_in()) {
        return false;
    }
    
    if ($user && !$user->isAdmin()) {
        return false;
    }
    
    if (elgg_get_ignore_access()) {
        return true;
    }

    switch ($tos->getSubtype()) {
        case 'accept_privacy':
            if (!accept_tos_can_activate_privacy($tos)) {
                return false;
            }        
            break;
        
        default:
            if (!accept_tos_can_activate($tos)) {
                return false;
            }
            break;
    }
    
    return true;
}


function accept_tos_index($hook, $type, $return, $params) {
    if (elgg_is_logged_in()) {
        $tos = accept_tos_get_most_recent_tos();
        
        if ($tos && $tos->needsAgreement(elgg_get_logged_in_user_entity())) {
            // make sure we can come back to where we were trying to get to
            $_SESSION['agreed_tos_destination'] = current_page_url();
            forward($tos->getURL());
        }
    }
}


function accept_tos_privacy_index($hook, $type, $return, $params) {
    if (elgg_is_logged_in()) {
        $privacy = accept_tos_get_most_recent_privacy();
        
        if ($privacy && $privacy->needsAgreement(elgg_get_logged_in_user_entity())) {
            // make sure we can come back to where we were trying to get to
            $_SESSION['agreed_tos_destination'] = current_page_url();
            forward($privacy->getURL());
        }
    }
}



function accept_tos_allowed_handlers($hook, $type, $return, $params) {
    if (!is_array($return)) {
        $return = array();
    }
    
    $allowed = array(
        'action',
        'ajax',
        'css',
        'js',
        'terms',
        'privacy',
        'reportedcontent'
    );
    
    return array_merge($return, $allowed);
}


function accept_tos_forwarder($hook, $type, $return, $params) {
	if ($params['current_url'] != elgg_get_site_url() . 'action/reportedcontent/add') {
		return $return;
	}
	
	if (!elgg_is_logged_in()) {
		return $return;
	}
	
	// now we need to check if we have a tos
	$user = elgg_get_logged_in_user_entity();
    $tos = accept_tos_get_most_recent_tos();
    
    if (!$tos) {
        return $return;
    }
    
    //see if the user has agreed to this version
    if (!$tos->needsAgreement($user)) {
        return $return;
    }
	
	// they declined to agree, so we log them out and forward them home
	logout();
	forward();
}