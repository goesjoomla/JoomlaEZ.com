<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Wrappers :: header & body
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

if ($params->get('_standardPage') && ($params->get('_modLogo') || $params->get('logo') != '' || (($params->get('topNav') && ($params->get('_colsCount') || $params->get('_navCount'))) || $params->get('_modTop'))))
	jezWrapper($params->get('wrapperHeader'), 'header', $params, 'jezHeader', '', true);

if ($params->get('_colsCount') || $params->get('_navCount'))
	jezWrapper($params->get('wrapperContent'), 'content', $params, 'jezContent', '', true, $params->get('_colsCount'));

if (!defined('RAW_OUTPUT') && $params->get('_extrasCount'))
	jezWrapper($params->get('wrapperExtras'), 'extras', $params, 'jezExtras', '', true, $params->get('_extrasCount'));

if ($params->get('_standardPage') && ($params->get('_modInset') || $params->get('bottomNav') || $params->get('_modSyndicate')))
	jezWrapper($params->get('wrapperBottom'), 'bottom', $params, 'jezBottom', '', true);
?>
