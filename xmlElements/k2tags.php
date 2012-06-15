<?php
/*
 * JoomlaEZ.com's XML Elements Styler :: k2tags element
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
class JElementK2Tags extends JElement {
	var	$_name = 'k2tags';

	function fetchElement($name, $value, &$node, $control_name) {
		// get attributes
		$size		= $node->attributes('size');

		// get database object
		$db =& JFactory::getDBO();

		// K2 v1 or v2?
		$db->setQuery( "SHOW TABLES LIKE '%k2_tags'" );
		$k2CatTbl = $db->loadResult();

		// get all published K2 tags
		$query = "SELECT id, name FROM $k2CatTbl WHERE published = 1 ORDER BY name";
		$db->setQuery($query);
		$options = $db->loadObjectList();

		// set size for select list
		if ( empty($size) || $size > count($options) )
			$size = count($options);

		// prepare selected item(s)
		if ( !empty($value) && !is_array($value) )
			$value = preg_split('/\s*(,|\|)\s*/', $value);

		// build multiple selection select list then return
		return JHTML::_('select.genericlist', $options, $control_name.'['.$name.'][]', 'multiple="multiple" size="'.$size.'"', 'id', 'name', $value, $control_name.$name );
	}
}
?>