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
?>
<p class="mod_whosonline<?php echo $params->get( 'moduleclass_sfx' ); ?> info">
<?php if ($showmode == 0 || $showmode == 2) {
	if ($count['guest'] != 0 || $count['user'] != 0) {
		echo JText::_('We have') . '&nbsp;';

		if ($count['guest'] == 1)
			echo JText::sprintf('guest', '1');
		else {
			if ($count['guest'] > 1)
				echo JText::sprintf('guests', $count['guest']);
		}

		if ($count['guest'] != 0 && $count['user'] != 0)
			echo '&nbsp;' . JText::_('and') . '&nbsp;';

		if ($count['user'] == 1)
			echo JText::sprintf('member', '1');
		else {
			if ($count['user'] > 1)
				echo JText::sprintf('members', $count['user']);
		}

		echo '&nbsp;' . JText::_('online');
	}
}

if (($showmode > 0) && count($names)) : ?>
<ul>
<?php foreach($names as $name) : ?>
<li class="bold"><?php echo $name->username; ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</p>
