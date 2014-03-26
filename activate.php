<?php

if (get_subtype_id('object', 'accept_tos')) {
	update_subtype('object', 'accept_tos', 'AcceptTOS');
} else {
	add_subtype('object', 'accept_tos', 'AcceptTOS');
}

if (get_subtype_id('object', 'accept_privacy')) {
	update_subtype('object', 'accept_privacy', 'AcceptPrivacy');
} else {
	add_subtype('object', 'accept_privacy', 'AcceptPrivacy');
}
