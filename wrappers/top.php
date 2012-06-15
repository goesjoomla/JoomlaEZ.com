<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Wrappers :: top position
*
* @package		JEZ Rego
* @version		1.5.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://www.joomlaez.com/ for more information
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if ($params->get('_modUser4')) :
	jimport('joomla.application.module.helper');
	$user4Mod =& JModuleHelper::getModules('user4');
	$modParams = new JParameter($user4Mod[0]->params); ?>
<div id="modUser4" class="fl <?php echo ($modParams->get('moduleclass_sfx') == '_special' ? 'special ' : '').$params->get('_user4Style'); ?>">
	<jdoc:include type="modules" name="user4<?php echo $modParams->get('moduleclass_sfx') == '_special' ? '' : $params->get('_user4Chrome'); ?>" />
	<?php if ( $modParams->get('moduleclass_sfx') == '_special' ) : ?>
	<div class="user4SpecialTL"><!-- Top left corner --></div>
	<div class="user4SpecialTR"><!-- Top right corner --></div>
	<div class="user4SpecialBL"><!-- Bottom left corner --></div>
	<div class="user4SpecialBR"><!-- Bottom right corner --></div>
	<?php endif; ?>
</div>
<?php endif;

if ($params->get('_modUser2')) : ?>
<div id="modUser2" class="fr <?php echo $params->get('_user2Style'); ?>">
	<jdoc:include type="modules" name="user2<?php echo $params->get('_user2Chrome'); ?>" />
</div>
<?php endif;

if ($params->get('_modUser1')) : ?>
<div id="modUser1" class="fl <?php echo $params->get('_user1Style'); ?>">
	<jdoc:include type="modules" name="user1<?php echo $params->get('_user1Chrome'); ?>" />
</div>
<?php endif; ?>