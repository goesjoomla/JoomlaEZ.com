<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Wrappers :: extras position
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

if ($params->get('_modUser5')) : ?>
<div id="modUser5" class="<?php echo $params->get('_modUser5Style'); ?>">
	<jdoc:include type="modules" name="user5<?php echo $params->get('_user5Chrome'); ?>" />
</div>
<?php endif;

if ($params->get('_modUser6')) : ?>
<div id="modUser6" class="<?php echo $params->get('_modUser6Style'); ?>">
	<jdoc:include type="modules" name="user6<?php echo $params->get('_user6Chrome'); ?>" />
</div>
<?php endif;

if ($params->get('_modUser7')) : ?>
<div id="modUser7" class="<?php echo $params->get('_modUser7Style'); ?>">
	<jdoc:include type="modules" name="user7<?php echo $params->get('_user7Chrome'); ?>" />
</div>
<?php endif;

if ($params->get('_modUser8')) : ?>
<div id="modUser8" class="<?php echo $params->get('_modUser8Style'); ?>">
	<jdoc:include type="modules" name="user8<?php echo $params->get('_user8Chrome'); ?>" />
</div>
<?php endif; ?>
