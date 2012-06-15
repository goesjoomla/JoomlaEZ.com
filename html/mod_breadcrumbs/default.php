<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Output Overrides
*
* @package		JEZ Rego
* @version		1.5.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://www.joomlaez.com/ for more information
*/

/*----------------------------------------------------------------------------*/

defined('_JEXEC') or die;

if (!(isset($_COOKIE['jezTplName']) && isset($_COOKIE['jezTplDir']))) {
	// get template directory
	$tpl_dir = dirname(dirname(dirname(__FILE__)));
	setcookie('jezTplDir', $tpl_dir);

	// get the active template
	$template = basename($tpl_dir);
	setcookie('jezTplName', $template);
} else {
	$tpl_dir = $_COOKIE['jezTplDir'];
	$template = $_COOKIE['jezTplName'];
}

// is language loaded?
if ( preg_match('/\?*JEZ_REGO\?*/', JText::_('JEZ_REGO')) ) {
	$lang =& JFactory::getLanguage();
	$lang->load( "tpl_{$template}", $tpl_dir );
}

echo JText::_('You are here'); ?>:
<span class="mod_breadcrumbs<?php echo $params->get( 'moduleclass_sfx' ); ?>">
<?php
for ($i = 0; $i < $count; $i ++) {
	// get title attribute
	$parts = explode('::', $list[$i]->name);
	if (isset($parts[1]))
		$list[$i]->name = trim($parts[0]);

	// if not the last item in the breadcrumbs add the separator
	if ($i < $count -1) {
		if (!empty($list[$i]->link))
			echo '<a href="'.$list[$i]->link.'" class="noicon" title="'.(isset($parts[1]) ? trim($parts[1]) : $list[$i]->name).'">'.$list[$i]->name.'</a>';
		else
			echo $list[$i]->name;

		echo ' <span class="breadcrumb_separator">'.$separator.'</span> ';
	} else if ($params->get('showLast', 1)) // when $i == $count -1 and 'showLast' is true
		echo ' <span class="breadcrumb_current">'.$list[$i]->name.'</span> ';
}
?>
</span>
