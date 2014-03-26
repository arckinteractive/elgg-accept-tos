<?php

$tos = accept_tos_get_most_recent_tos();

if (!$tos) {
	return;
}

if (elgg_is_active_plugin('profile_manager')) {
	// must accept terms
	if($accept_terms = elgg_get_plugin_setting("registration_terms", "profile_manager")){
		$link_begin = "<a target='_blank' href='" . $accept_terms . "'>";
		$link_end = "</a>";
		
		$terms = "<div class='mandatory accept-tos-accept_terms'>";
		$terms .= "<input id='accepttos-accept_terms' type='checkbox' name='accept_terms' value='yes' /> ";
		$terms .= "<label for='accepttos-accept_terms'>" . elgg_echo("profile_manager:registration:accept_terms", array($link_begin, $link_end)) . "</label>";
		$terms .= "</div>";
	}
}

echo $terms;

// there may be duplicate terms acceptance depending on the view - so use jquery magick to remove duplicates
?>

<script>
	$(document).ready(function() {
		var remove_terms = false;
		$('input[name="accept_terms"]').each(function() {
			if (!remove_terms) {
				remove_terms = true;
				return;
			}
			
			$(this).parent().remove();
		});
	});
</script>