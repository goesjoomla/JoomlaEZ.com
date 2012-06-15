<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Wrappers :: users position
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

if ($params->get('_modUser1')) : ?>
<div id="modUser1" class="fl<?php echo $params->get('_modUser1Style'); ?>">
	<jdoc:include type="modules" name="user1<?php echo $params->get('_user1Chrome'); ?>" />
</div>
<?php endif;

if ($params->get('_modUser2')) : ?>
<div id="modUser2" class="fr">
	<jdoc:include type="modules" name="user2<?php echo $params->get('_user2Chrome'); ?>" />
</div>
<?php endif; ?>
