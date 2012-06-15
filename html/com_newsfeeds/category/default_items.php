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
<form action="<?php echo JRoute::_('index.php?view=category&id='.$this->category->slug); ?>" method="post" name="adminForm">
<?php if ($this->params->get('show_limit')) : ?>
<div class="fr filter">
	<label for="limit"><?php echo JText::_('Display'); ?></label>
	<?php echo $this->pagination->getLimitBox(); ?>
	&nbsp;&nbsp;<input type="submit" title="<?php echo JText::_('Change'); ?>" value="<?php echo JText::_('Change'); ?>" />
</div>
<?php endif; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="listing borders clr">
<?php if ($this->params->get('show_headings')) : ?>
<thead>
	<tr>
		<th class="tac" width="5%"><?php $colSpan = 1; echo JText::_('Num'); ?></th>
		<?php if ( $this->params->get( 'show_name' ) ) : $colSpan++; ?>
		<th width="85%"><?php echo JText::_( 'Feed Name' ); ?></th>
		<?php endif;

		if ( $this->params->get( 'show_articles' ) ) : $colSpan++; ?>
		<th width="10%" class="tac" nowrap="nowrap"><?php echo JText::_( 'Num Articles' ); ?></th>
		<?php endif; ?>
	</tr>
</thead>
<?php endif; ?>
<tbody>
	<?php foreach ($this->items as $item) : ?>
	<tr class="row<?php echo $item->odd + 1; ?>">
		<td class="tac"><?php echo $item->count + 1; ?></td>
		<?php if ( $this->params->get( 'show_name' ) ) : ?>
		<td><a href="<?php echo $item->link; ?>" title="<?php echo $this->escape($item->name); ?>"><?php echo $this->escape($item->name); ?></a></td>
		<?php endif;

		if ( $this->params->get( 'show_articles' ) ) : ?>
		<td class="tac"><?php echo $item->numarticles; ?></td>
		<?php endif; ?>
	</tr>
	<?php endforeach; ?>
</tbody>
<?php if (($this->pagination->getPagesLinks() != '') || ($this->pagination->getPagesCounter() != '')) : ?>
<tfoot>
	<tr>
		<td colspan="<?php echo $colSpan; ?>">
			<?php if ($this->pagination->getPagesLinks() != '') : ?>
			<div class="pages_links">
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
			<?php endif;
			if ($this->pagination->getPagesCounter() != '') : ?>
			<div class="pages_counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</div>
			<?php endif; ?>
		</td>
	</tr>
</tfoot>
<?php endif; ?>
</table>
</form>
