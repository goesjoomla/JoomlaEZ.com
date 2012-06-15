<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Wrappers :: body
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

if ($params->get('_message') || $params->get('_modBreadcrumb') || $params->get('_component'))
	jezWrapper($params->get('wrapperMain'), 'main', $params, 'jezMain', 'fl '.$params->get('_centerStyle'));

if (!defined('RAW_OUTPUT') && $params->get('_modRight')) : ?>
<div id="jezSub" class="fr <?php echo $params->get('_rightStyle'); ?>">
	<jdoc:include type="modules" name="right<?php echo $params->get('_rightChrome'); ?>" />
</div>
<?php endif;

if (!defined('RAW_OUTPUT') && $params->get('_modLeft')) : ?>
<div id="jezLocal" class="fl <?php echo $params->get('_leftStyle'); ?>">
	<jdoc:include type="modules" name="left<?php echo $params->get('_leftChrome'); ?>" />
</div>
<?php endif; ?>