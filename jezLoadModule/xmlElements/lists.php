<?php
/*
 * JoomlaEZ.com's XML Elements Styler :: lists element
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

// lists element parser class
class JElementLists extends JElement {
	var	$_name = 'Lists';

	function fetchElement($name, $value, &$node, $control_name) {
		// get attribute
		$size = $node->attributes('size');

		$options = array();
		foreach ($node->children() as $option) {
			$val = $option->attributes('value');
			$text = $option->data();
			$options[] = JHTML::_( 'select.option', $val, JText::_($text) );
		}

		// set size for select list
		if ( empty($size) )
			$size = count($options);

		// prepare selected item(s)
		if ( !empty($value) )
			$value = preg_split('/\s*,\s*/', $value);

		// build multiple selection select list then return
		return JHTML::_('select.genericlist', $options, $control_name.'['.$name.'][]', 'multiple="multiple" size="'.$size.'"', 'value', 'text', $value, $control_name.$name);
	}
}
?>