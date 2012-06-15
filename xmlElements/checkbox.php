<?php
/*
 * JoomlaEZ.com's XML Elements Styler :: checkbox element
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

// checkbox element parser class
class JElementCheckbox extends JElement {
	var	$_name = 'Checkbox';

	function fetchElement($name, $value, &$node, $control_name) {
		$html = '';

		if ( $node->children() ) {
			// multiple checkboxes
			$checked = explode(',', $value);

			// create html
			foreach ($node->children() as $option) {
				$val	= $option->attributes('value');
				$text	= $option->data();
				$html .= '
<input type="checkbox" id="'.$name.$val.'" name="'.$control_name.'['.$name.'][]" value="'.$val.'"'.(in_array($val, $checked) ? ' checked="checked"' : '').' />
<label for="'.$name.$val.'">'.JText::_($text).'</label><br />';
			}
		} else {
			// create html for single checkbox
			$html .= '
<input type="checkbox" id="'.$name.$value.'" name="'.$control_name.'['.$name.']" value="'.$node->attributes('default').'"'.($value == $node->attributes('default') ? ' checked="checked"' : '').' />';
		}

		return $html;
	}
}
?>