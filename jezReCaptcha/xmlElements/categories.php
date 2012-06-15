<?php
/*
 * JoomlaEZ.com's XML Elements Styler :: categories element
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

// categories element parser class
class JElementCategories extends JElement {
	var	$_name = 'Categories';

	function fetchElement($name, $value, &$node, $control_name) {
		// get attributes
		$size		= $node->attributes('size');
		$section	= $node->attributes('section');

		// get database object
		$db =& JFactory::getDBO();

		if ( !isset($section) ) {
			// alias for section
			$section = $node->attributes('scope');
			if ( !isset($section) )
				$section = 'content';
		}

		if ($section == 'content') {
			$query = 'SELECT c.id, CONCAT_WS( " / ", s.title, c.title ) AS title' .
				' FROM #__categories AS c' .
				' LEFT JOIN #__sections AS s ON s.id = c.section' .
				' WHERE c.published = 1' .
				' AND s.scope = '.$db->Quote($section).
				' ORDER BY s.title, c.title';
		} else {
			$query = 'SELECT id, title' .
				' FROM #__categories' .
				' WHERE published = 1' .
				' AND section = '.$db->Quote($section).
				' ORDER BY title';
		}
		$db->setQuery($query);
		$options = $db->loadObjectList();

		// set size for select list
		if ( empty($size) )
			$size = count($options);

		// prepare selected item(s)
		if ( !empty($value) )
			$value = preg_split('/\s*,\s*/', $value);

		// build multiple selection select list then return
		return JHTML::_('select.genericlist', $options, $control_name.'['.$name.'][]', 'multiple="multiple" size="'.$size.'"', 'id', 'title', $value, $control_name.$name );
	}
}
?>