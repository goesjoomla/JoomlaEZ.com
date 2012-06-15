<?php
/*
 * JoomlaEZ.com's XML Elements Styler :: intro element
 *
 * @package		JEZ XML Elements Styler
 * @version		1.0.0
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008 JoomlaEZ.com. All rights reserved
 * @license		Creative Commons Attribution-Noncommercial-Share Alike 3.0 Unported
 *
 * Please visit http://joomlaez.com/ for more information
 */

/*----------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.user.helper');

// intro element parser class
class JElementIntro extends JElement {
	var	$_name = 'Intro';

	// disable tool-tip?
	function fetchTooltip($label, $description, &$node, $control_name, $name) {
		$cols = $node->attributes('cols');

		if ($cols == 2)
			return '&nbsp;';

		return;
	}

	// parse element attributes
	function fetchElement($name, $value, &$node, $control_name) {
		$title			= $node->attributes('label');
		$description	= $node->attributes('description');
		$image			= $node->attributes('image');
		$imageAlign		= $node->attributes('imageAlign');
		$url			= $node->attributes('url');
		$open			= $node->attributes('open');
		$id				= JUtility::getHash(JUserHelper::genRandomPassword());

		if ($url)
			$url = '<a href="'.$url.'" target="_blank">';

		if ($description)
			$description = html_entity_decode(JText::_($description));

		if ($imageAlign == 'left')
			$imageAlign = ' style="float:left;background-color:#CB9630;margin-right:.75em;margin-bottom:.5em"';
		else
			$imageAlign = ' style="float:right;background-color:#CB9630;margin-left:.75em;margin-bottom:.5em"';

		if ($image)
			$image = $url.'<img src="'.$image.'" border="0"'.$imageAlign.' />'.($url != '' ? '</a>' : '');

		if ($title) {
			$title = '<h3 style="margin-top:0">'.$url.html_entity_decode(JText::_($title)).($url != '' ? '</a>' : '');

			if (!empty($image) || !empty($description)) {
				$title .= ' <small><small>(<a href="javascript:void(0)" onclick="var thisIntro = document.getElementById(\''.$id.'\');'
				.' var thisSlider = thisIntro; while (!thisSlider.className || !thisSlider.className.match(/jpane-slider/))'
				.' { if ( thisSlider.parentNode.tagName.toLowerCase() != \'html\' ) thisSlider = thisSlider.parentNode; else break; }'
				.' if (thisIntro.style.display == \'none\') { thisIntro.style.display = \'block\'; if (thisSlider.style.height)'
				.' { thisSlider.style.height = (parseInt(thisSlider.style.height) + thisIntro.offsetHeight) + \'px\'; } }'
				.' else { if (thisSlider.style.height) { thisSlider.style.height = (parseInt(thisSlider.style.height) -'
				.' thisIntro.offsetHeight) + \'px\'; } thisIntro.style.display = \'none\'; }" title="'.JText::_('Show / hide details').'">'
				.JText::_('Show / hide details').'</a>)</small></small>';
			}

			$title .= '</h3>';
		}

		$html = '<div id="" class="panel"><div style="padding:.5em">'
		.$title.'<div id="'.$id.'" style="display:'.($open ? 'block' : 'none').'">'
		.$image.$description.($image ? '<div style="clear:both;height:0"></div>' : '').'</div>'
		.'</div></div>';

		return $html;
	}
}
?>