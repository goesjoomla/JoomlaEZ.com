<?php
/*
// "K2" Component by JoomlaWorks for Joomla! 1.5.x - Version 1.0.2b
// Copyright (c) 2006 - 2009 JoomlaWorks Ltd. All rights reserved.
// Released under the GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
// More info at http://www.joomlaworks.gr
// Designed and developed by JoomlaWorks.
// *** Last update: May 12th, 2009 ***
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class JElementK2Categories extends JElement
{

	var	$_name = 'k2categories';

	function fetchElement($name, $value, &$node, $control_name)
	{
		// get attribute
		$size = $node->attributes('size');

		// get database object
		$db = &JFactory::getDBO();

		// K2 v1 or v2?
		$db->setQuery( "SHOW TABLES LIKE '%k2_categories'" );
		$k2CatTbl = $db->loadResult();

		// get all published K2 categories
		$query = "SELECT id, name, parent FROM $k2CatTbl WHERE published != 0 ORDER BY parent, ordering";
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		$children = array();

		if ( $mitems )
		{
			foreach ( $mitems as $v )
			{
				$pt 	= $v->parent;
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}

		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		$mitems = array();

		foreach ( $list as $item ) {
			@$mitems[] = JHTML::_('select.option',  $item->id, '&nbsp;&nbsp;&nbsp;'. $item->treename.$item->title );
		}

		// set size for select list
		if ( empty($size) || $size > count($mitems) )
			$size = count($mitems);

		return JHTML::_('select.genericlist',  $mitems, ''.$control_name.'['.$name.'][]', 'class="inputbox" multiple="multiple" size="'.$size.'"', 'value', 'text', $value );

	}

}