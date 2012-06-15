<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Template Customizer
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

/*----------------------------------------------------------------------------*/

/*******
* Init *
*******/

// get page class suffix
$params = &$mainframe->getParams();
if ( is_object($params) )
	$this->params->set( 'pageclass_sfx', $params->get('pageclass_sfx') );

// require JEZ Thema helpers
require_once(TEMPLATE_PATH.DS.'helper.php');
require_once(TEMPLATE_PATH.DS.'wrappers'.DS.'wrapper.php');

// define parameters
$xmlDoc =& JFactory::getXMLparser();
$xmlDoc->resolveErrors(true);

$success = $xmlDoc->loadXML_utf8(TEMPLATE_PATH.DS.'templateDetails.xml');
if ($success && is_object($xmlDoc->documentElement)) {
	// get parameters from XML document
	$xmlItems = $xmlDoc->getElementsByTagName('param');
	unset($xmlDoc);

	// parse and define parameters if neccessary
	for ($i = 0, $n = $xmlItems->getLength(); $i < $n; $i++) {
		$item =& $xmlItems->item($i);

		// get parameter
		$pname = $item->getAttribute('name');
		if (substr($pname, 0, 1) != '@')
			$this->params->def($pname, $item->getAttribute('default'));
	}
}

// load custom theme?
$theme = $this->params->get('theme');

if ( $this->params->get('switchThemeViaPageClassSuffix') && preg_match('/tpl_theme\@.+/i', $this->params->get('pageclass_sfx')) )
	$theme = strtolower(preg_replace('/tpl_theme\@/i', '', $this->params->get('pageclass_sfx')));

if ( $this->params->get('switchThemeViaUrl') && jezThemeBaseHelper::jezGetDevParam('tpl_theme', 1, 0) != null )
	$theme = strtolower(jezThemeBaseHelper::jezGetDevParam('tpl_theme', 1, 0));

if ($theme != '') {
	// load selected theme parameters
	if ( @is_readable(TEMPLATE_PATH.DS.'themes'.DS.$theme.DS.'params.ini') ) {
		unset($this->params);
		$this->params = new JParameter( file_get_contents(TEMPLATE_PATH.DS.'themes'.DS.$theme.DS.'params.ini') );
	}
}

/*----------------------------------------------------------------------------*/

/*********************************
* Analyze options then customize *
*********************************/

// if in dev mode, check to see if any template parameter is passed in or exists in cookie
if ( $this->params->get('dev') ) {
	// do not override custom selected theme parameters if it is loaded the first time in this browsing session
	if ( isset($_COOKIE['jezThemeBaseThemeLoaded']) )
		$themeLoaded = unserialize( rawurldecode($_COOKIE['jezThemeBaseThemeLoaded']) );

	if ( !isset($themeLoaded) || empty($themeLoaded) )
		$themeLoaded = array();

	if ( $theme != '' && !in_array($theme, $themeLoaded) ) {
		// set cookie to indicate that theme is loaded
		$themeLoaded[] = $theme;
		setcookie( 'jezThemeBaseThemeLoaded', rawurlencode( serialize($themeLoaded) ) );
	} else {
		// get the raw data of defined parameters
		$rawParams = get_object_vars($this->params->_registry['_default']['data']);

		// check request / cookie data for custom template parameters
		$opts = array_keys($rawParams);
		foreach ($opts AS $opt) {
			// theme parameter is already processed, skip it
			if ($opt != 'theme') {
				$value = jezThemeBaseHelper::jezGetDevParam( 'tpl_'.$opt, $this->params->get('dev_rememberParams'), (int) $this->params->get('dev_cookieLifetime') );
				if ($value != null)
					$this->params->set($opt, $value);
			}
		}
	}
}

// get the raw data of defined parameters
$rawParams = get_object_vars($this->params->_registry['_default']['data']);

