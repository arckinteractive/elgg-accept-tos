<?php

class AcceptPrivacy extends ElggObject {

	/**
	 * Set subtype to object.
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "accept_privacy";
	}

    /*
     * determine if a user has agreed to these tos
     */
    public function userAgreed($user) {
        if (!elgg_instanceof($user, 'user')) {
            return false;
        }
        
        return check_entity_relationship($user->guid, ACCEPT_TOS_PRIVACY_RELATIONSHIP, $this->guid);
    }

    /**
     * determine if the user needs to agree to this TOS
     * 
     * @param type $user
     * @return boolean
     */
    public function needsAgreement($user) {
        if (!$user) {
            $user = elgg_get_logged_in_user_entity();
        }
        
        if (!$user) {
            return false;
        }
        
        if ($user->isAdmin()) {
            return false;
        }
        
        if ($this->userAgreed($user)) {
            return false;
        }
        
        // depending on the plugin setting
        $require = (elgg_get_plugin_setting('privacy_require_most_recent', 'accept_tos') != 'no') ? true : false;
        $previously_agreed = accept_tos_has_accepted_any_privacy($user);
        
        if ($require) {        
            // if this is the most recent version we need to agree
            $most_recent = accept_tos_get_most_recent_privacy();
            if ($most_recent->guid == $this->guid) {
                // this is most recent
                return true;
            }
            
            return false;
        }
        
        if ($previously_agreed) {
            return false;
        }
        
        // if we get this far
        return true;
    }
    
    
    public function userAccepts($user) {
        add_entity_relationship($user->guid, ACCEPT_TOS_PRIVACY_RELATIONSHIP, $this->guid);
    }
}