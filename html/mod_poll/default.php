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

defined('_JEXEC') or die;

if (!(isset($_COOKIE['jezTplName']) && isset($_COOKIE['jezTplDir']))) {
	// get template directory
	$tpl_dir = dirname(dirname(dirname(__FILE__)));
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
<form action="index.php" method="post" class="mod_poll<?php echo $params->get( 'moduleclass_sfx' ); ?>">
<h4><?php echo $poll->title; ?></h4>
<fieldset>
<?php for ($i = 0, $n = count($options); $i < $n; $i++) : ?>
<div class="tr row<?php echo ($i % 2); ?>">
	<div class="fl tc w20p tac">
		<input type="radio" name="voteid" id="voteid<?php echo $options[$i]->id;?>" value="<?php echo $options[$i]->id;?>" title="<?php echo $options[$i]->text; ?>" />
	</div>
	<div class="fl w80p">
		<label for="voteid<?php echo $options[$i]->id;?>"><?php echo $options[$i]->text; ?></label>
	</div>
</div>
<?php endfor; ?>
<div id="form-poll-button" class="tr">
	<div class="fl">
		<button type="submit" title="<?php echo JText::_('Vote'); ?>">
			<?php echo JText::_('Vote'); ?></button>
	</div>
	<div class="fr">
		<a class="button" href="<?php echo JRoute::_('index.php?option=com_poll&id='.$poll->slug.$itemid); ?>" title="<?php echo JText::_('Results'); ?>">
			<?php echo JText::_('Results'); ?></a>
	</div>
</div>
</fieldset>
<input type="hidden" name="option" value="com_poll" />
<input type="hidden" name="task" value="vote" />
<input type="hidden" name="id" value="<?php echo $poll->id;?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
