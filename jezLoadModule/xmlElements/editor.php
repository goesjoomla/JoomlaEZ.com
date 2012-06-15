<?php
/*
 * JoomlaEZ.com's XML Elements Styler :: editor element
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

// editor element parser class
class JElementEditor extends JElement {
	var	$_name = 'Editor';

	// parse element attributes
	function fetchElement($name, $value, &$node, $control_name) {
		// get editor
		$editor =& JFactory::getEditor();

		return $editor->display( $control_name.'['.$name.']', html_entity_decode(JText::_($value)), '66.6%', '200', '50', '25', array('pagebreak', 'readmore') );
	}
}
?>