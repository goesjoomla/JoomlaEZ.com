<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Wrappers :: top position
*
* @package		JEZ Thema
* @version		1.1.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://joomlaez.com/ for more information
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if ($params->get('topNav') && ($params->get('_colsCount') || $params->get('_navCount'))) : ?>
<ul class="menu"><li><span class="separator bold"><?php echo JText::_('Jump to'); ?>:</span></li><li>
	<?php
	$topNav = array();

	if ($params->get('_message') || $params->get('_usersCount') || $params->get('_component') || $params->get('_modBottom'))
		$topNav[] = '<a href="#jezMain" title="'.JText::_('Main Content').'">'.JText::_('Main Content').'</a>';

	if ($params->get('_modRight'))
		$topNav[] = '<a href="#jezSub" title="'.JText::_('Sub Content').'">'.JText::_('Sub Content').'</a>';

	if ($params->get('_modLeft'))
		$topNav[] = '<a href="#jezLocal" title="'.JText::_('Local Menu').'">'.JText::_('Local Menu').'</a>';

	if ($params->get('_navCount'))
		$topNav[] = '<a href="#jezNav" title="'.JText::_('Global Menu').'">'.JText::_('Global Menu').'</a>';

	echo implode('</li><li>', $topNav);
	?>
</li></ul>
<?php endif;

if ($params->get('_modTop')) : ?>
<jdoc:include type="modules" name="top<?php echo $params->get('_topChrome'); ?>" />
<?php endif; ?>
