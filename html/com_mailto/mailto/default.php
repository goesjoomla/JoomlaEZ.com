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

$data	= $this->get('data');

// is JEZ reCAPTCHA installed and enabled?
$captcha = false;
if (@file_exists(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'jezReCaptcha/helper.php')) {
	require_once(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'jezReCaptcha/helper.php');
	if (plgSystemJezReCaptchaHelper::isEnabled())
		$captcha = true;
}
?>
<script type="text/javascript" language="javascript"><!-- // --><![CDATA[
function submitbutton(pressbutton) {
	var form = document.mailtoForm;
	// do field validation
	if (form.mailto.value == "" || form.from.value == "") {
		alert( '<?php echo JText::_("EMAIL_ERR_NOINFO"); ?>' );
		return false;
	}
	form.submit();
}
// ]]></script>
<noscript>
<h6>Warning</h6>
<p>This website uses <a href="http://www.joomlaez.com/" title="Joomla Theme JEZ Rego"><em>Joomla Theme JEZ Rego</em></a>.</p>
<p><em>JEZ Rego</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox</a></em> is recommended, or enable JavaScript support in your browser for best experience with Joomla theme <em>JEZ Rego</em>.</p>
<p>Visit <em><a href="http://www.joomlaez.com/" title="Download Joomla themes and Joomla modules">JoomlaEZ.com to browse and download professional Joomla themes and Joomla modules for making your Joomla site more attractive</a></em>.</p>
</noscript>
<form action="<?php echo JURI::base() ?>index.php" name="mailtoForm" method="post" class="mailto">
<fieldset class="clr">
<legend><?php echo JText::_('EMAIL_THIS_LINK_TO_A_FRIEND'); ?></legend>
<div class="tr">
	<div class="fl tc w40p tar">
		<label><?php echo JText::_('Link'); ?></label>
	</div>
	<div class="fl w60p bold">
		<?php echo base64_decode($data->link); ?>
	</div>
</div>
<div class="tr">
	<div class="fl tc w40p tar">
		<label for="mailto"><?php echo JText::_('EMAIL_TO'); ?></label>
	</div>
	<div class="fl w60p">
		<input type="text" class="inputbox" id="mailto" name="mailto" title="<?php echo JText::_('EMAIL_TO'); ?>" size="30" value="<?php echo $this->escape($data->mailto); ?>" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w40p tar">
		<label for="sender"><?php echo JText::_('SENDER'); ?></label>
	</div>
	<div class="fl w60p">
		<input type="text" class="inputbox" id="sender" name="sender" title="<?php echo JText::_('SENDER'); ?>" value="<?php echo $this->escape($data->sender); ?>" size="30" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w40p tar">
		<label for="from"><?php echo JText::_('YOUR_EMAIL'); ?></label>
	</div>
	<div class="fl w60p">
		<input type="text" class="inputbox" id="from" name="from" title="<?php echo JText::_('YOUR_EMAIL'); ?>" value="<?php echo $this->escape($data->from); ?>" size="30" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w40p tar">
		<label for="subject"><?php echo JText::_('SUBJECT'); ?></label>
	</div>
	<div class="fl w60p">
		<input type="text" class="inputbox" id="subject" name="subject" title="<?php echo JText::_('SUBJECT'); ?>" value="<?php echo $this->escape($data->subject); ?>" size="30" />
	</div>
</div>
<?php if ($captcha) : ?>
<div class="tr h12 captcha">
	<div class="fl tc w40p">&nbsp;</div>
	<div class="fl w60p">
		<?php global $mainframe;
		$mainframe->triggerEvent('onCaptchaDisplay'); ?>
	</div>
</div>
<?php endif; ?>
<div class="tr">
	<div class="fl tc w40p">&nbsp;</div>
	<div class="fl w60p">
		<button onclick="return submitbutton('send');" class="positive fl" title="<?php echo JText::_('SEND'); ?>">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/tick.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('SEND'); ?></button>
	</div>
</div>
</fieldset>
<input type="hidden" name="layout" value="<?php echo $this->getLayout();?>" />
<input type="hidden" name="option" value="com_mailto" />
<input type="hidden" name="task" value="send" />
<input type="hidden" name="tmpl" value="component" />
<input type="hidden" name="link" value="<?php echo $data->link; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
