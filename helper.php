<?php
/*
 * JEZ Arguo Joomla! 1.5 Module
 *
 * @package		Module Helpers
 * @version		1.1.4
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008 JoomlaEZ.com. All rights reserved
 * @license		Commercial Proprietary
 *
 * Please visit http://joomlaez.com/ for more information
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.module.helper');
require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modJezNewsflasherHelper {
	// function to render content item
	function renderItem(&$item, &$params, &$access) {
		global $mainframe;

		$user =& JFactory::getUser();

		if ( !in_array( $params->get('source'), array('newsfeeds', 'modules', 'xml') ) ) {
			if ( $params->get('source') == 'k2' && isset($item->intro_text) ) {
				// K2 v1
				$item->introtext = $item->intro_text;
				$item->fulltext = $item->full_text;
				unset($item->intro_text);
				unset($item->full_text);
			}

			$item->text = $params->get('intro_only') ? $item->introtext : $item->introtext."\n".$item->fulltext;
			$item->readmore = ($params->get('intro_only') && $params->get('readmore') && trim($item->fulltext) != '');

			if ($params->get('intro_only') && ($params->get('readmore') || $params->get('link_title'))) {
				// check if user has permission to view the full article or not
				if ($item->access <= (int) $user->get('aid', 0)) {
					if ($params->get('source') == 'k2') {
						// get associated menu item ID for K2 item
						require_once(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');

						$ItemID = K2HelperRoute::getItemRoute($item->id, $item->catid);
						if (empty($ItemID))
							$ItemID = K2HelperRoute::getCategoryRoute($item->catid);

						if (empty($ItemID)) {
							require_once(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'models'.DS.'itemlist.php');
							if ( class_exists(ModelK2ItemList) ) {
								// K2 v1
								$category = ModelK2ItemList::getCategory($item->catid);
							} else {
								// K2 v2
								$category = K2ModelItemList::getCategory($item->catid);
							}

							while ( $category->parent > 0 && empty($ItemID) ) {
								if ( class_exists(ModelK2ItemList) ) {
									// K2 v1
									$category = ModelK2ItemList::getCategory($category->parent);
								} else {
									// K2 v2
									$category = K2ModelItemList::getCategory($category->parent);
								}

								$ItemID = K2HelperRoute::getCategoryRoute($category->id);
							}
						}

						if ( is_null($ItemID) || $ItemID == "" )
							$ItemID = JRequest::getInt('Itemid');

						$item->linkOn = JRoute::_("index.php?option=com_k2&id={$item->id}:{$item->alias}&view=item&Itemid={$ItemID}");
					} else
						$item->linkOn = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, (isset($item->catslug) ? $item->catslug : ''), $item->sectionid));
				} else {
					$item->linkOn = JRoute::_('index.php?option=com_user&task=register');
					$item->linkText = JText::_('Login to read more...');
				}
			}

			if ( !$params->get('intro_only') ) {
				$results = $mainframe->triggerEvent('onAfterDisplayTitle', array(&$item, &$params, 1));
				$item->afterDisplayTitle = trim(implode("\n", $results));
			}

			$results = $mainframe->triggerEvent('onBeforeDisplayContent', array(&$item, &$params, 1));
			$item->beforeDisplayContent = trim(implode("\n", $results));
		}

		// let Joomla! route URL embeded inside content text
		if (preg_match_all('#<a\s+[^>]*href="([^"^>]+)"[^>]*>#si', $item->text, $matches, PREG_SET_ORDER) > 0) {
			foreach ($matches AS $match) {
				if (!preg_match('#^(http|https)://#i', $match[1]))
					$item->text = str_replace($match[0], str_replace($match[1], JRoute::_($match[1]), $match[0]), $item->text);
			}
		}

		if (!$params->get('image')) // strip images out of item content
			$item->text = preg_replace( '/<img[^>]*>/', '', $item->text);

		if ($params->get('words')) // truncate item content to number of words specified
			$item->text = modJezNewsflasherHelper::getWords($item->text, $params->get('words'));

		require(JModuleHelper::getLayoutPath('mod_JEZ_Arguo', '_item'));
	}

	// function to retrieve content created with com_content
	function getListContent(&$params, &$access) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$aid = (int) $user->get('aid', 0);

		$contentConfig =& JComponentHelper::getParams('com_content');
		$noauth = !$contentConfig->get('shownoauth');

		$date =& JFactory::getDate();
		$now = $date->toMySQL();
		$nullDate = $db->getNullDate();

		// counting
		$sCount = 0;
		if ( (is_array($params->get('content_sections')) && count($params->get('content_sections'))) || trim($params->get('content_sections')) != '' )
			$sCount = (array) $params->get('content_sections');
		elseif ( $params->get('source') == 'section' && trim($params->get('sourceid')) != '' ) {
			// backward compatiable with JEZ Arguo v1.0.0
			$sCount = preg_split('/\s*(,|\|)\s*/', $params->get('sourceid'));
		}

		$cCount = 0;
		if ( (is_array($params->get('content_categories')) && count($params->get('content_categories'))) || trim($params->get('content_categories')) != '' )
			$cCount = (array) $params->get('content_categories');
		elseif ( $params->get('source') == 'category' && trim($params->get('sourceid')) != '' ) {
			// backward compatiable with JEZ Arguo v1.0.0
			$cCount = preg_split('/\s*(,|\|)\s*/', $params->get('sourceid'));
		}

		$aCount = trim($params->get('content_articles'));

		// build query to retrieve articles belonging to specified sections
		if ( $sCount ) {
			$select[] = "CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as catslug";
			$joins[] = 'LEFT JOIN #__categories AS c ON c.id = a.catid';
			$joins[] = 'LEFT JOIN #__sections AS s ON s.id = a.sectionid';
			$where[] = "(\n\t\ta.sectionid IN (" . implode(', ', $sCount) . ")"
			. "\n\t\tAND\n\t\tc.section = s.id"
			. ($noauth ? "\n\t\tAND\n\t\tc.access <= $aid\n\t\tAND\n\t\ts.access <= $aid" : '') . "\n\t)";
		}

		// build query to retrieve articles belonging to specified categories
		if ( $cCount ) {
			if ( !$sCount ) {
				$select[] = "CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as catslug";
				$joins[] = 'LEFT JOIN #__categories AS c ON c.id = a.catid';
			}
			$where[] = "(\n\t\ta.catid IN (" . implode(', ', $cCount) . ")"
			. ($noauth ? "\n\t\tAND\n\t\tc.access <= $aid" : '') . "\n\t)";
		}

		// build query to retrieve specified articles
		if ($aCount) {
			$where[] = "(\n\t\ta.id IN (" . preg_replace('/\s*(,|\|)\s*/', ', ', $aCount) . ")"
			. ($noauth ? "\n\t\tAND\n\t\ta.access <= $aid" : '') . "\n\t)";
		}

		// conditional
		if (@is_array($where))
			$conds[] = "(\n\t" . implode($params->get('content_allconds') ? "\n\tAND\n\t" : "\n\tOR\n\t", $where) . "\n)";

		// publishing state
		if (
			($sCount || $cCount || $aCount || $params->get('content_frontpage'))
			&&
			!($params->get('content_unpublished') && $params->get('content_archived'))
		) {
			$state[] = "(\n\t\ta.state = 1"
			. "\n\t\tAND\n\t\t(\n\t\t\ta.publish_up = ".$db->Quote($nullDate)."\n\t\t\tOR\n\t\t\ta.publish_up <= ".$db->Quote($now)."\n\t\t)"
			. "\n\t\tAND\n\t\t(\n\t\t\ta.publish_down = ".$db->Quote($nullDate)."\n\t\t\tOR\n\t\t\ta.publish_down >= ".$db->Quote($now)."\n\t\t)\n\t)";

			if ($params->get('content_unpublished'))
				$state[] = 'a.state = 0';
		}

		if ($params->get('content_archived'))
			$state[] = 'a.state = -1';

		if (@is_array($state))
			$conds[] = "(\n\t" . implode("\n\tOR\n\t", $state) . "\n)";

		// build query to retrieve only frontpage articles
		if ($params->get('content_frontpage'))
			$joins[] = 'INNER JOIN #__content_frontpage AS f ON f.content_id = a.id';

		// prepare order control
		switch ($params->get('content_ordering')) {
			case 'random':
				$orderby[] = 'RAND()';
				break;
			case 'date':
				$orderby[] = 'a.created';
				break;
			case 'rdate':
				$orderby[] = 'a.created DESC';
				break;
			case 'alpha':
				$orderby[] = 'a.title';
				break;
			case 'ralpha':
				$orderby[] = 'a.title DESC';
				break;
			case 'hits':
				$orderby[] = 'a.hits';
				break;
			case 'rhits':
				$orderby[] = 'a.hits DESC';
				break;
			case 'ordering':
				if ($sCount)
					$orderby[] = 's.ordering';

				if ($cCount)
					$orderby[] = 'c.ordering';

				$orderby[] = 'a.ordering';
				break;
			case 'noorder':
			default:
				break;
		}

		// build final query
		$query = 'SELECT a.*,'
		. "\nCASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(':', a.id, a.alias) ELSE a.id END as slug"
		. (@is_array($select) ? ",\n" . implode(",\n", $select) : '')
		. "\nFROM #__content AS a"
		. (@is_array($joins) ? "\n" . implode("\n", $joins) : '')
		. (@is_array($conds) ? "\nWHERE\n" . implode("\nAND\n", $conds) : '')
		. (@is_array($orderby) ? "\nORDER BY " . implode(', ', $orderby) : '');

		// query for articles now
		$items = (int) $params->get('content_limit');

		if ($items > 0)
			$db->setQuery($query, 0, $items);
		else
			$db->setQuery($query);

		$rows = $db->loadObjectList();
		return $rows;
	}

	// function to retrieve content from newsfeeds managed by com_newsfeeds
	function getListNewsfeeds(&$params, &$access) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$aid = (int) $user->get('aid', 0);

		// get feeds data
		$query = 'SELECT f.*,'
		. "\nCASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as catslug"
		. "\nFROM #__newsfeeds AS f"
		. "\nLEFT JOIN #__categories AS c ON c.id = f.catid"
		. "\nWHERE\nf.id IN (" . preg_replace('/\s*(,|\|)\s*/', ', ', $params->get('newsfeeds')) . ")"
		. ($params->get('newsfeeds_unpublished') ? '' : "\nAND\nf.published = 1\nAND\nc.published = 1")
		. "\nAND\nc.access <= $aid";

		$db->setQuery($query);
		$newsfeeds = $db->loadObjectList();

		// get content from queried newsfeed links
		$items = (int) $params->get('newsfeeds_limit');
		$rows = array();

		foreach ($newsfeeds AS $newsfeed) {
			$options['rssUrl'] = $newsfeed->link;
			$options['cache_time'] = $newsfeed->cache_time;

			$rssDoc =& JFactory::getXMLparser('RSS', $options);
			if ($rssDoc == false)
				continue;
			else {
				$newsfeed->items = $rssDoc->get_items();
				if (!count($newsfeed->items))
					continue;
			}

			// is limitation specified?
			if ($items > 0) {
				$limit = $items - count($rows);
				if ($limit == 0)
					break;
				else
					$newsfeed->items = array_slice($newsfeed->items, 0, $limit);
			}

			// parse and store feeded items
			foreach ($newsfeed->items AS $item) {
				$row = new stdClass();

				// create link to read full article
				if (!is_null($item->get_link()) && ($params->get('readmore') || $params->get('link_title')))
					$row->linkOn = JRoute::_( $item->get_link() ).'" target="_blank';

				// store syndicated title and content
				$row->title = str_replace('&apos;', "'", $item->get_title());
				$row->text = str_replace('&apos;', "'", $item->get_description());

				$rows[] = $row;
			}
		}

		return $rows;
	}

	// function to render specified modules for use as slideshow content
	function getListModules(&$params, &$access) {
		global $mainframe;

		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$aid = (int) $user->get('aid', 0);

		// prepare order control for modules query
		switch ($params->get('modules_ordering')) {
			case 'random':
				$orderby = "\nORDER BY RAND()";
				break;
			case 'alpha':
				$orderby = "\nORDER BY title";
				break;
			case 'ralpha':
				$orderby = "\nORDER BY title DESC";
				break;
			case 'ordering':
				$orderby = "\nORDER BY ordering";
				break;
			case 'noorder':
			default:
				$orderby = '';
				break;
		}

		// build final query
		$query = "SELECT * FROM #__modules"
		. "\nWHERE\nid IN (" . preg_replace('/\s*(,|\|)\s*/', ', ', $params->get('modules')) . ")"
		. ($params->get('modules_unpublished') ? '' : "\nAND\npublished = 1")
		. "\nAND\naccess <= $aid"
		. "\nAND\nclient_id = " . (int) $mainframe->getClientId()
		. $orderby;

		// let's query for modules now
		$db->setQuery($query);
		$mods = $db->loadObjectList();

		// if we have result, render and store queried modules
		$rows = array();
		if ($mods) {
			foreach ($mods AS $mod) {
				// is this a custom HTML module?
				if ( !isset($mod->user) )
					$mod->user = $mod->module == 'mod_custom' ? 1 : 0;

				$row = new stdClass();
				$row->text = JModuleHelper::renderModule($mod);

				// get module title
				if ($params->get('module_title') == 1 || ($params->get('module_title') == -1 && $mod->showtitle))
					$row->title = $mod->title;

				$rows[] = $row;
			}
		}

		return $rows;
	}

	// function to retrieve content created with K2 CCK component
	function getListK2(&$params, &$access) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$aid = (int) $user->get('aid', 0);

		// counting
		$cCount = 0;
		if ( (is_array($params->get('k2_cats')) && count($params->get('k2_cats'))) || trim($params->get('k2_cats')) != '' )
			$cCount = (array) $params->get('k2_cats');

		$tCount = 0;
		if ( (is_array($params->get('k2_tags')) && count($params->get('k2_tags'))) || trim($params->get('k2_tags')) != '' )
			$tCount = (array) $params->get('k2_tags');

		$aCount = trim($params->get('k2_items'));

		// build query to retrieve articles belonging to specified categories
		if ($cCount) {
			if ($params->get('k2_nested')) {
				// get all nested categories of specified categories
				require_once(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'models'.DS.'itemlist.php');
				foreach ($cCount AS $id) {
					if ( class_exists(ModelK2ItemList) ) {
						// K2 v1
						$children = ModelK2ItemList::getCategoryChilds($id);
					} else {
						// K2 v2
						$children = K2ModelItemList::getCategoryChilds($id);
					}

					foreach ($children AS $child)
						$cats[] = $child;
				}
				$cats = array_unique(array_merge($cCount, $cats));
			}

			if ( !empty($cats) )
				$where[] = 'i.catid IN (' . implode(', ', $cats) . ')';
			else
				$where[] = 'i.catid IN (' . implode(', ', $cCount) . ')';
		}

		// build query to retrieve articles assigned specified tags
		if ($tCount) {
			// K2 v1 or v2?
			$db->setQuery( "SHOW TABLES LIKE '%k2_tags_xref'" );
			$k2TagsXrefTbl = $db->loadResult();

			if (!$k2TagsXrefTbl) {
				// K2 v1
				$where[] = "(\n\t\tFIND_IN_SET('" . implode("', i.tags)\n\t\tOR\n\t\tFIND_IN_SET('", $tCount) . "', i.tags)\n\t)";
			} else {
				// K2 v2
				$joins[] = "LEFT JOIN $k2TagsXrefTbl AS t ON t.itemID = i.id";
				$where[] = "(\n\t\tt.tagID IN (" . implode(', ', $tCount) . ")\n\t)";
			}
		}

		// build query to retrieve specified articles
		if ($aCount)
			$where[] = 'i.id IN (' . preg_replace('/\s*(,|\|)\s*/', ', ', $aCount) . ')';

		// conditional
		if (@is_array($where))
			$conds[] = "(\n\t" . implode($params->get('k2_allconds') ? "\n\tAND\n\t" : "\n\tOR\n\t", $where) . "\n)";

		// publishing state
		if ( !$params->get('k2_unpublished') )
			$conds[] = "i.published = 1";

		// access control
		$conds[] = "i.access <= $aid";

		// build query to retrieve only featured articles
		if ($params->get('k2_featured'))
			$conds[] = "i.featured = 1";

		// prepare order control
		switch ($params->get('k2_ordering')) {
			case 'random':
				$orderby = "\nORDER BY RAND()";
				break;
			case 'date':
				$orderby = "\nORDER BY i.created";
 					break;
			case 'rdate':
				$orderby = "\nORDER BY i.created DESC";
				break;
			case 'alpha':
				$orderby = "\nORDER BY i.title";
 					break;
			case 'ralpha':
				$orderby = "\nORDER BY i.title DESC";
				break;
			case 'ordering':
				$orderby = "\nORDER BY i.ordering";
				break;
			case 'noorder':
			default:
				$orderby = '';
				break;
		}

		// K2 v1 or v2?
		$db->setQuery( "SHOW TABLES LIKE '%k2_items'" );
		$k2ItemTbl = $db->loadResult();

		// build final query
		$query = "SELECT i.* FROM $k2ItemTbl AS i"
		. (@is_array($joins) ? "\n".implode("\n", $joins) : '')
		. (@is_array($conds) ? " WHERE\n" . implode("\nAND\n", $conds) : '')
		. $orderby;

		// let's query for K2 items now
		$items = (int) $params->get('k2_limit');

		if ($items > 0)
			$db->setQuery($query, 0, $items);
		else
			$db->setQuery($query);

		$rows = $db->loadObjectList();
		return $rows;
	}

	// function to retrieve data from XML document
	function getListXml(&$params, &$access) {
		$items = (int) $params->get('xml_limit');
		$rows = array();

		// init XML parser
		$xmlDoc =& JFactory::getXMLparser();
		$xmlDoc->resolveErrors(true);

		if (substr($params->get('xml'), 0, 5) == 'http:') {
			// enable parsing for remote XML document
			$xmlDoc->useHTTPClient(true);
		} elseif (!preg_match('/^(\/|[a-zA-Z]:)/', $params->get('xml'))) {
			// convert XML document's relative path to absolute path
			$params->set('xml', JPATH_SITE.DS.$params->get('xml'));
		}

		if ( $params->get('xml') == '' || ( substr($params->get('xml'), 0, 5) != 'http:' && !(@file_exists($params->get('xml'))) ) )
			return $rows;

		// get XML data
		if ($params->get('xml_utf8'))
			$success = $xmlDoc->loadXML_utf8($params->get('xml'));
		else
			$success = $xmlDoc->loadXML($params->get('xml'));

		if (!$success || !is_object($xmlDoc->documentElement)) {
			// free up memory from DOMIT parser
			unset($xmlDoc);
			return $rows;
		}

		// get slideshow items from XML document
		$xmlItems = $xmlDoc->getElementsByTagName(trim($params->get('xml_itemNode')));
		unset($xmlDoc);

		// parse and store slideshow items
		for ($i = 0, $n = $xmlItems->getLength(); $i < $n; $i++) {
			$item =& $xmlItems->item($i);
			$row = new stdClass();

			// get item title
			$tmp = $item->getElementsByTagName(trim($params->get('xml_titleNode')));
			if ($tmp->getLength()) {
				$tmp2 = $tmp->item(0);
				$row->title = $tmp2->getText();
			} else {
				// try to get item's title from the item node's attributes
				if ($item->hasAttribute(trim($params->get('xml_titleNode'))))
					$row->title = $item->getAttribute(trim($params->get('xml_titleNode')));
			}

			// get item content
			$tmp = $item->getElementsByTagName(trim($params->get('xml_contentNode')));
			if ($tmp->getLength()) {
				$tmp2 = $tmp->item(0);
				$row->text = $tmp2->getText();
			} else {
				// try to get item's content from the item node's attributes
				if ($item->hasAttribute(trim($params->get('xml_contentNode'))))
					$row->text = $item->getAttribute(trim($params->get('xml_contentNode')));
				else
					$row->text = '';
			}

			// get item associated link
			$tmp = $item->getElementsByTagName(trim($params->get('xml_linkNode')));
			if ($tmp->getLength()) {
				$tmp2 = $tmp->item(0);
				$row->linkOn = $tmp2->getText();
			} else {
				// try to get item's associated link from the item node's attributes
				if ($item->hasAttribute(trim($params->get('xml_linkNode'))))
					$row->linkOn = $item->getAttribute(trim($params->get('xml_linkNode')));
			}

			// fix an unknown bug of the XML parser
			if (isset($row->title) && preg_match('/http\//', $row->title))
				$row->title = str_replace('http/', '/', $row->title);
			if ($row->text != '' && preg_match('/http\//', $row->text))
				$row->text = str_replace('http/', '/', $row->text);
			if (isset($row->linkOn) && preg_match('/http\//', $row->linkOn))
				$row->linkOn = JRoute::_( str_replace('http/', '/', $row->linkOn) );

			$rows[] = $row;
			if ($items > 0 && count($rows) >= $items)
				break;
		}

		return $rows;
	}

	// function to truncate given content to specified words
	function getWords($text, $count) {
		$words = preg_split("/[\s\t\n]+/", str_replace('><', ">\n<", $text));

		if (count($words) > $count) {
			$i = 0;
			$openTag = array();
			$counting = 0;
			$text = '';

			while ($counting < $count && $i < count($words)) {
				$text .= ($text == '' ? '' : ' ').$words[$i];

				if (preg_match("/^.*<\/[^>]+>.*$/", $words[$i])) {
					// found close tag, e.g. </b>, </i>, </strong>, </em>
					array_pop($openTag);

					// increase words count also if the close tag is prefixed or suffixed with any word
					if (strpos($words[$i], '<') > 0 || strpos($words[$i], '>') < (strlen($words[$i]) - 1))
						$counting++;
				} elseif (preg_match("/^.*<[^>]+>.*$/", $words[$i])) {
					// found a single word open tag, e.g. <b>, <i>, <strong>, <em>
					$openTag[] = $words[$i];

					// found self-closed tag, e.g. <br/>
					if (preg_match("/^.*<[^\/^>]+\/>.*$/", $words[$i]))
						array_pop($openTag);

					// increase words count also if the open / self-closed tag is prefixed or suffixed with any word
					if (strpos($words[$i], '<') > 0 || strpos($words[$i], '>') < (strlen($words[$i]) - 1))
						$counting++;
				} elseif (preg_match("/^.*<[^\/^>]+$/", $words[$i])) {
					// found starting part of multi-words open tag, e.g. <a, <table
					$openTag[] = $words[$i];

					// increase words count also if the open tag is prefixed with any word
					if (strpos($words[$i], '<') > 0)
						$counting++;

					// get all remaining parts of the tag
					do {
						$i++;
						$text .= ' '.$words[$i];
					} while (!preg_match("/^.*>.*$/", $words[$i]));

					// increase words count if the final part of the tag is suffixed with any word
					if (strpos($words[$i], '>') < (strlen($words[$i]) - 1))
						$counting++;

					// found self-closed tag or the final part of the tag also contains close tag
					if (preg_match("/^.*\/>.*$/", $words[$i]) || preg_match("/^.*<\/[^>]+>.*$/", $words[$i]))
						array_pop($openTag);
				} else {
					// not a tag, increase words count
					$counting++;
				}

				$i++;
			}

			// complete the truncated text
			$text .= $i < count($words) ? '...' : '';

			if (count($openTag)) {
				// the truncated text has tag(s) that is/are not closed, close now
				for ($i = count($openTag) - 1; $i >= 0; $i--)
					$text .= '</'.preg_replace("/(.*<)|(>.*)/", '', $openTag[$i]).'>';
			}
		}

		return $text;
	}

	// function to load required or specified scripts
	function loadScripts($scripts = null, $path = 'modules/mod_JEZ_Arguo/', $matchExactPath = false) {
		if (!isset($scripts) || empty($scripts))
			$scripts = array('mootools.js', 'jezBaseFx.js', 'jezSlideshow.js', 'jezTextShow.js');
		elseif (!is_array($scripts))
			$scripts = array($scripts);

		// get document head and body
		$document = &JFactory::getDocument();
		$head = $document->getHeadData();
		$body = JResponse::getBody();
		$base = JURI::base(true);

		// if any script is already loaded, ignore it
		foreach ($scripts AS $script) {
			$loaded = false;
			foreach ($head['scripts'] AS $k => $v) {
				if (preg_match('#'.str_replace('.', '\\.', ($matchExactPath ? $path : '').$script).'$#', $k)) {
					$loaded = true;
					break;
				}
			}
			if (!$loaded) {
				if (empty($body)) {
					if ($script == 'mootools.js')
						JHTML::_('behavior.mootools');
					else
						JHTML::_('script', $script, $path);
				} else {
					if ($script == 'mootools')
						echo '<script type="text/javascript" src="'.$base.'/media/system/js/mootools.js"></script>';
					else
						echo '<script type="text/javascript" src="'.$base.'/'.$path.$script.'"></script>';
				}
			}
		}
	}

	// function to load required or specified stylesheets
	function loadStylesheets($stylesheets = null, $path = 'modules/mod_JEZ_Arguo/tmpl/', $matchExactPath = true) {
		if (!isset($stylesheets) || empty($stylesheets))
			$stylesheets = array('default.css');
		elseif (!is_array($stylesheets))
			$stylesheets = array($stylesheets);

		// get document head and body
		$document = &JFactory::getDocument();
		$head = $document->getHeadData();
		$body = JResponse::getBody();
		$base = JURI::base(true);

		// if any stylesheet is already loaded, ignore it
		foreach ($stylesheets AS $stylesheet) {
			$loaded = false;
			foreach ($head['styleSheets'] AS $k => $v) {
				if (preg_match('#'.str_replace('.', '\\.', ($matchExactPath ? $path : '').$stylesheet).'$#', $k)) {
					$loaded = true;
					break;
				}
			}
			if (!$loaded) {
				if (empty($body))
					JHTML::_('stylesheet', $stylesheet, $path);
				else
					echo '<link rel="stylesheet" href="'.$base.'/'.$path.$stylesheet.'" type="text/css" />';
			}
		}
	}
}

echo '
<noscript>
<p><a href="http://www.joomlaez.com/">Slideshow created by JEZ Arguo - Slideshow Joomla module developed by JoomlaEZ.com.</a></p>
</noscript>
';
?>