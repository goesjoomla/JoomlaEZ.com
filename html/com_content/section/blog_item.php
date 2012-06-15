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

$canEdit = ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
?>
<div class="content_article<?php echo ($this->item->state == 0 ? ' system-unpublished' : '').(isset($this->item->toc) ? ' tr' : ''); ?>">
<?php if (
	$this->item->params->get('show_title')
	&&
	(
		$canEdit
		||
		$this->item->params->get('show_pdf_icon')
		||
		$this->item->params->get('show_print_icon')
		||
		$this->item->params->get('show_email_icon')
	)
) : ?>
<div class="article_heading clearfix">
	<h3 class="contentheading fl">
		<?php if ($this->item->params->get('link_titles') && $this->item->readmore_link != '') : ?>
		<a href="<?php echo $this->item->readmore_link?>" title="<?php echo $this->escape($this->item->title); ?>">
			<?php echo $this->escape($this->item->title); ?></a>
		<?php elseif ($this->item->params->get('show_title')) :
		echo $this->escape($this->item->title);
		endif; ?>
	</h3>

	<span class="buttonheading fr">
		<?php
		if ( !isset($this->print) || !$this->print ) {
			if ($this->item->params->get('show_pdf_icon'))
				echo JHTML::_('icon.pdf', $this->item, $this->item->params, $this->access, array('class' => 'noicon'));

			if ( $this->item->params->get( 'show_print_icon' ))
				echo JHTML::_('icon.print_popup', $this->item, $this->item->params, $this->access);

			if ($this->item->params->get('show_email_icon'))
				echo preg_replace('/width=\d+,height=\d+/', 'width=640,height=480', JHTML::_('icon.email', $this->item, $this->item->params, $this->access));

			if ($canEdit)
				echo JHTML::_('icon.edit', $this->item, $this->item->params, $this->access);
		} else
			echo JHTML::_('icon.print_screen', $this->item, $this->item->params, $this->access);
		?>
	</span>
</div>
<?php elseif ($this->item->params->get('show_title')) : ?>
<h3 class="contentheading">
	<?php if ($this->item->params->get('link_titles') && $this->item->readmore_link != '') : ?>
	<a href="<?php echo $this->item->readmore_link?>" title="<?php echo $this->escape($this->item->title); ?>">
		<?php echo $this->escape($this->item->title); ?></a>
	<?php elseif ($this->item->params->get('show_title')) :
	echo $this->escape($this->item->title);
	endif; ?>
</h3>
<?php endif;

if (!$this->item->params->get('show_intro'))
	echo $this->item->event->afterDisplayTitle;

if (
	($this->item->params->get('show_section') && $this->item->sectionid && isset($this->section->title))
	||
	($this->item->params->get('show_category') && $this->item->catid)
	||
	(($this->item->params->get('show_author')) && ($this->item->author != ''))
	||
	$this->item->params->get('show_create_date')
	||
	(intval($this->item->modified) != 0 && $this->item->params->get('show_modify_date'))
	||
	($this->item->params->get('show_url') && $this->item->urls)
) : ?>
<p class="info">
	<?php if ($this->item->params->get('show_section') && $this->item->sectionid && isset($this->section->title)) : ?>
	<span>
		<?php
		if ($this->item->params->get('link_section'))
			echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->item->sectionid)).'" title="'.$this->escape($this->section->title).'">';

		echo $this->escape($this->section->title);

		if ($this->item->params->get('link_section'))
			echo '</a>';

		if ($this->item->params->get('show_category'))
			echo ' - ';
		?>
	</span>
	<?php endif;

	if ($this->item->params->get('show_category') && $this->item->catid) : ?>
	<span>
		<?php
		if ($this->item->params->get('link_category'))
			echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug, $this->item->sectionid)).'" title="'.$this->item->category.'">';

		echo $this->escape($this->item->category);

		if ($this->item->params->get('link_category'))
			echo '</a>';
		?>
	</span>
	<?php endif;

	if (($this->item->params->get('show_author')) && ($this->item->author != '')) : ?>
	<span>
		<?php JText::printf( 'Written by', ($this->escape($this->item->created_by_alias) ? $this->escape($this->item->created_by_alias) : $this->escape($this->item->author)) ); ?>
	</span>
	<?php endif;

	if ($this->item->params->get('show_create_date')) : ?>
	<span>
		<?php echo JText::_( 'Published on' ).' '.JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2')) ?>
	</span>
	<?php endif;

	if (intval($this->item->modified) != 0 && $this->item->params->get('show_modify_date')) : ?>
	<span>
		<?php echo JText::_( 'Last updated on' ).' '.JHTML::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2')); ?>
	</span>
	<?php endif;

	if ($this->item->params->get('show_url') && $this->item->urls) : ?>
	<span><a href="http://<?php echo $this->escape($this->item->urls); ?>" title="<?php echo $this->escape($this->item->title); ?>" target="_blank">
		<?php echo $this->escape($this->item->urls); ?>
	</a></span>
	<?php endif; ?>
</p>
<?php endif;

echo $this->item->event->beforeDisplayContent;

if (isset($this->item->toc))
	echo $this->item->toc;

echo $this->item->text;

if ($this->item->params->get('show_readmore') && $this->item->readmore) : ?>
<p class="readon clearfix">
	<a href="<?php echo $this->item->readmore_link; ?>" title="<?php echo $this->escape($this->item->title); ?>"><?php if ($this->item->readmore_register) :
		echo JText::_('Register to read more...');
	elseif ($readmore = $this->item->params->get('readmore')) :
		echo $readmore;
	else :
		echo JText::sprintf('Read more...');
	endif; ?></a>
</p>
<?php
endif;

echo $this->item->event->afterDisplayContent;
?>
</div>
