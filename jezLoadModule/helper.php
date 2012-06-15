<?php
/*
 * JEZ Module Includer Joomla! 1.5 plugin :: plugin helper
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

jimport('joomla.application.module.helper');

class plgSystemJezLoadModuleHelper {
	// function to check if plugin is enabled and configured
	function isEnabled() {
		static $enabled, $params;

		if (!isset($enabled))
			$enabled = JPluginHelper::isEnabled('system', 'jezLoadModule');

		if ($enabled) {
			// check for necessary params
			if (!isset($params)) {
				$plugin	= &JPluginHelper::getPlugin('system', 'jezLoadModule');
				$params	= new JParameter($plugin->params);
			}

			return $params;
		}

		return false;
	}

	// function to parse loadmodule inclusion syntax
	function parseInclusionSyntax($text, $params) {
		// inclusion syntax matching pattern
		$regex = '/{\s*loadmodule\s*.*?\s*}/i';

	 	// find all instances of inclusion syntax and put in $matches
		preg_match_all($regex, $text, $matches);
		$count = count($matches[0]);

		if ($count) {
			// prepare variables
			global $mainframe;
			$db		= &JFactory::getDBO();
			$user	= &JFactory::getUser();
			$aid	= (int) $user->get('aid', 0);

		 	for ($i = 0; $i < $count; $i++) {
		 		// parse inclusion syntax for parameters
		 		$load = str_replace( 'loadmodule', '', $matches[0][$i] );
		 		$load = str_replace( '{', '', $load );
		 		$load = str_replace( '}', '', $load );
		 		$load = preg_split( '/\s+/', trim($load), 3 );

				// query for specified module then render it
				$db->setQuery('SELECT * FROM #__modules'
				. ' WHERE id = ' . (int) $load[0] . ($params->get('show_unpublished') ? '' : ' AND published = 1')
				. ' AND access <= ' . $aid
				. ' AND client_id = ' . (int) $mainframe->getClientId(), 0, 1);
				if ($mod = $db->loadObject())
					$text = str_replace($matches[0][$i], plgSystemJezLoadModuleHelper::renderModule($mod, $load, $params), $text);
		 	}

		 	// clear any remaining inclusion syntax without matching module id
			$text = plgSystemJezLoadModuleHelper::clearInclusionSyntax($text);
		}

		return $text;
	}

	// function to clear captcha inclusion syntax
	function clearInclusionSyntax($text) {
		// inclusion syntax matching pattern
		$regex = '/{\s*loadmodule\s*.*?\s*}/i';

		if (preg_match($regex, $text))
			$text = preg_replace($regex, '', $text);

		return $text;
	}

	// function to render specified module
	function renderModule($module, $cParams, $dParams) {
		// is this a custom HTML module?
		if ( !isset($module->user) )
			$module->user = $module->module == 'mod_custom' ? 1 : 0;

		// render raw module output
		$content	= ($module->showtitle ? '<h3>'.$module->title.'</h3>' : '').JModuleHelper::renderModule($module);
		$suffix		= isset($cParams[2]) ? ' '.$cParams[2] : ($dParams->get('suffix') != '' ? ' '.$dParams->get('suffix') : '');

		// create module wrapper
		$chrome = isset($cParams[1]) ? $cParams[1] : $dParams->get('style');
		switch ($chrome) {
			case 'xhtml':
				$content = '<div class="moduletable'.$suffix.'">'.$content.'</div>';
				break;
			case 'rounded':
				$content = '<div class="module'.$suffix.'"><div><div><div>'.$content.'</div></div></div></div>';
				break;
			case 'jezRounded6ImgsNested':
				$content = '<div class="jezRounded6ImgsNested'.$suffix.'"><div><div><div><div><div>'.$content.'</div></div></div></div></div></div>';
				break;
			case 'jezRounded6Imgs':
				$content = '<div class="jezRounded6Imgs'.$suffix.'"><div class="hd"><div class="c"></div></div><div class="bd"><div class="c">'.$content.'</div></div><div class="ft"><div class="c"></div></div></div>';
				break;
			case 'jezRounded1Img':
			case 'jezRounded1ImgScroll':
				$cOpen = $chrome == 'jezRounded1ImgScroll' ? '"><div class="wrapper' : '';
				$cClose = $chrome == 'jezRounded1ImgScroll' ? '></div' : '';
				$content = '<div class="jezRounded1Img'.$suffix.'"><div class="inner'.$cOpen.'"><div class="t"></div>'.$content.'</div'.$cClose.'><div class="b"><div></div></div></div>';
				break;
			case 'raw':
			default:
				break;
		}

		// please keep the back link, thank you
		$content .= '
			<noscript>
			<p><a href="http://joomlaez.com/extensions/slideshow-joomla-module-to-create-web-20-joomla-slideshow.html" title="JoomlaEZ.com\'s Slideshow Joomla Module">JoomlaEZ.com\'s Slideshow Joomla Module</a> - Brings modern web 2.0 slideshow layouts to Joomla based site.
			<br/><a href="http://joomlaez.com/templates/joomla-theme-base-to-create-joomla-template-joomla-themes.html" title="JoomlaEZ.com\'s Joomla Theme Base">JoomlaEZ.com\'s Joomla Theme Base</a> - Complete base helps creating professional Joomla template in minutes.</p>
			</noscript>
		';

		return $content;
	}
}
?>