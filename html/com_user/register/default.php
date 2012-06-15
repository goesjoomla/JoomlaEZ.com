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

// is JEZ reCAPTCHA installed and enabled?
$captcha = false;
if (@file_exists(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'jezReCaptcha/helper.php')) {
	require_once(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'jezReCaptcha/helper.php');
	if (plgSystemJezReCaptchaHelper::isEnabled())
		$captcha = true;
}
?>
<div id="jezComOutput" class="user_register<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php
if (isset($this->message))
	$this->display('message');

if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif; ?>
<script type="text/javascript" language="javascript"><!-- // --><![CDATA[
window.onDomReady(function(){
	document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
});
// ]]></script>
<noscript>
<h6>Warning</h6>
<p>This website uses <a href="http://www.joomlaez.com/" title="Joomla Theme JEZ Rego"><em>Joomla Theme JEZ Rego</em></a>.</p>
<p><em>JEZ Rego</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox</a></em> is recommended, or enable JavaScript support in your browser for best experience with Joomla theme <em>JEZ Rego</em>.</p>
<p>Visit <em><a href="http://www.joomlaez.com/" title="Download Joomla themes and Joomla modules">JoomlaEZ.com to browse and download professional Joomla themes and Joomla modules for making your Joomla site more attractive</a></em>.</p>
</noscript>
<form action="<?php echo JRoute::_( 'index.php?option=com_user' ); ?>" method="post" id="josForm" name="josForm" class="form-validate">
<fieldset>
<legend><?php echo JText::_('Account'); ?></legend>
<div class="tr">
	<div class="fl tc w40p"><label id="namemsg" for="name"><?php echo JText::_( 'Name' ); ?></label> *</div>
	<div class="fl w60p">
		<input type="text" name="name" id="name" title="<?php echo JText::_( 'Name' ); ?>" size="30" value="<?php echo $this->escape($this->user->get( 'name' ));?>" class="inputbox required" maxlength="50" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w40p"><label id="usernamemsg" for="username"><?php echo JText::_( 'Username' ); ?></label> *</div>
	<div class="fl w60p">
		<input type="text" id="username" name="username" title="<?php echo JText::_( 'Username' ); ?>" size="30" value="<?php echo $this->escape($this->user->get( 'username' ));?>" class="inputbox required validate-username" maxlength="25" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w40p"><label id="emailmsg" for="email"><?php echo JText::_( 'Email' ); ?></label> *</div>
	<div class="fl w60p">
		<input type="text" id="email" name="email" title="<?php echo JText::_( 'Email' ); ?>" size="30" value="<?php echo $this->escape($this->user->get( 'email' ));?>" class="inputbox required validate-email" maxlength="100" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w40p"><label id="pwmsg" for="password"><?php echo JText::_( 'Password' ); ?></label> *</div>
	<div class="fl w60p">
		<input class="inputbox required validate-password" type="password" id="password" name="password" title="<?php echo JText::_( 'Password' ); ?>" size="30" value="" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w40p"><label id="pw2msg" for="password2"><?php echo JText::_( 'Verify Password' ); ?></label> *</div>
	<div class="fl w60p">
		<input class="inputbox required validate-passverify" type="password" id="password2" name="password2" title="<?php echo JText::_( 'Verify Password' ); ?>" size="30" value="" />
	</div>
</div>
<p class="info"><?php echo JText::_( 'REGISTER_REQUIRED' ); ?></p>
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
		<button class="validate" type="submit" title="<?php echo JText::_('Register'); ?>">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/user_add.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Register'); ?></button>
	</div>
</div>
</fieldset>
<input type="hidden" name="task" value="register_save" />
<input type="hidden" name="id" value="0" />
<input type="hidden" name="gid" value="0" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
