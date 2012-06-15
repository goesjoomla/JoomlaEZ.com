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
<div id="jezComOutput" class="user_complete<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<h2 class="componentheading"><?php echo JText::_('Reset your Password'); ?></h2>
<script type="text/javascript" language="javascript"><!-- // --><![CDATA[
window.onDomReady(function(){
	document.formvalidator.setHandler('passverify', function (value) { return ($('password1').value == value); }	);
});
// ]]></script>
<noscript>
<h6>Warning</h6>
<p>This website uses <a href="http://www.joomlaez.com/" title="Joomla Theme JEZ Rego"><em>Joomla Theme JEZ Rego</em></a>.</p>
<p><em>JEZ Rego</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox</a></em> is recommended, or enable JavaScript support in your browser for best experience with Joomla theme <em>JEZ Rego</em>.</p>
<p>Visit <em><a href="http://www.joomlaez.com/" title="Download Joomla themes and Joomla modules">JoomlaEZ.com to browse and download professional Joomla themes and Joomla modules for making your Joomla site more attractive</a></em>.</p>
</noscript>
<form action="<?php echo JRoute::_( 'index.php?option=com_user&task=completereset' ); ?>" method="post" id="josForm" name="josForm" class="form-validate">
<p class="intro"><?php echo JText::_('RESET_PASSWORD_COMPLETE_DESCRIPTION'); ?></p>
<fieldset>
<div class="tr">
	<div class="fl tc w30p">
		<label for="password1" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_PASSWORD1_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_PASSWORD1_TIP_TEXT'); ?>"><?php echo JText::_('Password'); ?></label>
	</div>
	<div class="fl w70p">
		<input id="password1" name="password1" type="password" title="<?php echo JText::_('Password'); ?>" class="inputbox required validate-password" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">
		<label for="password2" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_PASSWORD2_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_PASSWORD2_TIP_TEXT'); ?>"><?php echo JText::_('Verify Password'); ?></label>
	</div>
	<div class="fl w70p">
		<input id="password2" name="password2" type="password" title="<?php echo JText::_('Verify Password'); ?>" class="inputbox required validate-passverify" />
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
