<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Wrappers :: body
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

if ($params->get('_message') || $params->get('_usersCount') || $params->get('_component') || $params->get('_modBottom'))
	jezWrapper($params->get('wrapperMain'), 'main', $params, 'jezMain', 'fl '.$params->get('_centerStyle'));

if (!defined('RAW_OUTPUT') && $params->get('_modRight'))
	jezWrapper($params->get('wrapperSub'), 'sub', $params, 'jezSub', 'fr '.$params->get('_rightStyle'));

if (!defined('RAW_OUTPUT') && $params->get('_modLeft'))
	jezWrapper($params->get('wrapperLocal'), 'local', $params, 'jezLocal', 'fl '.$params->get('_leftStyle'));

if ($params->get('_standardPage') && $params->get('_navCount'))
	jezWrapper($params->get('wrapperNav'), 'nav', $params, 'jezNav', '', true);
?>
