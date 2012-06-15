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

$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';
?>
<div id="jezComOutput" class="content_archive<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif; ?>
<form id="jForm" action="<?php JRoute::_('index.php'); ?>" method="post">
<div class="fr filter">
	<?php if ($this->params->get('filter')) : ?>
	<label for="filter"><?php echo JText::_('Filter'); ?></label>
	<input type="text" class="inputbox" id="filter" name="filter" value="<?php echo $this->escape($this->filter); ?>" title="<?php echo JText::_('Keyword'); ?>" size="15" onchange="document.jForm.submit();" />
	<?php
	endif;

	echo '&nbsp;&nbsp;'.$this->form->monthField.'&nbsp;&nbsp;';
	echo '&nbsp;'.$this->form->yearField.'&nbsp;&nbsp;';
	?>
	<label for="limit"><?php echo JText::_('Display'); ?></label>
	<?php echo $this->form->limitField.'&nbsp;&nbsp;'; ?>
	<input type="submit" title="<?php echo JText::_('Filter'); ?>" value="<?php echo JText::_('Filter'); ?>" />
</div>
<?php echo $this->loadTemplate('items'); ?>
<input type="hidden" name="view" value="archive" />
<input type="hidden" name="option" value="com_content" />
</form>
</div>
