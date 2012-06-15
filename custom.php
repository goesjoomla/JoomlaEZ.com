<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Template Customizer
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

/*----------------------------------------------------------------------------*/

/*******
* Init *
*******/

// get page class suffix
$params = &$mainframe->getParams();
if ( is_object($params) )
	$this->params->set( 'pageclass_sfx', $params->get('pageclass_sfx') );

// require JEZ Rego helpers
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

	if ( $theme != '' && !in_array($theme, $themeLoaded) && @is_readable(TEMPLATE_PATH.DS.'themes'.DS.$theme.DS.'params.ini') ) {
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

	if ($this->params->get('keepVR'))
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
	// set logo alternative text if not defined
	if ($this->params->get('logoAlt') == '') {
		$document =& JFactory::getDocument();
		$this->params->set('logoAlt', $document->getTitle());
	}

	// count module in global nav
	$this->params->set('_navCount', $this->params->get('_modUser3') + $this->params->get('_modTop'));

	// do we have menu in global nav?
	$hasNav = jezThemeBaseHelper::jezModulePresents('mod_mainmenu', 'user3');

	if ($this->params->get('_navCount') && $hasNav) {
		// set parameters for global navigation
		if ($this->params->get('navFx')) {
			$jsQuery[] = 'navFx='.$this->params->get('navFx');

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
	// set appropriated style for user1, user4 and user2 blocks of top position
	$this->params->set('_blksCount', $this->params->get('_modUser4') + $this->params->get('_modUser1') + $this->params->get('_modUser2'));

	$this->params->set('_user1Style', $this->params->get('_blksCount') == 1 ? '' : 'tc');
	$this->params->set('_user4Style', $this->params->get('_blksCount') == 3 || ($this->params->get('_blksCount') == 2 && $this->params->get('_modUser2')) ? 'tc' : '');

	if ($this->params->get('_blksCount') == 2 && !$this->params->get('_modUser4')) {
		switch ($this->params->get('topNoCenterExpand')) {
			case 'right':
				$this->params->set('_user2Style', 'expandRight');
				break;
			case 'left':
				$this->params->set('_user1Style', $this->params->get('_user1Style').' expandLeft');
				break;
			case 'both':
			default:
				$this->params->set('_user1Style', $this->params->get('_user1Style').' expandBoth');
				$this->params->set('_user2Style', 'expandBoth');
				break;
		}
	}

	// set appropriated style for left, center and right columns of main position
	$this->params->set('_colsCount', ($this->getBuffer('message') || $this->params->get('_modBreadcrumb') || $this->params->get('_component') ? 1 : 0) + $this->params->get('_modLeft') + $this->params->get('_modRight'));

	$this->params->set('_leftStyle', $this->params->get('_colsCount') == 1 ? '' : 'tc');
	$this->params->set('_centerStyle', $this->params->get('_colsCount') == 3 || ($this->params->get('_colsCount') == 2 && $this->params->get('_modRight')) ? 'tc' : '');

	if ($this->params->get('_colsCount') == 2 && !($this->getBuffer('message') || $this->params->get('_modBreadcrumb') || $this->params->get('_component'))) {
		switch ($this->params->get('contentNoCenterExpand')) {
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
	$this->params->set('_extsCount', $this->params->get('_modUser5') + $this->params->get('_modUser6') + $this->params->get('_modUser7') + $this->params->get('_modUser8'));
	for ($i = 8; $i >= 5; $i--) {
		if ($this->params->get('_modUser'.$i)) {
			$first = true;
			for ($j = 5; $j < $i; $j++) {
				if ($this->params->get('_modUser'.$j)) {
					if (isset($first) && $first) {
						$this->params->set('_modUser'.$j.'Style', 'fl first');
						unset($first);
					} else
						$this->params->set('_modUser'.$j.'Style', 'fl');
				}
			}

			$this->params->set('_modUser'.$i.'Style', 'fr');
			break;
		}
	}
} else
	$this->params->set('_colsCount', $this->getBuffer('message') || $this->params->get('_component') ? 1 : 0);

// store base URL to params
$this->params->set('_baseurl', $this->baseurl);

/*----------------------------------------------------------------------------*/

/*****************************
* Set general layout options *
*****************************/

// font set
if ($this->params->get('fontSet') != 'mixed')
	$cssQuery[] = 'fontSet='.$this->params->get('fontSet');

// layout unit
if (
	!(
		$this->params->get('layoutUnit') == 'elastic'
		&&
		$this->params->get('tplWidth') == '80'
		&&
		$this->params->get('leftWidth') == '16.417'
		&&
		$this->params->get('rightWidth') == '16.417'
	)
	||
	$this->params->get('hdHeight') != '75px'
	||
	$this->params->get('topHeight') != '194px'
	||
	$this->params->get('logoWidth') != '111px'
	||
	$this->params->get('logoHeight') != '46px'
)
	$cssQuery[] = 'layoutUnit='.$this->params->get('layoutUnit');

// template, left and right column width
if (
	!(
		$this->params->get('layoutUnit') == 'elastic'
		&&
		$this->params->get('tplWidth') == '80'
		&&
		$this->params->get('leftWidth') == '16.417'
		&&
		$this->params->get('rightWidth') == '16.417'
	)
) {
	$cssQuery[] = 'tplWidth='.$this->params->get('tplWidth');
	$cssQuery[] = 'leftWidth='.$this->params->get('leftWidth');
	$cssQuery[] = 'rightWidth='.$this->params->get('rightWidth');
}

// header height
if ($this->params->get('hdHeight') != '75px')
	$cssQuery[] = 'hdHeight='.$this->params->get('hdHeight');

// top block height
if ($this->params->get('topHeight') != '194px')
	$cssQuery[] = 'topHeight='.$this->params->get('topHeight');

// logo dimension
if ($this->params->get('logoWidth') != '111px')
	$cssQuery[] = 'logoWidth='.$this->params->get('logoWidth');

if ($this->params->get('logoHeight') != '46px')
	$cssQuery[] = 'logoHeight='.$this->params->get('logoHeight');

// process internal (sub-)domains
if ($this->params->get('internalLinks') != '')
	$cssQuery[] = 'internalLinks='.rawurlencode($this->params->get('internalLinks'));

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

// modal window links creation
$modalLinks = false;

// modalize icons links if in com_content page
if (
	JRequest::getCmd('option') == 'com_content'
	&&
	($this->params->get('modalPdf') || $this->params->get('modalPrint') || $this->params->get('modalEmail'))
) {
	$comOutput = $this->getBuffer('component');

	if (preg_match_all('/<a\s+[^>]*href="([^"^>]+)"[^>]*>/si', $comOutput, $matches, PREG_SET_ORDER) > 0) {
		foreach ($matches AS $match) {
			$found = false;

			if ($this->params->get('modalPdf') && (preg_match('/format=pdf/i', $match[1]) || preg_match('/\.pdf$/i', $match[1])))
				$found = 'modalPdf';
			elseif ($this->params->get('modalPrint') && (preg_match('/print=1/i', $match[1]) || preg_match('/\/print\.html$/i', $match[1])))
				$found = 'modalPrint';
			elseif ($this->params->get('modalEmail') && (preg_match('/mailto/i', $match[1]) || preg_match('/\/email\.html$/i', $match[1])))
				$found = 'modalEmail';

			if ($found) {
				$modalLinks = true;

				// prepare modal window dimension for icon link determined
				if (!isset(${$found})) {
					${$found} = true;
					$jsQuery[] = $found.'='.rawurlencode(str_replace('px', '', $this->params->get($found.'Size')));
				}

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
	}

	// set new buffer
	if ($modalLinks)
		$this->setBuffer($comOutput, 'component');
}

// process custom modalization rules
for ($i = 0; $i < 10; $i++) {
	if ($this->params->get('modal'.$i) && $this->params->get('modal'.$i.'Attr') != '' && $this->params->get('modal'.$i.'Pattern') != '') {
		// we have custom modalization rule set
		$modalLinks = true;

		if ($this->params->get('modal'.$i.'Match') != 'exact') {
			// regular expression matching
			$jsQuery[] = 'modalRegex['.$i.']['.$this->params->get('modal'.$i.'Attr').']='.rawurlencode(trim($this->params->get('modal'.$i.'Pattern')));
		} else {
			// match pattern exactly
			$jsQuery[] = 'modalExact['.$i.']['.$this->params->get('modal'.$i.'Attr').']='.rawurlencode(trim($this->params->get('modal'.$i.'Pattern')));
		}

		// prepare custom modalization window dimension
		if (!isset(${'modal'.$i})) {
			${'modal'.$i} = true;
			$jsQuery[] = 'modalSize['.$i.']='.rawurlencode(str_replace('px', '', $this->params->get('modal'.$i.'Size')));
		}
	}
}

/*----------------------------------------------------------------------------*/

/************************************
* Load stylesheets and script files *
************************************/

// convert array of name/value pairs to query string
$jsQuery = @count($jsQuery) ? implode('&amp;', $jsQuery) : '';
$cssQuery = @count($cssQuery) ? implode('&amp;', $cssQuery) : '';

// load JoomlaEZ.com's CSS Framework and template specific stylesheets
jezThemeBaseHelper::loadStylesheets('jezFramework.css', 'templates/'.$this->template.'/css/');
jezThemeBaseHelper::loadStylesheets('layouts.css', 'templates/'.$this->template.'/css/', true);
jezThemeBaseHelper::loadStylesheets('template.css', 'templates/'.$this->template.'/css/', true);

// pass query string to CSS customization script
if ($cssQuery)
	$this->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/css/customize.css.php?'.$cssQuery);

$this->addCustomTag('
<!--[if lte IE 7]><link rel="stylesheet" href="'.$this->baseurl.'/templates/'.$this->template.'/css/ie7compat.css" type="text/css" /><![endif]-->
');

if ($this->params->get('ie6Warning'))
	$this->addCustomTag('
<!--[if lte IE 6]><link rel="stylesheet" href="'.$this->baseurl.'/templates/'.$this->template.'/css/ie6warning.css" type="text/css" /><![endif]-->
<!--[if lte IE 6]><style type="text/css">body { padding-top: 2em; }</style><![endif]-->
');

// add support for hover and focus state in IE6 using CSS Hover v3 behavior file
// visit http://www.xs4all.nl/~peterned/csshover.html for details
if ($this->params->get('ie6Hover'))
	$this->addCustomTag('
<!--[if lte IE 6]><style type="text/css">body { behavior: url("'.$this->baseurl.'/templates/'.$this->template.'/css/csshover3.htc"); }</style><![endif]-->
');

// load JoomlaEZ.com's Base JavaScript library
if (
	$this->params->get('png24Fix')
	||
	$this->params->get('keepVR')
	||
	(!$this->params->get('_modTop') && $this->params->get('fontResizer'))
)
	jezThemeBaseHelper::loadScripts('jezBase.js', 'templates/'.$this->template.'/scripts/');

// load Mootools and other effects libraries?
if (
	($this->params->get('_standardPage') && $this->params->get('_navCount') && $hasNav && $this->params->get('navFx'))
	||
	$modalLinks
) {
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

// load template specific JavaScript libraries
if ( !$this->params->get('_modTop') && $this->params->get('fontResizer') )
	jezThemeBaseHelper::loadScripts('jezFontResizer.js', 'templates/'.$this->template.'/scripts/');

if ($this->params->get('png24Fix'))
	jezThemeBaseHelper::loadScripts('jezPngFix.js', 'templates/'.$this->template.'/scripts/');

if ($this->params->get('reflection'))
	jezThemeBaseHelper::loadScripts('reflection.js', 'templates/'.$this->template.'/scripts/3rd-party/reflection/');

// pass query string to JavaScript initialization script
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
						if ($file == 'ie7compat.css')
							$this->addCustomTag('
<!--[if lte IE 7]><link rel="stylesheet" href="'.$this->baseurl.'/templates/'.$this->template.'/themes/'.$theme.'/css/ie7compat.css" type="text/css" /><![endif]-->
');
						elseif ($file == 'ie6warning.css')
							$this->addCustomTag('
<!--[if lte IE 6]><link rel="stylesheet" href="'.$this->baseurl.'/templates/'.$this->template.'/themes/'.$theme.'/css/ie6warning.css" type="text/css" /><![endif]-->
');
						elseif ($file != 'customize.css.php')
							jezThemeBaseHelper::loadStylesheets($file, 'templates/'.$this->template.'/themes/'.$theme.'/css/', true);
					}
				}

				if (@file_exists($basePath.DS.'css'.DS.'customize.css.php') && @is_readable($basePath.DS.'css'.DS.'customize.css.php') && $cssQuery)
					$this->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/themes/'.$theme.'/css/customize.css.php?'.$cssQuery);
			}
		}

		// load additional scripts
		if ( @is_dir($basePath.DS.'scripts') ) {
			// looking for additional scripts
			$files = JFolder::files($basePath.DS.'scripts');
			if ($files !== false) {
				foreach ($files as $file) {
					if ( substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html' && @is_readable($basePath.DS.'scripts'.DS.$file) ) {
						if ($file != 'initialize.js.php')
							jezThemeBaseHelper::loadScripts($file, 'templates/'.$this->template.'/themes/'.$theme.'/scripts/', true);
					}
				}

				if (@file_exists($basePath.DS.'scripts'.DS.'initialize.js.php') && @is_readable($basePath.DS.'scripts'.DS.'initialize.js.php') && $jsQuery)
					$this->addScript($this->baseurl.'/templates/'.$this->template.'/themes/'.$theme.'/scripts/initialize.js.php?'.$jsQuery);
			}
		}
	}
}
?>