// check for positions status and wrappers format
foreach ($rawParams AS $opt => $value) {
	if (preg_match("/^mod/", $opt) && !preg_match("/Chrome$/", $opt)) {
		$mod = strtolower(preg_replace("/^mod/", '', $opt));

		// is there any active module in this module position?
		$this->params->set('_'.$opt, $this->params->get($opt) ? ($this->countModules($mod) ? 1 : 0) : 0);

		if ($this->params->get('_'.$opt)) {
			// define the module wrapper style
			$this->params->set('_'.$mod.'Chrome', $this->params->get($opt.'Chrome') != 'raw' ? '" style="'.$this->params->get($opt.'Chrome') : '');

			// add custom attributes
			if ($this->params->get($opt.'Chrome') == 'jezRounded6ImgsNested')
				$this->params->set('_'.$mod.'Chrome', '" style="jezRounded6Imgs" nested="1');
			elseif ($this->params->get($opt.'Chrome') == 'jezRounded1ImgScroll')
				$this->params->set('_'.$mod.'Chrome', '" style="jezRounded1Img" scrollable="1');
		}
	}
}

// load theme specific customization
if ( $theme && @file_exists(TEMPLATE_PATH.DS.'themes'.DS.$theme.DS.'custom.php') )
	require_once(TEMPLATE_PATH.DS.'themes'.DS.$theme.DS.'custom.php');

// final preparation
if (JRequest::getCmd('layout') == 'form' || JRequest::getCmd('task') == 'edit') {
	$this->params->set('_modRight', 0);
	$jsQuery[] = 'hasEditor=1';
}

// detect current page type
$this->params->set('_standardPage', 0);
if (!defined('RAW_OUTPUT') || (defined('RAW_OUTPUT') && $this->params->get('rawShowHeader')))
	$this->params->set('_standardPage', 1);

// get main position status
$this->params->set('_message', $this->getBuffer('message'));
$this->params->set('_component', !$this->params->get('homeShowComponent') && jezThemeBaseHelper::jezIsRootPage() ? 0 : 1);

if ($this->params->get('_standardPage')) {
	// count module in global nav
	$this->params->set('_navCount', $this->params->get('_modUser3') + $this->params->get('_modUser4') + $this->params->get('_modBreadcrumb'));

	// do we have menu in global nav?
	$hasNav = jezThemeBaseHelper::jezModulePresents('mod_mainmenu', 'user3') || jezThemeBaseHelper::jezModulePresents('mod_mainmenu', 'user4');

	if ($this->params->get('_navCount') && $hasNav) {
		// set parameters for global navigation
		$jsQuery[] = 'nav=1';
		$jsQuery[] = 'navFx='.$this->params->get('navFx');

		if ($this->params->get('navFx')) {
			// JoomlaEZ.com's Menu Effects script default options
			$navFxDefaultOpts['navFx_fxDuration'] = 500; // effect duration (milliseconds)
			$navFxDefaultOpts['navFx_firstSubDirection'] = 'both'; // 1st level sub-menu slideout effect direction (vertical | horizontal | both)
			$navFxDefaultOpts['navFx_deeperSubDirection'] = 'both'; // deeper level sub-menu slideout effect direction (vertical | horizontal | both)

			// create array of javascript object to contruct JoomlaEZ.com's Menu Effect script
			$navFxOpts = jezThemeBaseHelper::jezJSClassOpts($navFxDefaultOpts, $this->params);
			if (count($navFxOpts))
				$jsQuery[] = 'navFxOpts='.rawurlencode(implode(',', $navFxOpts));
		}
	}
}

