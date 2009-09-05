<?php
/*
Plugin Name:	GitHub Bug Linker
Plugin URI:		http://alanhogan.com/projects/gh_bug
Description:	This plugin generates links to GitHub issues automatically into your posts/pages. Based on EHT Mantis.
Author:			Alan Hogan
Version:		0.3
Author URI:		http://alanhogan.com/

History:		0.3		Improved handling of issue links at the bottom.

Setup:
	1) Install the plugin.
	2) Configure.
	3) Refer to bugs like so: #1

*/

define ("GH_BUGS_PLUGIN_NAME", "gitbug-bugs");
define ("GH_BUGS_PLUGIN_TITLE", "GitHub Bug Linker");
define ("GH_BUGS_PLUGIN_URL_BASE", get_option ("siteurl") . "/wp-content/plugins/" . GH_BUGS_PLUGIN_NAME . "/");
define ("GH_BUGS_PLUGIN_PATH_BASE", $_SERVER["DOCUMENT_ROOT"] . "/wp-content/plugins/" . GH_BUGS_PLUGIN_NAME . "/");
define ("GH_BUGS_PLUGIN_VERSION", "0.3");
define ("GH_BUGS_OPTION_URL", GH_BUGS_PLUGIN_NAME . "-option-url");
define ("GH_BUGS_OPTION_TARGET_NEW", GH_BUGS_PLUGIN_NAME . "-option-target-new");
define ("GH_BUGS_YES", "yes");
define ("GH_BUGS_NO", "no");
define ("GH_BUGS_FIELD_ACTION", GH_BUGS_PLUGIN_NAME . "-field-action");
define ("GH_BUGS_ACTION_UPDATE", "Update");
define ("GH_BUGS_SLASH", strstr (PHP_OS, "WIN") ? "\\" : "/");
define ("GH_BUGS_FORMAT_LINK", "<a class=\"github-bug\" href=\"%sissues/%d/find\" %s>#%d</a>");
define ("GH_BUGS_ALL_LINKS_HEADER", "<div class=\"github-bugs-list\">\n<strong>Issue links:</strong> ");
define ("GH_BUGS_ALL_LINKS_FOOTER", '</div><!--github-bugs-list-->');
define ("GH_BUGS_ALL_LINKS_LINE_FIRST", "%s");
define ("GH_BUGS_ALL_LINKS_LINE", ", %s");
define ("GH_BUGS_CONTINUE_READING_TAG", "class=\"more-link\"");

require_once ("Admin.php");

add_filter ("the_content", "GithubBugsFilterTheContent");

function GithubBugsFilterTheContent ($content)
{
	$continueReading = (strpos ($content, GH_BUGS_CONTINUE_READING_TAG) !== false);

	$search = "/([^&])#(\d+)/i";
	preg_match_all ($search, $content, $results);

	if (is_array ($results[1]))
	{
		$optionUrl = get_option (GH_BUGS_OPTION_URL);
		$optionTargetNew = get_option (GH_BUGS_OPTION_TARGET_NEW);
		$target = (($optionTargetNew == GH_BUGS_YES) ? " target=\"_blank\"" : "");
		$links = array ();
		
		for ($m = 0; $m < count ($results[0]); $m++)
		{
			$previous = $results[1][$m];
			$id = trim ($results[2][$m]);
			
			$links[$m] = sprintf (GH_BUGS_FORMAT_LINK,
								  $optionUrl,
								  $id,
								  $target,
								  $id);
			$text = $previous . $links[$m];

			$content = str_replace ($results[0][$m], $text, $content); 
		}
		
		if ((count ($links) > 0) && (!$continueReading))
		{
			$links = array_unique($links, SORT_REGULAR);
			sort($links);
			$content .=  GH_BUGS_ALL_LINKS_HEADER;
			$first = true;
			foreach ($links as $link)
			{
				$content .= sprintf (($first ? GH_BUGS_ALL_LINKS_LINE_FIRST : GH_BUGS_ALL_LINKS_LINE), $link);
				$first = false;
			}
			$content .= GH_BUGS_ALL_LINKS_FOOTER;
		}
	}
	
	return ($content);
}

?>