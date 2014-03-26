<?php

function accept_tos_register_user($event, $type, $user) {

	$tos = accept_tos_get_most_recent_tos();
	if ($tos && elgg_is_active_plugin('profile_manager')) {
		$terms = get_input('accept_terms');
		
		if ($terms == 'yes') {
			// lets make mark the tos as accepted
			
			$tos->userAccepts($user);
		}
	}
}