if (!defined('RAW_OUTPUT')) {
	// get page class suffix
	$params = &$mainframe->getParams();
	if ( is_object($params) )
		$this->params->set( 'pageclass_sfx', $params->get('pageclass_sfx') );

	// set appropriated style for left, center and right columns of main position
	$this->params->set('_usersCount', $this->params->get('_modUser1') + $this->params->get('_modUser2'));
	$this->params->set('_colsCount', ($this->getBuffer('message') || $this->params->get('_usersCount') || $this->params->get('_component') || $this->params->get('_modBottom') ? 1 : 0) + $this->params->get('_modRight') + $this->params->get('_modLeft'));

	$this->params->set('_leftStyle', $this->params->get('_colsCount') == 1 ? '' : 'tc');
	$this->params->set('_centerStyle', $this->params->get('_colsCount') == 3 || ($this->params->get('_colsCount') == 2 && $this->params->get('_modRight')) ? 'tc' : '');

	if ($this->params->get('_colsCount') == 2 && !($this->getBuffer('message') || $this->params->get('_usersCount') || $this->params->get('_component') || $this->params->get('_modBottom'))) {
		switch ($this->params->get('noCenterExpand')) {
			case 'right':
				$this->params->set('_rightStyle', 'expandRight');
				break;
			case 'left':
				$this->params->set('_leftStyle', $this->params->get('_leftStyle').' expandLeft');
				break;
			case 'both':
			default:
				$this->params->set('_leftStyle', $this->params->get('_leftStyle').' expandBoth');
				$this->params->set('_rightStyle', 'expandBoth');
				break;
		}
	}

	// set appropriated style for user5, user6, user7 and user8 positions of extras block
	$this->params->set('_extrasCount', $this->params->get('_modUser5') + $this->params->get('_modUser6') + $this->params->get('_modUser7') + $this->params->get('_modUser8'));
	for ($i = 8; $i >= 5; $i--) {
		if ($this->params->get('_modUser'.$i)) {
			for ($j = 5; $j < $i; $j++)
				$this->params->set('_modUser'.$j.'Style', 'fl tc');

			$this->params->set('_modUser'.$i.'Style', 'fr');
			break;
		}
	}
} else {
	$this->params->set('_colsCount', $this->getBuffer('message') || $this->params->get('_component') ? 1 : 0);

	if (!$this->params->get('_standardPage'))
		$this->params->set('wrapperContent', 'plain');
}

// store base URL to params
$this->params->set('_baseurl', $this->baseurl);

/*----------------------------------------------------------------------------*/

/***********************
* Set behavior options *
***********************/

// IE6 PNG-24 fix options
if ($this->params->get('png24Fix'))
	$jsQuery[] = 'png24Fix=1&amp;png24Fix_for='.$this->params->get('png24Fix_for').'&amp;spacer='.rawurlencode($this->baseurl.'/templates/'.$this->template.'/images/spacer.gif');

// automatically keep typography`s vertical rhythm
if ($this->params->get('keepVR'))
	$jsQuery[] = 'keepVR=1';

// add support for hover and focus state in IE6 (form elements only) by automatically adding `hover` / `focus`
// class to input, select, textarea and button elements when they are hovered / focused
if ($this->params->get('ie6Hover'))
	$jsQuery[] = 'ie6Hover=1';

// modal window links creation
$modalLinks = false;

