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
<div id="jezComOutput" class="user_confirm<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<h2 class="componentheading"><?php echo JText::_('Confirm your Account'); ?></h2>
<form action="<?php echo JRoute::_( 'index.php?option=com_user&task=confirmreset' ); ?>" method="post" class="josForm form-validate">
<p class="intro"><?php echo JText::_('RESET_PASSWORD_CONFIRM_DESCRIPTION'); ?></p>
<fieldset>
<div class="tr">
	<div class="fl tc w30p">
		<label for="token" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_TOKEN_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_TOKEN_TIP_TEXT'); ?>"><?php echo JText::_('Token'); ?></label>
	</div>
	<div class="fl w70p">
		<input id="token" name="token" type="text" title="<?php echo JText::_('Token'); ?>" size="30" class="inputbox required" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">&nbsp;</div>
	<div class="fl w70p">
		<button type="submit" class="validate" title="<?php echo JText::_('Submit'); ?>">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/key.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Submit'); ?></button>
	</div>
</div>
</fieldset>
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
