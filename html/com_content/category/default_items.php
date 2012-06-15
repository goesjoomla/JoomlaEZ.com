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

defined('_JEXEC') or die('Restricted access');

if (!(isset($_COOKIE['jezTplName']) && isset($_COOKIE['jezTplDir']))) {
	// get template directory
	$tpl_dir = dirname(dirname(dirname(dirname(__FILE__))));
	setcookie('jezTplDir', $tpl_dir);

	// get the active template
	$template = basename($tpl_dir);
	setcookie('jezTplName', $template);
} else {
	$tpl_dir = $_COOKIE['jezTplDir'];
	$template = $_COOKIE['jezTplName'];
}

// is language loaded?
if ( preg_match('/\?*JEZ_REGO\?*/', JText::_('JEZ_REGO')) ) {
	$lang =& JFactory::getLanguage();
	$lang->load( "tpl_{$template}", $tpl_dir );
}
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
<p>This website uses <a href="http://www.joomlaez.com/" title="Joomla Theme JEZ Rego"><em>Joomla Theme JEZ Rego</em></a>.</p>
<p><em>JEZ Rego</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox</a></em> is recommended, or enable JavaScript support in your browser for best experience with Joomla theme <em>JEZ Rego</em>.</p>
<p>Visit <em><a href="http://www.joomlaez.com/" title="Download Joomla themes and Joomla modules">JoomlaEZ.com to browse and download professional Joomla themes and Joomla modules for making your Joomla site more attractive</a></em>.</p>
</noscript>
<form action="<?php echo $this->action; ?>" method="post" name="adminForm">
<?php if ($this->params->get('filter') || $this->params->get('show_pagination_limit')) : ?>
<div class="fr filter">
	<?php if ($this->params->get('filter')) : ?>
	<label for="filter"><?php echo JText::_('Filter'); ?></label>
	<input type="text" class="inputbox" id="filter" name="filter" value="<?php echo $this->escape($this->lists['filter']);?>" title="<?php echo JText::_('Keyword'); ?>" size="15" onchange="document.adminForm.submit();" />&nbsp;&nbsp;
	<?php endif;

	if ($this->params->get('show_pagination_limit')) : ?>
	<label for="limit"><?php echo JText::_('Display'); ?></label>
	<?php
	echo $this->pagination->getLimitBox().'&nbsp;&nbsp;';
	endif;
	?>
	<input type="submit" title="<?php echo JText::_('Filter'); ?>" value="<?php echo JText::_('Filter'); ?>" />
</div>
<?php endif; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="listing borders clr">
<?php if ($this->params->get('show_headings')) : ?>
<thead>
	<tr>
		<th class="tac" width="5%"><?php $colSpan = 1; echo JText::_('Num'); ?></th>
		<?php if ($this->params->get('show_title')) : $colSpan++; ?>
	 	<th width="45%">
			<?php echo JHTML::_('grid.sort', 'Item Title', 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<?php endif;

		if ($this->params->get('show_date')) : $colSpan++; ?>
		<th width="25%">
			<?php echo JHTML::_('grid.sort', 'Date', 'a.created', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<?php endif;

		if ($this->params->get('show_author')) : $colSpan++; ?>
		<th width="20%">
			<?php echo JHTML::_('grid.sort', 'Author', 'author', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<?php endif;

		if ($this->params->get('show_hits')) : $colSpan++; ?>
		<th class="tac" width="5%" nowrap="nowrap">
			<?php echo JHTML::_('grid.sort', 'Hits', 'a.hits', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<?php endif; ?>
	</tr>
</thead>
<?php endif; ?>
<tbody>
	<?php foreach ($this->items as $item) : ?>
	<tr class="row<?php echo ($item->odd + 1 ); ?>" >
		<td class="tac"><?php echo $this->pagination->getRowOffset( $item->count ); ?></td>
		<?php if ($this->params->get('show_title')) :
		if ($item->access <= $this->user->get('aid', 0)) : ?>
		<td>
			<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>"><?php echo $this->escape($item->title); ?></a>
			<?php $this->item = $item; echo JHTML::_('icon.edit', $item, $this->params, $this->access); ?>
		</td>
		<?php else : ?>
		<td>
			<?php echo $this->escape($item->title).' : ';
			$link = JRoute::_('index.php?option=com_user&view=login');
			$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug, $item->sectionid), false);
			$fullURL = new JURI($link);
			$fullURL->setVar('return', base64_encode($returnURL));
			$link = $fullURL->toString(); ?>
			<a href="<?php echo $link; ?>" title="<?php echo $item->title; ?>"><?php echo JText::_( 'Register to read more...' ); ?></a>
		</td>
		<?php endif;
		endif;

		if ($this->params->get('show_date')) : ?>
		<td><?php echo $item->created; ?></td>
		<?php endif;

		if ($this->params->get('show_author')) : ?>
		<td><?php echo $this->escape($item->created_by_alias) ? $this->escape($item->created_by_alias) : $this->escape($item->author); ?></td>
		<?php endif;

		if ($this->params->get('show_hits')) : ?>
		<td class="tac"><?php echo $this->escape($item->hits) ? $this->escape($item->hits) : '-'; ?></td>
		<?php endif; ?>
	</tr>
	<?php endforeach; ?>
</tbody>
<?php if (
	($this->params->get('show_pagination') && ($this->pagination->getPagesLinks() != ''))
	||
	($this->params->get('show_pagination_results') && ($this->pagination->getPagesCounter() != ''))
) : ?>
<tfoot>
	<tr>
		<td colspan="<?php echo $colSpan; ?>">
			<?php if ($this->params->get('show_pagination') && ($this->pagination->getPagesLinks() != '')) : ?>
			<div class="pages_links">
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
			<?php endif;

			if ($this->params->get('show_pagination_results') && ($this->pagination->getPagesCounter() != '')) : ?>
			<div class="pages_counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</div>
			<?php endif; ?>
		</td>
	</tr>
</tfoot>
<?php endif; ?>
</table>
<input type="hidden" name="id" value="<?php echo $this->category->id; ?>" />
<input type="hidden" name="sectionid" value="<?php echo $this->category->sectionid; ?>" />
<input type="hidden" name="task" value="<?php echo $this->lists['task']; ?>" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>
