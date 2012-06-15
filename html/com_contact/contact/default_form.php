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

defined( '_JEXEC' ) or die( 'Restricted access' );

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

// is JEZ reCAPTCHA installed and enabled?
$captcha = false;
if (@file_exists(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'jezReCaptcha/helper.php')) {
	require_once(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'jezReCaptcha/helper.php');
	if (plgSystemJezReCaptchaHelper::isEnabled())
		$captcha = true;
}

if (isset($this->error) && !empty($this->error))
	echo $this->error;
?>
<script type="text/javascript" language="javascript"><!-- // --><![CDATA[
function validateForm( frm ) {
	var valid = document.formvalidator.isValid(frm);
	if (valid == false) {
		// do field validation
		if (frm.email.invalid)
			alert( "<?php echo JText::_( 'Please enter a valid e-mail address.', true );?>" );
		else if (frm.text.invalid)
			alert( "<?php echo JText::_( 'CONTACT_FORM_NC', true ); ?>" );

		return false;
	} else
		frm.submit();
}
// ]]></script>
<noscript>
<h6>Warning</h6>
<p>This website uses <a href="http://www.joomlaez.com/" title="Joomla Theme JEZ Rego"><em>Joomla Theme JEZ Rego</em></a>.</p>
<p><em>JEZ Rego</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox</a></em> is recommended, or enable JavaScript support in your browser for best experience with Joomla theme <em>JEZ Rego</em>.</p>
<p>Visit <em><a href="http://www.joomlaez.com/" title="Download Joomla themes and Joomla modules">JoomlaEZ.com to browse and download professional Joomla themes and Joomla modules for making your Joomla site more attractive</a></em>.</p>
</noscript>
<form action="<?php echo JRoute::_( 'index.php' );?>" method="post" class="form-validate">
<fieldset>
<legend><?php echo JText::_( 'Send an email to this contact' );?></legend>
<div class="tr">
	<div class="fl tc w30p">
		<label for="contact_name"><?php echo JText::_( 'Your name' );?></label>
	</div>
	<div class="fl w70p">
		<input type="text" class="inputbox" name="name" id="contact_name" size="30" title="<?php echo JText::_( 'Enter your name' );?>" value="" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">
		<label id="contact_emailmsg" for="contact_email"><?php echo JText::_( 'Email address' );?></label> *
	</div>
	<div class="fl w70p">
		<input type="text" id="contact_email" name="email" size="30" value="" class="inputbox required validate-email" title="<?php echo JText::_( 'Email address' );?>" maxlength="100" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">
		<label for="contact_subject"><?php echo JText::_( 'Message subject' );?></label>
	</div>
	<div class="fl w70p">
		<input type="text" class="inputbox" name="subject" id="contact_subject" size="30" title="<?php echo JText::_( 'Message subject' );?>" value="" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">
		<label id="contact_textmsg" for="contact_text"><?php echo JText::_( 'Your message' );?></label> *
	</div>
	<div class="fl w70p">
		<textarea cols="50" rows="10" name="text" id="contact_text" class="required" title="<?php echo JText::_( 'Enter your message' );?>"></textarea>
	</div>
</div>
<?php if ($this->contact->params->get( 'show_email_copy' )) : ?>
<div class="tr">
	<div class="fl tc w30p">&nbsp;</div>
	<div class="fl w70p">
		<input type="checkbox" name="email_copy" id="contact_email_copy" value="1" title="<?php echo JText::_( 'EMAIL_A_COPY' ); ?>" />
		<label for="contact_email_copy"><?php echo JText::_( 'EMAIL_A_COPY' ); ?></label>
	</div>
</div>
<?php endif; ?>
<p class="info"><?php echo JText::_( 'Fields marked with an asterisk (*) are required.' ); ?></p>
<?php if ($captcha) : ?>
<div class="tr h12 captcha">
	<div class="fl tc w30p">&nbsp;</div>
	<div class="fl w70p">
		<?php global $mainframe;
		$mainframe->triggerEvent('onCaptchaDisplay'); ?>
	</div>
</div>
<?php endif; ?>
<div class="tr">
	<div class="fl tc w30p">&nbsp;</div>
	<div class="fl w70p">
		<button class="validate" type="submit" title="<?php echo JText::_('Send'); ?>">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/email_go.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Send'); ?></button>
	</div>
</div>
</fieldset>
<input type="hidden" name="option" value="com_contact" />
<input type="hidden" name="view" value="contact" />
<input type="hidden" name="id" value="<?php echo $this->contact->id; ?>" />
<input type="hidden" name="task" value="submit" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
