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
