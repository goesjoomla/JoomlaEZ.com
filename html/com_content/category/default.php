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

$cparams =& JComponentHelper::getParams('com_media');
$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';
?>
<div id="jezComOutput" class="content_category<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif;

if ($this->category->image || $this->category->description != '') : ?>
<p class="info clearfix">
	<?php if ($this->category->image) : ?>
	<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'. $this->category->image;?>" class="<?php echo $this->category->image_position;?>" alt="<?php echo JText::_('Image'); ?>" />
	<?php
	endif;

	echo $this->category->description;
	?>
</p>
<?php
endif;

$this->items =& $this->getItems();
echo $this->loadTemplate('items');

if ($this->access->canEdit || $this->access->canEditOwn)
	echo JHTML::_('icon.create', $this->category, $this->params, $this->access);
?>
</div>
