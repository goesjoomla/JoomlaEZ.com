<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Wrappers :: center column
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

if ($params->get('_message')) : ?>
<div class="notice"><?php echo $params->get('_message'); ?></div>
<?php endif;

if (!defined('RAW_OUTPUT') && $params->get('_usersCount'))
	jezWrapper($params->get('wrapperUsers'), 'users', $params, 'jezUsers', '', true, $params->get('_usersCount'));

if ($params->get('_component'))
	jezWrapper($params->get('wrapperComponent'), 'component', $params, 'jezComponent', '', true);

if (!defined('RAW_OUTPUT') && $params->get('_modBottom')) : ?>
<div id="modBottom" class="fluid">
	<jdoc:include type="modules" name="bottom<?php echo $params->get('_bottomChrome'); ?>" />
</div>
<?php endif; ?>
