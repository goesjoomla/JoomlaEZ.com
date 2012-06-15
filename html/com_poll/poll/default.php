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

JHTML::_('stylesheet', 'poll_bars.css', 'components/com_poll/assets/');
?>
<div id="jezComOutput" class="poll<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif; ?>
<form action="index.php" method="post" name="poll">
<div class="fr filter">
	<label for="poll"><?php echo JText::_('Select Poll'); ?></label>
	<?php echo preg_replace('/id="[^"]+"/i', 'id="poll"', $this->lists['polls']); ?>
	&nbsp;&nbsp;<input type="submit" title="<?php echo JText::_('View'); ?>" value="<?php echo JText::_('View'); ?>" />
</div>
<?php echo $this->loadTemplate('graph'); ?>
</form>
</div>
