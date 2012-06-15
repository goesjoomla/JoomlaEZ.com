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
<script type="text/javascript" language="javascript"><!-- // --><![CDATA[
function tableOrdering( order, dir, task ) {
	var form = document.adminForm;
	form.filter_order.value 	= order;
	form.filter_order_Dir.value	= dir;
	document.adminForm.submit( task );
}
// ]]></script>
<noscript>
<h6>Warning</h6>
<p>This website uses <a href="http://www.joomlaez.com/" title="JEZ Thema"><em>JEZ Thema</em></a> as the base for its Joomla template.</p>
<p><em>JEZ Thema</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox</a></em> is recommended, or enable JavaScript support in your browser for best experience with Joomla template created based on <em>JEZ Thema</em>.</p>
<p>Visit <em><a href="http://joomlaez.com/" title="Download Joomla themes and Joomla modules">JoomlaEZ.com to JoomlaEZ.com to browse and download professional Joomla themes and Joomla modules for making your Joomla site more attractive</a></em>.</p>
</noscript>
<form action="<?php echo JFilterOutput::ampReplace($this->action); ?>" method="post" name="adminForm">
<div class="fr filter">
	<label for="limit"><?php echo JText::_('Display'); ?></label>
	<?php echo $this->pagination->getLimitBox(); ?>
	&nbsp;&nbsp;<input type="submit" title="<?php echo JText::_('Change'); ?>" value="<?php echo JText::_('Change'); ?>" />
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="listing borders clr">
<?php if ( $this->params->def( 'show_headings', 1 ) ) : ?>
<thead>
	<tr>
		<th width="5%" class="tac"><?php echo JText::_('Num'); ?></th>
		<th width="85%">
			<?php $colSpan = 2; echo JHTML::_('grid.sort', 'Web Link', 'title', $this->lists['order_Dir'], $this->lists['order']); ?>
		</th>
		<?php if ( $this->params->get( 'show_link_hits' ) ) : $colSpan++; ?>
		<th width="10%" class="tac" nowrap="nowrap">
			<?php echo JHTML::_('grid.sort', 'Hits', 'hits', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<?php endif; ?>
	</tr>
</thead>
<?php endif; ?>
<tbody>
	<?php foreach ($this->items as $item) : ?>
	<tr class="row<?php echo $item->odd + 1; ?>">
		<td class="tac"><?php echo $this->pagination->getRowOffset( $item->count ); ?></td>
		<td>
			<?php if ( $item->image ) : ?>
			<?php echo $item->image; ?>&nbsp;&nbsp;
			<?php endif;

			echo str_replace('class="category"', '', $item->link);

			if ( $this->params->get( 'show_link_description' ) ) : ?>
			<p class="intro"><?php echo nl2br($this->escape($item->description)); ?></p>
			<?php endif; ?>
		</td>
		<?php if ( $this->params->get( 'show_link_hits' ) ) : ?>
		<td class="tac"><?php echo $item->hits; ?></td>
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
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>
