<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Output Overrides
*
* @package		JEZ Rego
* @version		1.5.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://www.joomlaez.com/ for more information
*/

/*----------------------------------------------------------------------------*/

defined('_JEXEC') or die;

if ($params->get('item_title')) : ?>
<h4>
	<?php if ($params->get('link_titles') && $item->linkOn != '') : ?>
	<a href="<?php echo $item->linkOn;?>" title="<?php echo $item->title;?>"><?php echo $item->title;?></a>
	<?php
	else :
		echo $item->title;
	endif;
	?>
</h4>
<?php
endif;

if (!$params->get('intro_only'))
	echo $item->afterDisplayTitle;

echo $item->beforeDisplayContent;
echo $item->text;

if (isset($item->linkOn) && $item->readmore && $params->get('readmore')) : ?>
<p class="readon clearfix">
	<a href="'.$item->linkOn.'" title="<?php echo $item->title;?>"><?php echo $item->linkText; ?></a>
</p>
<?php endif; ?>
