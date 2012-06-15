<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Wrappers :: bottom position
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

if ($params->get('_modInset')) : ?>
<div id="modInset" class="fl">
	<jdoc:include type="modules" name="inset<?php echo $params->get('_insetChrome'); ?>" />
</div>
<?php endif;

if ($params->get('bottomNav') || $params->get('_modSyndicate')) : ?>
<div id="modSyndicate" class="fr tar">
	<?php if ($params->get('bottomNav')) : ?>
	<a class="backward" href="javascript:history.go(-1);" title="<?php echo JText::_('Back to Previous Page'); ?>"><?php echo JText::_('Back'); ?></a>
	<a class="upward" href="#jezPage" title="<?php echo JText::_('Jump to Top of Page'); ?>"><?php echo JText::_('Top'); ?></a>
	<?php endif;

	if ($params->get('_modSyndicate')) : ?>
	<jdoc:include type="modules" name="syndicate<?php echo $params->get('_syndicateChrome'); ?>" />
	<?php endif; ?>
</div>
<?php endif; ?>