if ($this->params->get('modalPdf') || $this->params->get('modalPrint') || $this->params->get('modalEmail')) {
	// only modalize icons links if in com_content page
	$comOutput = $this->getBuffer('component');
	if (JRequest::getCmd('option') == 'com_content' && preg_match_all('#<a\s+[^>]*href="([^"^>]+)"[^>]*>#si', $comOutput, $matches, PREG_SET_ORDER) > 0) {
		$modalLinks = true;

		// prepare modal window dimension for each icon link types
		foreach (array('Pdf', 'Print', 'Email') AS $icon) {
			if ($this->params->get('modal'.$icon)) {
				list($w, $h) = preg_split("/\s*x\s*/i", str_replace('px', '', $this->params->get('modal'.$icon.'Size')), 2);
				$jsQuery[] = 'modalIcons[modal'.$icon.'][w]='.rawurlencode($w).'&amp;modalIcons[modal'.$icon.'][h]='.rawurlencode($h);
			}
		}

		// modalize icons links
		foreach ($matches AS $match) {
			$found = false;
			if ($this->params->get('modalPdf') && (preg_match('/format=pdf/i', $match[1]) || preg_match('/\.pdf$/i', $match[1])))
				$found = 'modalPdf';
			elseif ($this->params->get('modalPrint') && (preg_match('/print=1/i', $match[1]) || preg_match('/\/print\.html$/i', $match[1])))
				$found = 'modalPrint';
			elseif ($this->params->get('modalEmail') && (preg_match('/mailto/i', $match[1]) || preg_match('/\/email\.html$/i', $match[1])))
				$found = 'modalEmail';

			if ($found) {
				// add class attribute
				if (preg_match('/class=/', $match[0]))
					$found = preg_replace('/class="([^"^>]+)"/i', 'class="'.$found.' \\1"', $match[0]);
				else
					$found = str_replace('>', ' class="'.$found.'">', $match[0]);

				// remove rel attribute
				$found = preg_replace('/rel="([^"^>]+)"/i', '', $found);

				// remove onclick event
				$found = preg_replace('/onclick="[^"^>]+"/i', '', $found);

				$comOutput = str_replace($match[0], $found, $comOutput);
			}
		}

		// set new buffer
		$this->setBuffer($comOutput, 'component');
	}
}

// process custom modalization rules
for ($i = 0; $i < 10; $i++) {
	if ($this->params->get('modal'.$i) && $this->params->get('modal'.$i.'Attr') != '' && $this->params->get('modal'.$i.'Pattern') != '') {
		// has custom modalization set
		$modalLinks = true;
		$sels = explode(',', $this->params->get('modal'.$i.'Pattern'));

		if ($this->params->get('modal'.$i.'Match') != 'exact') {
			// regular expression matching
			foreach ($sels AS $sel)
				$jsQuery[] = 'modalRegex['.$i.']['.$this->params->get('modal'.$i.'Attr').'][]='.rawurlencode(trim($sel));
		} else {
			// match pattern exactly
			foreach ($sels AS $sel)
				$jsQuery[] = 'modalExact['.$i.']['.$this->params->get('modal'.$i.'Attr').'][]='.rawurlencode(trim($sel));
		}

		// prepare custom modalization window dimension
		list($w, $h) = preg_split("/\s*x\s*/i", str_replace('px', '', $this->params->get('modal'.$i.'Size')), 2);
		$jsQuery[] = 'modalSize['.$i.'][w]='.rawurlencode($w).'&amp;modalSize['.$i.'][h]='.rawurlencode($h);
	}
}

/*----------------------------------------------------------------------------*/

/************************************
* Load stylesheets and script files *
************************************/

// convert array of name/value pairs to query string
$jsQuery = count($jsQuery) ? implode('&amp;', $jsQuery) : '';

// load JoomlaEZ.com's CSS Framework and template specific stylesheets
jezThemeBaseHelper::loadStylesheets('jezFramework.css', 'templates/'.$this->template.'/css/');

if ($this->params->get('layout') == 'fixed')
	jezThemeBaseHelper::loadStylesheets('jezGridFixed.css', 'templates/'.$this->template.'/css/');
else
	jezThemeBaseHelper::loadStylesheets('jezGridElastic.css', 'templates/'.$this->template.'/css/');

