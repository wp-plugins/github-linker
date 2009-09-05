<?php

add_action ("admin_menu", "GitubBugsAdminAddPages");

function GitubBugsAdminAddPages ()
{
	if (function_exists ("add_options_page"))
	{
		add_options_page (GH_BUGS_PLUGIN_TITLE, 
						  GH_BUGS_PLUGIN_TITLE,
						  8, 
						  GH_BUGS_PLUGIN_TITLE . "-options", 
						  "GitubBugsAdminOptions");
	}
}

function GitubBugsAdminOptions ()
{
	$action = $_REQUEST[GH_BUGS_FIELD_ACTION];
	if ($action == GH_BUGS_ACTION_UPDATE)
	{
		$optionUrl = $_REQUEST[GH_BUGS_OPTION_URL];
		$optionTargetNew = $_REQUEST[GH_BUGS_OPTION_TARGET_NEW];
	}
	else
	{
		$optionUrl = get_option (GH_BUGS_OPTION_URL);
		$optionTargetNew = get_option (GH_BUGS_OPTION_TARGET_NEW);
	}

	$firstUse = ($optionUrl == "") &&
				($optionTargetNew == "");
	
	$optionTargetNew = (($optionTargetNew == "") ? GH_BUGS_NO : GH_BUGS_YES);

	if ($action == GH_BUGS_ACTION_UPDATE)
	{
        update_option (GH_BUGS_OPTION_URL, $optionUrl);
        update_option (GH_BUGS_OPTION_TARGET_NEW, $optionTargetNew);
        echo "<div class=\"updated\">The options have been updated.</div>\n";
	}

	echo "<div class=\"wrap\">\n" .
		 "<h2>GitHub Bug Links</h2>\n" .
		 "<form method=\"post\" action=\"" . str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) . "\">\n" .
		 "<p>GitHub Project URL (with the end slash). Example: <code>http://github.com/myusername/myproject/</code><br>\n" .
		 "<input type=\"text\" name=\"" . GH_BUGS_OPTION_URL . "\" value=\"$optionUrl\"></p>\n" .
		 "<p><input type=\"checkbox\" name=\"" . GH_BUGS_OPTION_TARGET_NEW . "\" value=\"" . GH_BUGS_YES . "\" " . (($optionTargetNew == GH_BUGS_YES) ? "checked" : "") . ">\n" .
		 "Open GitHub bug links in a new window</p>\n" .
		 "<p class=\"submit\">\n" .
		 "<input type=\"submit\" name=\"" . GH_BUGS_FIELD_ACTION . "\" value=\"" . GH_BUGS_ACTION_UPDATE . "\" default>\n" .
		 "</p>\n" .
		 "</form>\n" .
		 "</div>\n";
}

?>