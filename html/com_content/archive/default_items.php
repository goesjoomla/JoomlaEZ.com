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
?>
<ul class="listing clr">
<?php for ($i = 0; $i < count($this->items); $i++) : ?>
<li class="row<?php echo ($this->items[$i]->odd +1 ); ?>">
	<h2 class="contentheading">
		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->items[$i]->slug)); ?>" title="<?php echo $this->escape($this->items[$i]->title); ?>"><?php echo $this->escape($this->items[$i]->title); ?></a>
	</h2>
	<?php if (
		($this->params->get('show_section') && $this->items[$i]->sectionid && isset($this->items[$i]->section))
		||
		($this->params->get('show_category') && $this->items[$i]->catid)
		||
		$this->params->get('show_author')
		||
		$this->params->get('show_create_date')
	) : ?>
	<p class="info">
		<?php if ($this->params->get('show_section') && $this->items[$i]->sectionid && isset($this->items[$i]->section)) : ?>
		<span>
			<?php
			if ($this->params->get('link_section'))
				echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->items[$i]->sectionid)).'" title="'.$this->items[$i]->section.'">';

			echo $this->escape($this->items[$i]->section);

			if ($this->params->get('link_section'))
				echo '</a>';

			if ($this->params->get('show_category'))
				echo ' - ';
			?>
		</span>
		<?php endif;

		if ($this->params->get('show_category') && $this->items[$i]->catid) : ?>
		<span>
			<?php
			if ($this->params->get('link_category'))
				echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->items[$i]->catslug, $this->items[$i]->sectionid)).'" title="'.$this->items[$i]->category.'">';

			echo $this->escape($this->items[$i]->category);

			if ($this->params->get('link_category'))
				echo '</a>';
			?>
		</span>
		<?php endif;

		if ($this->params->get('show_author')) : ?>
		<span>
			<?php echo JText::_('Author').': '; echo $this->escape($this->items[$i]->created_by_alias) ? $this->escape($this->items[$i]->created_by_alias) : $this->escape($this->items[$i]->author); ?>
		</span>
		<?php endif;

		if ($this->params->get('show_create_date')) : ?>
		<span>
			<?php echo JText::_('Created') .': '.  JHTML::_( 'date', $this->items[$i]->created, JText::_('DATE_FORMAT_LC2')) ?>
		</span>
		<?php endif; ?>
	</p>
	<?php endif; ?>
	<p class="intro">
		<?php echo substr(strip_tags($this->items[$i]->introtext), 0, 255); ?>...
	</p>
	<?php if (
		($i + 1 < count($this->items))
		||
		($this->params->get('show_item_navigation') && ($this->pagination->getPagesLinks() != '' || $this->pagination->getPagesCounter() != ''))
	) : ?>
	<span class="article_separator">&nbsp;</span>
	<?php endif; ?>
</li>
<?php endfor; ?>
</ul>
<?php if ($this->params->get('show_item_navigation')) :
if ($this->pagination->getPagesLinks() != '') : ?>
<div class="pages_links">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<?php endif;

if ($this->pagination->getPagesCounter() != '') : ?>
<div class="pages_counter">
	<?php echo $this->pagination->getPagesCounter(); ?>
</div>
<?php endif;
endif; ?>
