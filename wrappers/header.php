<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Wrappers :: header
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

if ($params->get('_modLogo') || $params->get('logo') != '') : ?>
<div id="jezLogo" class="fl">
	<?php if ($params->get('_modLogo')) : ?>
	<jdoc:include type="modules" name="logo<?php echo $params->get('_logoChrome'); ?>" />
	<?php elseif ($params->get('logo') != '') : $document =& JFactory::getDocument(); ?>
	<a href="<?php echo $params->get('_baseurl'); ?>/" title="<?php echo $document->getTitle(); ?>">
		<img src="<?php echo $params->get('_baseurl').'/'.$params->get('logo'); ?>" alt="<?php echo $document->getTitle(); ?>" class="logo png24" /></a>
	<?php endif; ?>
</div>
<?php endif;

if (($params->get('topNav') && ($params->get('_colsCount') || $params->get('_navCount'))) || $params->get('_modTop'))
	jezWrapper($params->get('wrapperTop'), 'top', $params, 'jezTop', 'fr'); ?>
