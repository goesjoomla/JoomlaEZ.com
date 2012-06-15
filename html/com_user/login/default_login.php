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

// get the active template
$template = basename(dirname(dirname(dirname(dirname(__FILE__)))));

$titleStyle	= $this->params->get('show_login_title', 1) ? '' : ' hide';

if (JPluginHelper::isEnabled('authentication', 'openid')) { // support OpenID authentication
	$lang = &JFactory::getLanguage();
	$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
	$langScript = 	'var JLanguage = {};'.
					' JLanguage.WHAT_IS_OPENID = \''.str_replace( 'OpenId', 'OpenID', JText::_('WHAT_IS_OPENID') ).'\';'.
					' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
					' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
					' var comlogin = 1;';
	$document =& JFactory::getDocument();
	$document->addScriptDeclaration( $langScript );
	JHTML::_('script', 'openid.js');

	// reformat OpenID links
	$head = $document->getHeadData();
	$loaded = false;
	foreach ($head['scripts'] AS $k => $v) {
		if (preg_match("/jezBaseFx\.js$/", $k)) {
			$loaded = true;
			break;
		}
	}
	if (!$loaded)
		JHTML::_('script', 'jezBaseFx.js', 'templates/'.$template.'/scripts/');

	$document->addScriptDeclaration('
		window.addEvent("load", function() {
			var thisForm = $("com-form-login");
			var list = thisForm.getElement("ul");
			var links = thisForm.getElements("a");
			var p = new Element("p").injectBefore(list);

			for (var i = 0; i < links.length; i++) {
				if (links[i].id == "com-openid-link")
					links[i].setStyle("display", "block").injectInside(p);
				else if (links[i].href.match(/openid\.net/)) {
					links[i].removeProperty("style").injectInside(p);
					if (!$defined(SqueezeBox))
						links[i].setProperty("target", "_blank");
				}
			}

			var state = Cookie.get("login-openid");
			var vR = jezGetVertRhythm(list);

			new jezFxStyle(list, {
				activateOn: "click",
				deactivateOn: "click",
				activeCss: {"opacity": 1, "height": list.offsetHeight, "margin-bottom": (vR.fontSize * 1.5)},
				inactiveCss: {"opacity": 0, "height": 0, "margin-bottom": 0},
				activeFx: {duration: 500, transition: Fx.Transitions.expoOut},
				inactiveFx: {duration: 500, transition: Fx.Transitions.expoIn},
				preset: state == 0 ? "active" : "inactive"
			}, $("com-openid-link"));

			new jezFxStyle($("com-form-login-password"), {
				activateOn: "click",
				deactivateOn: "click",
				activeCss: {"opacity": 1, "margin-bottom": (vR.fontSize * 1.5)},
				inactiveCss: {"opacity": 0, "margin-bottom": 0},
				activeFx: {duration: 500, transition: Fx.Transitions.expoOut},
				inactiveFx: {duration: 500, transition: Fx.Transitions.expoIn},
				preset: state == 0 ? "active" : "inactive"
			}, $("com-openid-link"));
		});
	');
}

// get the active template
$template = basename(dirname(dirname(dirname(dirname(__FILE__)))));
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
