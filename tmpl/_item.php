<?php
/*
 * JEZ Arguo Joomla! 1.5 Module
 *
 * @package		View Render
 * @version		1.1.4
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008 JoomlaEZ.com. All rights reserved
 * @license		Commercial Proprietary
 *
 * Please visit http://joomlaez.com/ for more information
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if ( ($params->get('source') == 'modules' || ($params->get('source') != 'modules' && $params->get('item_title'))) && isset($item->title) ) { ?>
<<?php echo $params->get('title_tag'); ?> class="title">
	<?php if ( $params->get('source') != 'modules' && $params->get('link_title') && isset($item->linkOn) ) { ?>
	<a href="<?php echo $item->linkOn; ?>" title="<?php echo $item->title; ?>"><?php echo $item->title; ?></a>
	<?php } else echo $item->title; ?>
</<?php echo $params->get('title_tag'); ?>>
<?php } ?>

<div class="content">
	<?php if ( !in_array($params->get('source'), array('newsfeeds', 'modules', 'xml')) && !$params->get('intro_only') )
		echo $item->afterDisplayTitle;

	if ( !in_array($params->get('source'), array('newsfeeds', 'modules', 'xml')) )
		echo $item->beforeDisplayContent;

	echo JFilterOutput::ampReplace($item->text);

	if ( $params->get('source') != 'modules' && $params->get('readmore') && isset($item->linkOn) ) { ?>
	<p class="readon"><a href="<?php echo $item->linkOn; ?>">
		<?php echo (isset($item->linkText) && $item->linkText != '') ? $item->linkText : JText::sprintf('Read more...'); ?>
	</a></p>
	<?php } ?>
</div>
