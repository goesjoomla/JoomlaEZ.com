<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Output Overrides
*
* @package		JEZ Thema
* @version		1.1.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://joomlaez.com/ for more information
*/

/*----------------------------------------------------------------------------*/

defined('_JEXEC') or die;
?>
<p class="info mod_whosonline<?php echo $params->get( 'moduleclass_sfx' ); ?>">
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
