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

$cparams =& JComponentHelper::getParams('com_media');
$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';
?>
<div id="jezComOutput" class="contact_category<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif;

if (
	(($this->params->get('image') != -1 && $this->params->get('image') != ''))
	||
	$this->category->image || $this->category->description != ''
) : ?>
<p class="info clearfix">
	<?php if ($this->params->get('image') != -1 && $this->params->get('image') != '') : ?>
	<img src="<?php echo $this->baseurl .'/images/stories/'. $this->params->get('image'); ?>" class="<?php echo $this->params->get('image_align'); ?>" alt="<?php echo JText::_('Image'); ?>" />
	<?php elseif ($this->category->image) : ?>
	<img src="<?php echo $this->baseurl .'/images/stories/'. $this->category->image; ?>" class="<?php echo $this->category->image_position; ?>" alt="<?php echo JText::_('Image'); ?>" />
	<?php
	endif;

	echo $this->category->description;
	?>
</p>
<?php endif; ?>
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
<?php if ($this->params->get('show_limit')) : ?>
<div class="fr filter">
	<label for="limit"><?php echo JText::_('Display'); ?></label>
	<?php echo $this->pagination->getLimitBox().'&nbsp;&nbsp;'; ?>
	<input type="submit" title="<?php echo JText::_('Change'); ?>" value="<?php echo JText::_('Change'); ?>" />
</div>
<?php endif; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="listing borders clr">
<?php if ($this->params->get( 'show_headings' )) : ?>
<thead>
	<tr>
		<th width="5%" class="tac"><?php echo JText::_('Num'); ?></th>
		<th width="20%">
			<?php $colSpan = 2; echo JHTML::_('grid.sort', 'Name', 'cd.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<?php if ( $this->params->get( 'show_position' ) ) : $colSpan++; ?>
		<th width="15%">
			<?php echo JHTML::_('grid.sort', 'Position', 'cd.con_position', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<?php endif;

		if ( $this->params->get( 'show_email' ) ) : $colSpan++; ?>
		<th width="30%"><?php echo JText::_( 'Email' ); ?></th>
		<?php endif;

		if ( $this->params->get( 'show_telephone' ) ) : $colSpan++; ?>
		<th width="10%"><?php echo JText::_( 'Phone' ); ?></th>
		<?php endif;

		if ( $this->params->get( 'show_mobile' ) ) : $colSpan++; ?>
		<th width="10%"><?php echo JText::_( 'Mobile' ); ?></th>
		<?php endif;

		if ( $this->params->get( 'show_fax' ) ) : $colSpan++; ?>
		<th width="10%"><?php echo JText::_( 'Fax' ); ?></th>
		<?php endif; ?>
	</tr>
</thead>
<?php endif;

echo $this->loadTemplate('items');

if (($this->pagination->getPagesLinks() != '') || ($this->pagination->getPagesCounter() != '')) : ?>
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
<input type="hidden" name="option" value="com_contact" />
<input type="hidden" name="catid" value="<?php echo $this->category->id;?>" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>
</div>
