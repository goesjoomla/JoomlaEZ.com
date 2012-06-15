<?php
/*
 * JEZ Module Includer Joomla! 1.5 plugin
 *
 * @package		JEZ Module Includer
 * @version		1.0.0
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008 JoomlaEZ.com. All rights reserved
 * @license		Creative Commons Attribution-Noncommercial-Share Alike 3.0 Unported
 *
 * Please visit http://joomlaez.com/ for more information
 */

// no direct access
defined('_JEXEC') or die();

// require the helper class
require_once( dirname( __FILE__ ).DS.'jezLoadModule'.DS.'helper.php' );

class plgSystemJezLoadModule extends JPlugin {
	// variable to hold the plugin params
	var $params = null;

	// construct plugin
	function plgSystemJezLoadModule(&$subject, $config) {
		parent::__construct($subject, $config);

		// get plugin params if enabled
		$this->params = plgSystemJezLoadModuleHelper::isEnabled();
		$this->params->def('show_unpublished', 0);
		$this->params->def('style', 'raw');
		$this->params->def('suffix', '');
	}

	// function to execute at system event onAfterRender
	function onAfterRender() {
		global $mainframe;
		if (!$mainframe->isAdmin()) {
			// get the document body
			$body = JResponse::getBody();
			$sign = md5($body);

			// is JEZ Module Includer plugin and inclusion syntax enabled?
		 	if ($this->params && $this->params->def('inclusion_syntax', 0)) {
				// parse inclusion syntax
				$body = plgSystemJezLoadModuleHelper::parseInclusionSyntax($body, $this->params);
			} else {
				// clear inclusion syntax
				$body = plgSystemJezLoadModuleHelper::clearInclusionSyntax($body);
			}

			// set the new document body
			if ( md5($body) != $sign )
				JResponse::setBody($body);
		}
	}

	// function to execute at custom event onLoadModule
	function onLoadModule($modID, $modChrome = '', $modSuffix = '') {
		// is JEZ Module Includer plugin enabled and configured?
		if ($this->params) {
			// prepare variables
			global $mainframe;
			$db		= &JFactory::getDBO();
			$user	= &JFactory::getUser();
			$aid	= (int) $user->get('aid', 0);

			// prepare custom parameters
			$options = array();
			if ($modChrome != '')
				$options[1] = $modChrome;
			if ($modSuffix != '')
				$options[2] = $modSuffix;

			// query for specified module then render it
			$db->setQuery('SELECT * FROM #__modules'
			. ' WHERE id = ' . (int) $modID . ($this->params->get('show_unpublished') ? '' : ' AND published = 1')
			. ' AND access <= ' . $aid
			. ' AND client_id = ' . (int) $mainframe->getClientId(), 0, 1);
			if ($mod = $db->loadObject())
				echo plgSystemJezLoadModuleHelper::renderModule($mod, $options, $this->params);
		}
	}
}
?>