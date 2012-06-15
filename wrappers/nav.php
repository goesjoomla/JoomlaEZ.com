<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Wrappers :: navigation position
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

if ($params->get('_modUser3')) : ?>
<div id="modUser3" class="fl">
	<jdoc:include type="modules" name="user3<?php echo $params->get('_user3Chrome'); ?>" />
</div>
<?php endif;

if ($params->get('_modUser4')) : ?>
<div id="modUser4" class="fr">
	<jdoc:include type="modules" name="user4<?php echo $params->get('_user4Chrome'); ?>" />
</div>
<?php endif;

if ($params->get('_modBreadcrumb')) : ?>
<div id="modBreadcrumb" class="clr">
	<jdoc:include type="modules" name="breadcrumb<?php echo $params->get('_breadcrumbChrome'); ?>" />
</div>
<?php endif; ?>
