<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Output Overrides
*
* @package		JEZ Thema
* @version		1.1.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://joomlaez.com/ for more information
*/

/*----------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

if ( !defined('modMainMenuXMLCallbackDefined') ) {
	function modMainMenuXMLCallback(&$node, $args) 	{
		$user	= &JFactory::getUser();
		$menu	= &JSite::getMenu();
		$active	= $menu->getActive();
		$path	= isset($active) ? array_reverse($active->tree) : null;

		if (($args['end']) && ($node->attributes('level') >= $args['end'])) {
			$children = $node->children();
			foreach ($node->children() as $child) {
				if ($child->name() == 'ul')
					$node->removeChild($child);
			}
		}

		if ($node->name() == 'ul') { // menu customization
			foreach ($node->children() as $child) {
				if ($child->attributes('access') > $user->get('aid', 0))
					$node->removeChild($child);
			}

			if ( count($node->children()) ) {
				// mark nested level of menu
				if ($node->attributes('class'))
					$node->addAttribute('class', $node->attributes('class').' level'.($node->level() / 2));
				else
					$node->addAttribute('class', 'level'.($node->level() / 2));

				if ( isset($node->li) && is_array($node->li) && count($node->li) ) {
					// mark first menu item
					if ($node->li[0]->attributes('class'))
						$node->li[0]->addAttribute('class', $node->li[0]->attributes('class').' first');
					else
						$node->li[0]->addAttribute('class', 'first');

					// mark last menu item
					$last = count($node->li) - 1;
					if ($node->li[$last]->attributes('class'))
						$node->li[$last]->addAttribute('class', $node->li[$last]->attributes('class').' last');
					else
						$node->li[$last]->addAttribute('class', 'last');
				}
			}
		} elseif ($node->name() == 'li') {
			// mark nested level of menu item
			if ($node->attributes('class'))
				$node->addAttribute('class', $node->attributes('class').' level'.(($node->level() - 1) / 2));
			else
				$node->addAttribute('class', 'level'.(($node->level() - 1) / 2));

			// mark parent
			if ( isset($node->ul) ) {
				if ($node->attributes('class'))
					$node->addAttribute('class', $node->attributes('class').' parent');
				else
					$node->addAttribute('class', 'parent');
			}

			// add support for link title and express individual menu item styling
			if ( isset($node->a) || ( isset($node->span) && preg_match("/\bseparator\b/", $node->span[0]->attributes('class')) ) ) {
				$mItem = isset($node->a) ? $node->a[0] : $node->span[0];

				// detect Default Login Layout link then change item title per user login status
				if (
					$mItem->name() == 'a'
					&&
					(
						(preg_match('/option=com_user/', $mItem->attributes('href')) && preg_match('/view=login/', $mItem->attributes('href')))
						||
						preg_match('/'.JText::_('login').'(.*\..+)?$/i', $mItem->attributes('href'))
					)
				) {
					$user =& JFactory::getUser();
					if (!$user->get('guest')) {
						// there is an user already logged in, replace the 'login' word with 'logout'
						if ( isset($mItem->span) ) {
							$mItem->span[0]->setData(preg_replace(
								array('/'.strtoupper(JText::_('Login')).'/', '/'.strtolower(JText::_('Login')).'/', '/'.ucfirst(JText::_('Login')).'/'),
								array(strtoupper(JText::_('Logout')), strtolower(JText::_('Logout')), ucfirst(JText::_('Logout'))),
								$mItem->span[0]->data()
							));
						}
						if ( isset($mItem->img) ) {
							$mItem->img[0]->addAttribute('alt', preg_replace(
								array('/'.strtoupper(JText::_('Login')).'/', '/'.strtolower(JText::_('Login')).'/', '/'.ucfirst(JText::_('Login')).'/'),
								array(strtoupper(JText::_('Logout')), strtolower(JText::_('Logout')), ucfirst(JText::_('Logout'))),
								$mItem->img[0]->attributes('alt')
							));
						}
					} else {
						// guest or not logged in user, replace the 'logout' word with 'login'
						if ( isset($mItem->span) ) {
							$mItem->span[0]->setData(preg_replace(
								array('/'.strtoupper(JText::_('Logout')).'/', '/'.strtolower(JText::_('Logout')).'/', '/'.ucfirst(JText::_('Logout')).'/'),
								array(strtoupper(JText::_('Login')), strtolower(JText::_('Login')), ucfirst(JText::_('Login'))),
								$mItem->span[0]->data()
							));
						}
						if ( isset($mItem->img) ) {
							$mItem->img[0]->addAttribute('alt', preg_replace(
								array('/'.strtoupper(JText::_('Logout')).'/', '/'.strtolower(JText::_('Logout')).'/', '/'.ucfirst(JText::_('Logout')).'/'),
								array(strtoupper(JText::_('Login')), strtolower(JText::_('Login')), ucfirst(JText::_('Login'))),
								$mItem->img[0]->attributes('alt')
							));
						}
					}
				}

				// get text for title attribute
				if ( isset($mItem->span) )
					$parts = explode('::', $mItem->span[0]->data());
				elseif ( isset($mItem->img) )
					$parts = explode('::', $mItem->img[0]->attributes('alt'));

				// do we have text for link title?
				if ( isset($parts[1]) ) {
					if ( isset($mItem->span) )
						$mItem->span[0]->setData(trim($parts[0]));
					if ( isset($mItem->img) )
						$mItem->img[0]->addAttribute('alt', trim($parts[0]));

					// is this menu item not a separator?
					if ($mItem->name() == 'a')
						$mItem->addAttribute('title', trim($parts[1]));
				} elseif ($mItem->name() == 'a')
					$mItem->addAttribute('title', $parts[0]);

				// generate class attribute
				$mItemClass = strtolower( preg_replace( array('/[^\w^\d^\s]/', '/\s+/'), array('', '-'), $parts[0] ) );

				// list item customization
				if ($node->attributes('class'))
					$node->addAttribute('class', $node->attributes('class').' '.$mItemClass);
				else
					$node->addAttribute('class', $mItemClass);

			}
		}

		if ( isset($path) && (in_array($node->attributes('id'), $path) || in_array($node->attributes('rel'), $path)) ) {
			// mark active menu item
			if ($node->attributes('class'))
				$node->addAttribute('class', $node->attributes('class').' active');
			else
				$node->addAttribute('class', 'active');
		} else {
			if ( isset($args['children']) && !$args['children'] ) {
				$children = $node->children();
				foreach ($node->children() as $child) {
					if ($child->name() == 'ul')
						$node->removeChild($child);
				}
			}
		}

		// mark menu item linked to current page
		if (isset($path) && $node->attributes('id') == $path[0])
			$node->addAttribute('id', 'current');
		else
			$node->removeAttribute('id');

		$node->removeAttribute('rel');
		$node->removeAttribute('level');
		$node->removeAttribute('access');
	}

	define('modMainMenuXMLCallbackDefined', true);
}

modMainMenuHelper::render($params, 'modMainMenuXMLCallback');
?>