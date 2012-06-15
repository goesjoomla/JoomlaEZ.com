<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Wrappers :: center column
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

if (!defined('RAW_OUTPUT') && $params->get('_modBreadcrumb')) : ?>
<div id="modBreadcrumb">
	<jdoc:include type="modules" name="breadcrumb<?php echo $params->get('_breadcrumbChrome'); ?>" />
</div>
<?php endif;

if ($params->get('_message')) : ?>
<div class="notice"><?php echo $params->get('_message'); ?></div>
<?php endif;

if ($params->get('_component')) : ?>
<div id="jezComponent">
	<jdoc:include type="component" />
</div>
<?php endif; ?>