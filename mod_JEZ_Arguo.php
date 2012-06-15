<?php
/*
 * JEZ Arguo Joomla! 1.5 Module
 *
 * @package		Initialization
 * @version		1.1.4
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008 JoomlaEZ.com. All rights reserved
 * @license		Commercial Proprietary
 *
 * Please visit http://joomlaez.com/ for more information
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// include helper functions
require_once(dirname(__FILE__).DS.'helper.php');

// preset parameters
$params->set('hide_author', 1);
$params->set('hide_createdate', 1);
$params->set('hide_modifydate', 1);

// backward compatible with JEZ Arguo v1.0.0
if (in_array($params->get('source'), array('section', 'category', 'frontpage', 'archive', 'articles'))) {
	$params->set('content', 1);
	$params->set('content_ordering', $params->get('orderby'));

	switch ($params->get('source')) {
		case 'frontpage':
			$params->set('content_frontpage', 1);
			break;
		case 'archive':
			$params->set('content_archived', 1);
			break;
		case 'articles':
			$params->set('content_articles', $params->get('sourceid'));
			break;
	}

	if ($params->get('show_unpublished'))
		$params->set('content_unpublished', 1);

	if ($params->get('items'))
		$params->set('content_limit', $params->get('items'));
} elseif ($params->get('source') == 'newsfeeds') {
	$params->set('newsfeeds', $params->get('sourceid'));

	if ($params->get('show_unpublished'))
		$params->set('newsfeeds_unpublished', 1);

	if ($params->get('items'))
		$params->set('newsfeeds_limit', $params->get('items'));
} elseif ($params->get('source') == 'modules') {
	$params->set('modules', $params->get('sourceid'));
	$params->set('modules_ordering', $params->get('orderby'));
	$params->set('modules_title', $params->get('module_title'));

	if ($params->get('show_unpublished'))
		$params->set('modules_unpublished', 1);
} elseif ($params->get('source') == 'xml') {
	$params->set('xml', $params->get('sourceid'));
	$params->set('xml_utf8', $params->get('utf8xml'));
	$params->set('xml_itemNode', $params->get('item_node'));
	$params->set('xml_titleNode', $params->get('item_title_node'));
	$params->set('xml_contentNode', $params->get('item_content_node'));
	$params->set('xml_linkNode', $params->get('item_link_node'));

	if ($params->get('items'))
		$params->set('xml_limit', $params->get('items'));
}

// init content retrieval parameters
$params->def('content', 0);
$params->def('content_articles', '');

$params->def('content_allconds', 0);
$params->def('content_ordering', 'noorder');
$params->def('content_limit', 0);

$params->def('content_unpublished', 0);
$params->def('content_archived', 0);
$params->def('content_frontpage', 0);

$params->def('newsfeeds', '');
$params->def('newsfeeds_limit', 0);
$params->def('newsfeeds_unpublished', 0);

$params->def('modules', '');
$params->def('modules_ordering', 'noorder');
$params->def('modules_unpublished', 0);
$params->def('modules_title', -1);

$params->def('k2', 0);
$params->def('k2_items', '');

$params->def('k2_allconds', 0);
$params->def('k2_ordering', 'noorder');
$params->def('k2_limit', 0);

$params->def('k2_nested', 0);
$params->def('k2_unpublished', 0);
$params->def('k2_featured', 0);

$params->def('xml', '');
$params->def('xml_utf8', 0);
$params->def('xml_limit', 0);

$params->def('xml_itemNode', 'item');
$params->def('xml_titleNode', 'title');
$params->def('xml_contentNode', 'description');
$params->def('xml_linkNode', 'link');

// init display behavior parameters
$params->def('words', 0);
$params->def('image', 0);

$params->def('item_title', 1);
$params->def('title_tag', 'h3');
$params->def('link_title', 0);

$params->def('intro_only', 1);
$params->def('readmore', 1);

// init slideshow creation parameters
$params->def('initializeOn', 'domready');
$params->def('navigableBrowsing', 0);
$params->def('enableShuffle', 0);

$params->def('showPlay', 0);
$params->def('showBackward', 1);
$params->def('showFastBackward', 0);
$params->def('showForward', 1);
$params->def('showFastForward', 0);

$params->def('controlOrientation', 'horizontal');
$params->def('controlPosHoriz', 'bottom');
$params->def('controlAlignHoriz', 'center');
$params->def('controlPosVert', 'right');
$params->def('controlAlignVert', 'middle');

$params->def('showIndex', 1);
$params->def('indexesType', 'numeric');
$params->def('jTips', 0);

$params->def('indexControlRel', 'after');

$params->def('indexesOrientation', 'horizontal');
$params->def('indexPosHoriz', 'bottom');
$params->def('indexAlignHoriz', 'center');
$params->def('indexPosVert', 'right');
$params->def('indexAlignVert', 'middle');

$params->def('showScrollingButtons', 0);
$params->def('reverseScroll', 0);

$params->def('fxDuration', 500);
$params->def('transitionFx', 'crossfade');

$params->def('timed', 1);
$params->def('delay', 5000);
$params->def('autoStop', 1);

// disable edit ability icon
$access = new stdClass();
$access->canEdit = 0;
$access->canEditOwn = 0;
$access->canPublish = 0;

// get slideshow content
foreach (array('content', 'newsfeeds', 'modules', 'k2', 'xml') AS $source) {
	if ( trim($params->get($source)) ) {
		$helperFunc = 'getList' . ucfirst($source);
		$list[$source] = modJezNewsflasherHelper::$helperFunc($params, $access);
	}
}

// if no results found, we have nothing to do
if (!count($list))
	return;

// add required scripts to document head
modJezNewsflasherHelper::loadScripts();

// customize script
if ($params->get('title_tag') != 'h3') {
	$config[] = 'titleSelector: "'.$params->get('title_tag').'.title"';
	$config[] = 'titleTag: "'.$params->get('title_tag').'"';
}
if ($params->get('navigableBrowsing'))
	$config[] = 'navigableBrowsing: true';
if ($params->get('enableShuffle'))
	$config[] = 'enableShuffle: true';

// play button
if ($params->get('showPlay')) {
	$config[] = 'showPlay: true';
	$config[] = 'playTitle: "'.JText::_('Play Slideshow').'"';
}

// fast backward button
if ($params->get('showFastBackward')) {
	$config[] = 'showFastBackward: true';
	$config[] = 'fastBackwardTitle: "'.JText::_('Jump to Previous Item without Transition Effect').'"';
}

// fast forward button
if ($params->get('showFastForward')) {
	$config[] = 'showFastForward: true';
	$config[] = 'fastForwardTitle: "'.JText::_('Jump to Next Item without Transition Effect').'"';
}

// backward button
if (!$params->get('showBackward'))
	$config[] = 'showBackward: false';
else
	$config[] = 'backwardTitle: "'.JText::_('Previous Item').'"';

// forward button
if (!$params->get('showForward'))
	$config[] = 'showForward: false';
else
	$config[] = 'forwardTitle: "'.JText::_('Next Item').'"';

if ($params->get('controlOrientation') != 'horizontal')
	$config[] = 'controlOrientation: "'.$params->get('controlOrientation').'"';
if ($params->get('controlPosHoriz') != 'bottom')
	$config[] = 'controlPosHoriz: "'.$params->get('controlPosHoriz').'"';
if ($params->get('controlAlignHoriz') != 'center')
	$config[] = 'controlAlignHoriz: "'.$params->get('controlAlignHoriz').'"';
if ($params->get('controlPosVert') != 'right')
	$config[] = 'controlPosVert: "'.$params->get('controlPosVert').'"';
if ($params->get('controlAlignVert') != 'middle')
	$config[] = 'controlAlignVert: "'.$params->get('controlAlignVert').'"';

if (!$params->get('showIndex'))
	$config[] = 'showIndex: false';
if ($params->get('indexesType') != 'numeric')
	$config[] = 'indexesType: "'.$params->get('indexesType').'"';

if ($params->get('indexControlRel') != 'after')
	$config[] = 'indexControlRel: "'.$params->get('indexControlRel').'"';

if ($params->get('jTips')) {
	JHTML::_('script', 'joomla.javascript.js', 'includes/js/');
	$config[] = 'jTips: true';
}

if ($params->get('indexesOrientation') != 'horizontal')
	$config[] = 'indexesOrientation: "'.$params->get('indexesOrientation').'"';
if ($params->get('indexPosHoriz') != 'bottom')
	$config[] = 'indexPosHoriz: "'.$params->get('indexPosHoriz').'"';
if ($params->get('indexAlignHoriz') != 'center')
	$config[] = 'indexAlignHoriz: "'.$params->get('indexAlignHoriz').'"';
if ($params->get('indexPosVert') != 'right')
	$config[] = 'indexPosVert: "'.$params->get('indexPosVert').'"';
if ($params->get('indexAlignVert') != 'middle')
	$config[] = 'indexAlignVert: "'.$params->get('indexAlignVert').'"';

if ($params->get('showScrollingButtons')) {
	$config[] = 'showScrollingButtons: true';

	// titles
	$config[] = 'toBeginTitle: "'.JText::_('Scroll to Begin').'"';
	$config[] = 'toEndTitle: "'.JText::_('Scroll to End').'"';

	// is reverse scroll enabled?
	if ($params->get('reverseScroll'))
		$config[] = 'reverseScroll: true';
}

if ($params->get('fxDuration') != 500)
	$config[] = 'fxDuration: '.(int) $params->get('fxDuration');
if ($params->get('transitionFx') != "crossfade")
	$config[] = 'transitionFx: "'.$params->get('transitionFx').'"';
if (!$params->get('timed'))
	$config[] = 'timed: false';
if ($params->get('delay') != 5000)
	$config[] = 'delay: '.(int) $params->get('delay');
if (!$params->get('autoStop'))
	$config[] = 'autoStop: false';

// prepare module instance id
if (!$params->get('instanceID')) {
	$mainframe->set($mainframe->scope, $mainframe->get($mainframe->scope, 0) + 1);
	$params->set('instanceID', 'jezSJM'.$mainframe->get($mainframe->scope));
}

// load view template
require(JModuleHelper::getLayoutPath('mod_JEZ_Arguo', 'default'));
?>