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

$canEdit	= ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
$pageTitle	= ($this->params->get('show_page_title', 1) && $this->params->get('page_title') != $this->article->title);
$titleTag	= $pageTitle ? 'h3' : 'h2';
?>
<div id="jezComOutput" class="content_article<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($pageTitle) : ?>
<h2 class="componentheading"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php
endif;

if (
	$this->params->get('show_title')
	&&
	(
		$canEdit
		||
		$this->params->get('show_pdf_icon')
		||
		$this->params->get('show_print_icon')
		||
		$this->params->get('show_email_icon')
	)
) : ?>
<div class="article_heading clearfix">
	<<?php echo $titleTag; ?> class="contentheading fl">
		<?php if ($this->params->get('link_titles') && $this->article->readmore_link != '') : ?>
		<a href="<?php echo $this->article->readmore_link?>" title="<?php echo $this->escape($this->article->title); ?>">
			<?php echo $this->escape($this->article->title); ?></a>
		<?php else :
		echo $this->escape($this->article->title);
		endif; ?>
	</<?php echo $titleTag; ?>>

	<span class="buttonheading fr">
		<?php
		if ( !isset($this->print) || !$this->print ) {
			if ($this->params->get('show_pdf_icon'))
				echo JHTML::_('icon.pdf', $this->article, $this->params, $this->access, array('class' => 'noicon'));

			if ( $this->params->get( 'show_print_icon' ))
				echo JHTML::_('icon.print_popup', $this->article, $this->params, $this->access);

			if ($this->params->get('show_email_icon'))
				echo preg_replace('/width=\d+,height=\d+/', 'width=640,height=480', JHTML::_('icon.email', $this->article, $this->params, $this->access));

			if ($canEdit)
				echo JHTML::_('icon.edit', $this->article, $this->params, $this->access);
		} else
			echo JHTML::_('icon.print_screen', $this->article, $this->params, $this->access);
		?>
	</span>
</div>
<?php elseif ($this->params->get('show_title')) : ?>
<<?php echo $titleTag; ?> class="contentheading">
	<?php if ($this->params->get('link_titles') && $this->article->readmore_link != '') : ?>
	<a href="<?php echo $this->article->readmore_link?>" title="<?php echo $this->escape($this->article->title); ?>">
		<?php echo $this->escape($this->article->title); ?></a>
	<?php else :
	echo $this->escape($this->article->title);
	endif; ?>
</<?php echo $titleTag; ?>>
<?php endif;

if (!$this->params->get('show_intro'))
	echo $this->article->event->afterDisplayTitle;

if (
	($this->params->get('show_section') && $this->article->sectionid && isset($this->article->section))
	||
	($this->params->get('show_category') && $this->article->catid)
	||
	(($this->params->get('show_author')) && ($this->article->author != ''))
	||
	$this->params->get('show_create_date')
	||
	(intval($this->article->modified) != 0 && $this->params->get('show_modify_date'))
	||
	($this->params->get('show_url') && $this->article->urls)
) : ?>
<p class="info">
	<?php if ($this->params->get('show_section') && $this->article->sectionid && isset($this->article->section)) : ?>
	<span>
		<?php
		if ($this->params->get('link_section'))
			echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->article->sectionid)).'" title="'.$this->article->section.'">';

		echo $this->escape($this->article->section);

		if ($this->params->get('link_section'))
			echo '</a>';

		if ($this->params->get('show_category'))
			echo ' - ';
		?>
	</span>
	<?php endif;

	if ($this->params->get('show_category') && $this->article->catid) : ?>
	<span>
		<?php
		if ($this->params->get('link_category'))
			echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->article->catslug, $this->article->sectionid)).'" title="'.$this->article->category.'">';

		echo $this->escape($this->article->category);

		if ($this->params->get('link_category'))
			echo '</a>';
		?>
	</span>
	<?php endif;

	if (($this->params->get('show_author')) && ($this->article->author != '')) : ?>
	<span>
		<?php JText::printf( 'Written by', ($this->escape($this->article->created_by_alias) ? $this->escape($this->article->created_by_alias) : $this->escape($this->article->author)) ); ?>
	</span>
	<?php endif;

	if ($this->params->get('show_create_date')) : ?>
	<span>
		<?php echo JText::_( 'Published on' ).' '.JHTML::_('date', $this->article->created, JText::_('DATE_FORMAT_LC2')) ?>
	</span>
	<?php endif;

	if (intval($this->article->modified) != 0 && $this->params->get('show_modify_date')) : ?>
	<span>
		<?php echo JText::_( 'Last updated on' ).' '.JHTML::_('date', $this->article->modified, JText::_('DATE_FORMAT_LC2')); ?>
	</span>
	<?php endif;

	if ($this->params->get('show_url') && $this->article->urls) : ?>
	<span><a href="http://<?php echo $this->article->urls; ?>" title="<?php echo $this->escape($this->article->title); ?>" target="_blank">
		<?php echo $this->escape($this->article->urls); ?>
	</a></span>
	<?php endif; ?>
</p>
<?php
endif;

echo $this->article->event->beforeDisplayContent;

if (isset($this->article->toc))
	echo $this->article->toc;

echo $this->article->text;

echo $this->article->event->afterDisplayContent;
?>
</div>
