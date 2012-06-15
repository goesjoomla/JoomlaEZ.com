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
<div id="jezComOutput" class="content_section<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif;

if ($this->section->image || $this->section->description != '') : ?>
<p class="info clearfix">
	<?php if ($this->section->image) : ?>
	<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'. $this->section->image;?>" class="<?php echo $this->section->image_position;?>" alt="<?php echo JText::_('Image'); ?>" />
	<?php endif;

	echo $this->section->description; ?>
</p>
<?php endif;

if ($this->params->get('show_categories', 1)) : ?>
<ul class="listing">
<?php foreach ($this->categories as $category) :
if (!$this->params->get('show_empty_categories') && !$category->numitems) continue; ?>
<li>
	<h2>
		<a href="<?php echo $category->link; ?>" title="<?php echo $this->escape($category->title); ?>"><?php echo $this->escape($category->title); ?></a>
		<?php if ($this->params->get('show_cat_num_articles')) : ?>
		&nbsp;<span class="small">(
			<?php
			if ($category->numitems == 1)
				echo $category->numitems ." ". JText::_( 'item' );
			else
				echo $category->numitems ." ". JText::_( 'items' );
			?>
		)</span>
		<?php endif; ?>
	</h2>
	<?php if ($this->params->def('show_category_description', 1) && $category->description) : ?>
	<p class="intro">
		<?php echo $category->description; ?>
	</p>
	<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</div>