if ($this->params->get('internalLinks') != '') {
	$inLinks = explode(',', $this->params->get('internalLinks'));
	$this->addStyleDeclaration('
a[href^="http://'.implode('"],a[href^="http://', $inLinks).'"] {
	padding: 0;
	background: none;
}
');
}

jezThemeBaseHelper::loadStylesheets('layouts.css', 'templates/'.$this->template.'/css/', true);
jezThemeBaseHelper::loadStylesheets('template.css', 'templates/'.$this->template.'/css/', true);

if ($this->params->get('ie6warning')) {
	$this->addCustomTag('
<!--[if lte IE 6]><link rel="stylesheet" href="'.$this->baseurl.'/templates/'.$this->template.'/css/ie6warning.css" type="text/css" /><![endif]-->
');
}

$this->addCustomTag('
<!--[if IE 7]><link rel="stylesheet" href="'.$this->baseurl.'/templates/'.$this->template.'/css/ie7compat.css" type="text/css" /><![endif]-->
');

// load JoomlaEZ.com's Base JavaScript library
jezThemeBaseHelper::loadScripts('jezBase.js', 'templates/'.$this->template.'/scripts/');

// load Mootools and other effects libraries?
if (($this->params->get('_standardPage') && $this->params->get('navFx')) || $modalLinks) {
	JHTML::_('behavior.mootools');

	if ($this->params->get('navFx')) {
		// load JoomlaEZ.com's Effects libraries
		jezThemeBaseHelper::loadScripts('jezBaseFx.js', 'templates/'.$this->template.'/scripts/');
		jezThemeBaseHelper::loadScripts('jezMenuFx.js', 'templates/'.$this->template.'/scripts/');
	}

	if ($modalLinks) {
		// load modal window script and stylesheet
		JHTML::_('script', 'modal.js');
		JHTML::_('stylesheet', 'modal.css');
	}
}

// load additional JavaScript libraries
if ($this->params->get('png24Fix'))
	jezThemeBaseHelper::loadScripts('jezPngFix.js', 'templates/'.$this->template.'/scripts/');

if ($this->params->get('reflection'))
	jezThemeBaseHelper::loadScripts('reflection.js', 'templates/'.$this->template.'/scripts/3rd-party/reflection/');

// pass customized parameters to initialization script
if ($jsQuery)
	$this->addScript($this->baseurl.'/templates/'.$this->template.'/scripts/initialize.js.php?'.$jsQuery);

// load theme?
if ( $theme ) {
	$basePath = TEMPLATE_PATH.DS.'themes'.DS.$theme;

	// only do extra work if theme directory exists
	if ( @is_dir($basePath) ) {
		jimport('joomla.filesystem.file');

		// load additional stylesheets
		if ( @is_dir($basePath.DS.'css') ) {
			// looking for additional stylesheets
			$files = JFolder::files($basePath.DS.'css');
			if ($files !== false) {
				foreach ($files as $file) {
					if ( substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html' && @is_readable($basePath.DS.'css'.DS.$file) ) {
						if ($file == 'ie6warning.css')
							$this->addCustomTag('
<!--[if lte IE 6]><link rel="stylesheet" href="'.$this->baseurl.'/templates/'.$this->template.'/themes/'.$theme.'/css/ie6warning.css" type="text/css" /><![endif]-->
');
						elseif ($file == 'ie7compat.css')
							$this->addCustomTag('
<!--[if IE 7]><link rel="stylesheet" href="'.$this->baseurl.'/templates/'.$this->template.'/themes/'.$theme.'/css/ie7compat.css" type="text/css" /><![endif]-->
');
						else
							jezThemeBaseHelper::loadStylesheets($file, 'templates/'.$this->template.'/themes/'.$theme.'/css/', true);
					}
				}
			}
		}

		// load additional scripts
		if ( @is_dir($basePath.DS.'scripts') ) {
			// looking for additional scripts
			$files = JFolder::files($basePath.DS.'scripts');
			if ($files !== false) {
				foreach ($files as $file) {
					if ( substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html' && @is_readable($basePath.DS.'scripts'.DS.$file) ) {
						if ($file == 'initialize.js.php')
							$this->addScript($this->baseurl.'/templates/'.$this->template.'/themes/'.$theme.'/scripts/initialize.js.php?'.$jsQuery);
						else
							jezThemeBaseHelper::loadScripts($file, 'templates/'.$this->template.'/themes/'.$theme.'/scripts/', true);
					}
				}
			}
		}
	}
}
?>