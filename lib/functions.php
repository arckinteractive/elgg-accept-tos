<?php

function accept_tos_get_most_recent_tos() {
    $tos = elgg_get_entities_from_metadata(array(
        'type' => 'object',
        'subtype' => 'accept_tos',
        'metadata_name_value_pairs' => array(
            'name' => 'accept_tos_activated',
            'value' => 1
        ),
        'limit' => 1
    ));
    
    if ($tos) {
        return $tos[0];
    }
    
    return false;
}



function accept_tos_get_most_recent_privacy() {
    $tos = elgg_get_entities_from_metadata(array(
        'type' => 'object',
        'subtype' => 'accept_privacy',
        'metadata_name_value_pairs' => array(
            'name' => 'accept_tos_activated',
            'value' => 1
        ),
        'limit' => 1
    ));

    if ($tos) {
        return $tos[0];
    }
    
    return false;
}


/*
 * determine if a user has accepted ANY tos
 */
function accept_tos_has_accepted_any_tos($user) {
    if (!elgg_instanceof($user, 'user')) {
        return false;
    }
    $ia = elgg_set_ignore_access(true);
    $tos = $user->getEntitiesFromRelationship(ACCEPT_TOS_RELATIONSHIP);
    elgg_set_ignore_access($ia);
    
    if ($tos) {
        return true;
    }
    
    return false;
}


/*
 * determine if a user has accepted ANY privacy
 */
function accept_tos_has_accepted_any_privacy($user) {
    if (!elgg_instanceof($user, 'user')) {
        return false;
    }
    $ia = elgg_set_ignore_access(true);
    $privacy = $user->getEntitiesFromRelationship(ACCEPT_TOS_PRIVACY_RELATIONSHIP);
    elgg_set_ignore_access($ia);
    
    if ($privacy) {
        return true;
    }
    
    return false;
}


/**
 * Can this TOS be activated?
 * @param type $tos
 * @return bool
 */
function accept_tos_can_activate($tos) {
    if (!elgg_instanceof($tos, 'object', 'accept_tos')) {
        return false;
    }
    
    // we can activate it if there are no active versions created after this one
    $active = accept_tos_get_most_recent_tos();
    
    if ($active) {
        if ($tos->time_created > $active->time_created) {
            return true;
        }
        
        return false;
    }
    
    return true;
}



/**
 * Can this TOS be activated?
 * @param type $tos
 * @return bool
 */
function accept_tos_can_activate_privacy($privacy) {
    if (!elgg_instanceof($privacy, 'object', 'accept_privacy')) {
        return false;
    }
    
    // we can activate it if there are no active versions created after this one
    $active = accept_tos_get_most_recent_privacy();
    
    if ($active) {
        if ($privacy->time_created > $active->time_created) {
            return true;
        }
        
        return false;
    }
    
    return true;
}



function accept_tos_get_status($tos, $translate = true) {
    if (!elgg_instanceof($tos, 'object', 'accept_tos')) {
        return $translate ? elgg_echo('accept_tos:status:undefined') : 'accept_tos:status:undefined';
    }
    
    if ($tos->accept_tos_activated) {
        return $translate ? elgg_echo('accept_tos:status:active') : 'accept_tos:status:active';
    }
    
    // so it's not active, must be archived or a draft
    if (!accept_tos_can_activate($tos)) {
        // we can't activate it, it must be a archived
        return $translate ? elgg_echo('accept_tos:status:archived') : 'accept_tos:status:archived';
    }
    
    // process of elimination, must be a draft
    return $translate ? elgg_echo('accept_tos:status:draft') : 'accept_tos:status:draft';
}


function accept_tos_get_privacy_status($privacy, $translate = true) {
    if (!elgg_instanceof($privacy, 'object', 'accept_privacy')) {
        return $translate ? elgg_echo('accept_tos:status:undefined') : 'accept_tos:status:undefined';
    }
    
    if ($privacy->accept_tos_activated) {
        return $translate ? elgg_echo('accept_tos:status:active') : 'accept_tos:status:active';
    }

    // so it's not active, must be archived or a draft
    if (!accept_tos_can_activate_privacy($privacy)) {
        // we can't activate it, it must be a archived
        return $translate ? elgg_echo('accept_tos:status:archived') : 'accept_tos:status:archived';
    }
    
    // process of elimination, must be a draft
    return $translate ? elgg_echo('accept_tos:status:draft') : 'accept_tos:status:draft';
}