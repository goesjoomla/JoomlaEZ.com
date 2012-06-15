<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Template Helper
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

class jezThemeBaseHelper {
	// function to create parameters to initialize JavaScript object
	function jezJSClassOpts($defaultOpts, $params, $delimitor = '_') {
		$jsClassOpts = array();
		foreach ($defaultOpts AS $k => $v) {
			if ($params->get($k) != '' && $params->get($k) != $v) {
				$tmp = substr($k, strpos($k, $delimitor) + 1, strlen($k)).':';
				if (!is_numeric($params->get($k)) && !in_array($params->get($k), array('yes', 'no')))
					$tmp .= '"'.$params->get($k).'"';
				else {
					switch ($params->get($k)) {
						case 'yes':
							$tmp .= 'true';
							break;
						case 'no':
							$tmp .= 'false';
							break;
						default:
							$tmp .= $params->get($k);
							break;
					}
				}
				$jsClassOpts[] = $tmp;
			}
		}
		return $jsClassOpts;
	}

	// function to check if any instance of a module type presents in certain page
	function jezModulePresents($modType, $position = '', $page = '') {
		global $Itemid;
		if ($position) {
			$position = is_array($position) ? $position : preg_split("/[,\n\t\s]+/", $position); // must be array
			$position = " AND m2.position IN ('".implode("', '", $position)."')";
		}
		$page = $page ? $page : $Itemid; // if not set to certain page, check for current page

		$db =& JFactory::getDBO();
		$usr =& JFactory::getUser();

		$db->setQuery('SELECT COUNT(*) FROM `#__modules_menu` AS m1 INNER JOIN `#__modules` AS m2'
		. ' WHERE m2.module = '.$db->Quote($modType).' AND m2.published = 1'
		. $position
		. ' AND m2.access <= '.(int) $usr->get('aid', 0).' AND m1.moduleid = m2.id'
		. ' AND (m1.menuid = 0 OR m1.menuid = '.(int) $page.')');

		return $db->loadResult();
	}

	// function to check if current page is root page (front page)
	function jezIsRootPage() {
		global $Itemid;
		$db =& JFactory::getDBO();

		$db->setQuery('SELECT id FROM #__menu WHERE `home` = 1 AND `published` = 1', 0, 1);
		$root = $db->loadResult();

		return $Itemid == $root;
	}

	// function to get and save custom parameter in dev mode
	function jezGetDevParam($param, $remember = 0, $days = 0) {
		static $baseurl;

		if ( !isset($baseurl) ) {
			preg_match_all('#(http|https)://([^/]+)(.*)#i', JURI::base(), $matches, PREG_SET_ORDER);
			$baseurl[0] = $matches[0][1];
			$baseurl[1] = $matches[0][2];
			$baseurl[2] = $matches[0][3];
			unset($matches);
		}

		// get custom parameter
		$request = JRequest::getCmd($param, null, 'GET');
		$request = $request != null ? $request : JRequest::getCmd($param, null, 'POST');
		$value = $request != null ? $request : (isset($_COOKIE[$param]) ? $_COOKIE[$param] : null);

		if ($value != null) {
			// save only custom parameter which is not already saved
			if ($remember && (!isset($_COOKIE[$param]) || $value != $_COOKIE[$param])) {
				if ($days != 0)
					setcookie($param, $value, time() + ($days * 24 * 60 * 60), $baseurl[2], $baseurl[1], strtolower($baseurl[0]) == 'https' ? 1 : 0);
				else
					setcookie($param, $value);
			}
			return $value;
		}

		return null;
	}

	// function to load required or specified scripts
	function loadScripts($scripts, $path, $matchExactPath = false) {
		// get document head and body
		$document = &JFactory::getDocument();
		$head = $document->getHeadData();
		$body = JResponse::getBody();
		$base = JURI::base(true);

		// if any script is already loaded, ignore it
		foreach ( (array) $scripts AS $script ) {
			$loaded = false;
			foreach ($head['scripts'] AS $k => $v) {
				if ( preg_match('#'.str_replace('.', '\\.', ($matchExactPath ? $path : '').$script).'$#', $k) ) {
					$loaded = true;
					break;
				}
			}
			if (!$loaded) {
				if ( empty($body) ) {
					if ($script == 'mootools.js')
						JHTML::_('behavior.mootools');
					else
						JHTML::_('script', $script, $path);
				} else {
					if ($script == 'mootools')
						echo '<script type="text/javascript" src="'.$base.'/media/system/js/mootools.js"></script>';
					else
						echo '<script type="text/javascript" src="'.$base.'/'.$path.$script.'"></script>';
				}
			}
		}
	}

	// function to load required or specified stylesheets
	function loadStylesheets($stylesheets, $path, $matchExactPath = false) {
		// get document head and body
		$document = &JFactory::getDocument();
		$head = $document->getHeadData();
		$body = JResponse::getBody();
		$base = JURI::base(true);

		// if any stylesheet is already loaded, ignore it
		foreach ( (array) $stylesheets AS $stylesheet ) {
			$loaded = false;
			foreach ($head['styleSheets'] AS $k => $v) {
				if ( preg_match('#'.str_replace('.', '\\.', ($matchExactPath ? $path : '').$stylesheet).'$#', $k) ) {
					$loaded = true;
					break;
				}
			}
			if (!$loaded) {
				if ( empty($body) ) {
					JHTML::_('stylesheet', $stylesheet, $path);

					if ($stylesheet == 'jezFramework.css') {
						// load additional files for JoomlaEZ.com's CSS Framework
						$document->addCustomTag('
<!--[if lte IE 6]><link rel="stylesheet" href="'.$base.'/'.$path.'jezIE6only.css" type="text/css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" href="'.$base.'/'.$path.'jezIE7only.css" type="text/css" /><![endif]-->
');
					}
				} else {
					echo '<link rel="stylesheet" href="'.$base.'/'.$path.$stylesheet.'" type="text/css" />';

					if ($stylesheet == 'jezFramework.css') {
						// load additional files for JoomlaEZ.com's CSS Framework
						echo '
<!--[if lte IE 6]><link rel="stylesheet" href="'.$base.'/'.$path.'jezIE6only.css" type="text/css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" href="'.$base.'/'.$path.'jezIE7only.css" type="text/css" /><![endif]-->
';
					}
				}
			}
		}
	}
}
?>