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

$titleStyle	= $this->params->get('show_login_title', 1) ? '' : ' hide';

if (JPluginHelper::isEnabled('authentication', 'openid')) { // support OpenID authentication
	$document =& JFactory::getDocument();

	// format OpenID links
	require_once($tpl_dir.DS.'helper.php');
	jezThemeBaseHelper::loadScripts( 'jezBaseFx.js', 'templates/'.$template.'/scripts/' );

	$document->addScriptDeclaration('
window.addEvent("load", function() {
	var list = $("com-form-login").getElement("ul");
	var state = Cookie.get("login-openid");

	if (state == 1) {
		$("username").addClass("system-openid");
		$("com-openid-link").setHTML("'.JText::_( 'Normal Login' ).'");
		list.setStyle("display", "none");
	} else {
		$("username").removeClass("system-openid");
		$("com-openid-link").setHTML("'.JText::_( 'OpenID Login' ).'");
		list.setStyle("display", "block");
	}

	var passwdField = new jezFxStyle($("com-form-login-password"), {
		addEventHandler: false,
		activeCss: {"opacity": 1, "height": $("com-form-login-password").offsetHeight, "margin-bottom": $("com-form-login-password").getStyle("margin-bottom")},
		inactiveCss: {"opacity": 0, "margin-bottom": 0},
		activeFx: {duration: 500, transition: Fx.Transitions.expoOut},
		inactiveFx: {duration: 500, transition: Fx.Transitions.expoIn},
		preset: state == 0 ? "active" : "inactive"
	});

	var forgotList = new jezFxStyle(list, {
		addEventHandler: false,
		activeCss: {"opacity": 1, "height": list.offsetHeight, "margin-bottom": list.getStyle("margin-bottom")},
		inactiveCss: {"opacity": 0, "height": 0, "margin-bottom": 0},
		activeFx: {duration: 500, transition: Fx.Transitions.expoOut},
		inactiveFx: {duration: 500, transition: Fx.Transitions.expoIn},
		preset: state == 0 ? "active" : "inactive"
	});

	$("com-openid-link").addEvent("click", function(passwdField, forgotList) {
		var newState = 1 - (Cookie.get("login-openid") ? Cookie.get("login-openid") : 0);

		if (newState == 1) {
			passwdField.deactivate();
			forgotList.deactivate();
			$("username").addClass("system-openid");
			this.setHTML("'.JText::_( 'Normal Login' ).'");
		} else {
			passwdField.activate();
			forgotList.activate();
			$("username").removeClass("system-openid");
			this.setHTML("'.JText::_( 'OpenID Login' ).'");
		}

		Cookie.set("login-openid", newState);
	}.pass([passwdField, forgotList], $("com-openid-link")));
});
');
}
?>
<div id="jezComOutput" class="user_login<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('header_login') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('header_login')); ?></h2>
<?php endif; ?>
<form id="com-form-login" name="com-login" action="<?php echo JRoute::_( 'index.php', true, $this->params->get('usesecure')); ?>" method="post">
<?php if ($this->image || $this->params->get('description_login')) : ?>
<p class="info clearfix">
	<?php
	if ($this->image)
		echo preg_replace('/align="([^"]+)"/i', 'class="\\1"', $this->image);

	if ($this->params->get('description_login'))
		echo $this->params->get( 'description_login_text' );
	?>
</p>
<?php endif; ?>
<div class="tr">
	<div class="fl tc w70p">
	<fieldset>
		<legend><?php echo JText::_('Account'); ?></legend>
		<div id="com-form-login-username" class="tr gr2">
			<div class="fl tc tar">
				<label for="username"><?php echo JText::_('Username') ?></label>
			</div>
			<div class="fr">
				<input name="username" id="username" type="text" title="<?php echo JText::_('Username') ?>" class="inputbox" size="20" />
			</div>
		</div>
		<div id="com-form-login-password" class="tr gr2">
			<div class="fl tc tar">
				<label for="passwd"><?php echo JText::_('Password') ?></label>
			</div>
			<div class="fr">
				<input type="password" id="passwd" name="passwd" title="<?php echo JText::_('Password') ?>" class="inputbox" size="20" />
			</div>
		</div>
		<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
		<div id="com-form-login-remember" class="tr gr2">
			<div class="fl tc tar">&nbsp;</div>
			<div class="fr">
				<input type="checkbox" id="remember" name="remember" title="<?php echo JText::_('Remember me') ?>" value="yes" />
				<label for="remember"><?php echo JText::_('Remember me') ?></label>
			</div>
		</div>
		<?php endif; ?>
		<div class="tr gr2">
			<div class="fl tc tar">&nbsp;</div>
			<div class="fr">
				<button type="submit" title="<?php echo JText::_('LOGIN'); ?>">
					<?php echo JHTML::_('image', "templates/$template/images/icons/silk/lock_open.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('LOGIN'); ?></button>
			</div>
		</div>
	</fieldset>
	</div>
	<div class="fr w30p mt1_5">
	<?php if (JPluginHelper::isEnabled('authentication', 'openid')) : ?>
	<p>
		<a id="com-openid-link" style="cursor: pointer"></a>
		<br /><a href="http://openid.net" target="_blank"><?php echo JText::_('What is OpenID?'); ?></a>
	</p>
	<?php endif; ?>
	<ul>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>" title="<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?>"><?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
		</li>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>" title="<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?>"><?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
		</li>
		<?php $usersConfig = &JComponentHelper::getParams( 'com_users' );
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>" title="<?php echo JText::_('REGISTER'); ?>"><?php echo JText::_('REGISTER'); ?></a>
		</li>
		<?php endif; ?>
	</ul>
	</div>
</div>
<input type="hidden" name="option" value="com_user" />
<input type="hidden" name="task" value="login" />
<input type="hidden" name="return" value="<?php echo $this->return; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
