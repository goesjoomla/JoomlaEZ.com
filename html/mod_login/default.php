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

defined('_JEXEC') or die;

// get the active template
$template = basename(dirname(dirname(dirname(__FILE__))));

if ($type == 'logout') :
?>
<form action="index.php" method="post" class="mod_logout<?php echo $params->get( 'moduleclass_sfx' ); ?>">
	<?php if ($params->get('greeting')) : ?>
	<p class="intro">
	<?php
	if ($params->get('name'))
		echo JText::sprintf( 'HINAME', $user->get('name') );
	else
		echo JText::sprintf( 'HINAME', $user->get('username') );
	?>
	</p>
	<?php endif; ?>
	<div class="tac">
		<button type="submit" class="negative" title="<?php echo JText::_('BUTTON_LOGOUT'); ?>">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/lock.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('BUTTON_LOGOUT'); ?></button>
	</div>
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
</form>
<?php
else :
	if (JPluginHelper::isEnabled('authentication', 'openid')) { // support OpenID authentication
		$document = &JFactory::getDocument();
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
			window.addEvent("domready", function() {
				var state = Cookie.get("login-openid") ? Cookie.get("login-openid") : 0;

				if (state == 1) {
					$("modlgn_username").addClass("system-openid");
					$("openid-switcher").setHTML("'.JText::_( 'Normal Login' ).'");
					$("form-login-register").setStyle("display", "none");
				} else {
					$("modlgn_username").removeClass("system-openid");
					$("openid-switcher").setHTML("'.JText::_( 'OpenID Login' ).'");
					$("form-login-register").setStyle("display", "block");
				}

				var passwdField = new jezFxStyle($("form-login-password"), {
					addEventHandler: false,
					activeCss: {"opacity": 1, "height": $("form-login-password").offsetHeight, "margin-bottom": $("form-login-password").getStyle("margin-bottom")},
					inactiveCss: {"opacity": 0, "height": 0, "margin-bottom": 0},
					activeFx: {duration: 500},
					inactiveFx: {duration: 500},
					preset: state == 0 ? "active" : "inactive"
				});

				var forgotList = new jezFxStyle($("form-login-forgot"), {
					addEventHandler: false,
					activeCss: {"opacity": 1, "height": $("form-login-forgot").offsetHeight, "margin-bottom": $("form-login-forgot").getStyle("margin-bottom")},
					inactiveCss: {"opacity": 0, "height": 0, "margin-bottom": 0},
					activeFx: {duration: 500},
					inactiveFx: {duration: 500},
					preset: state == 0 ? "active" : "inactive"
				});

				$("openid-switcher").addEvent("click", function(passwdField, forgotList) {
					var newState = 1 - (Cookie.get("login-openid") ? Cookie.get("login-openid") : 0);

					if (newState == 1) {
						passwdField.deactivate();
						forgotList.deactivate();
						$("modlgn_username").addClass("system-openid");
						this.setHTML("'.JText::_( 'Normal Login' ).'");
						$("form-login-register").setStyle("display", "none");
					} else {
						passwdField.activate();
						forgotList.activate();
						$("modlgn_username").removeClass("system-openid");
						this.setHTML("'.JText::_( 'OpenID Login' ).'");
						$("form-login-register").setStyle("display", "block");
					}

					Cookie.set("login-openid", newState);
				}.pass([passwdField, forgotList], $("openid-switcher")));
			});
		');
	}
?>
<form id="form-login" name="login" action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" class="mod_login<?php echo $params->get( 'moduleclass_sfx' ); ?>">
	<?php if ($params->get('pretext') != '') : ?>
	<p class="intro pre"><?php echo $params->get('pretext'); ?></p>
	<?php endif; ?>
	<fieldset>
	<div id="form-login-username" class="tr">
		<div class="fl">
			<label for="modlgn_username"><?php echo JText::_('Username') ?></label>
		</div>
		<div class="fr">
			<input id="modlgn_username" type="text" name="username" title="<?php echo JText::_('Username') ?>" class="inputbox" size="18" />
		</div>
	</div>
	<div id="form-login-password" class="tr">
		<div class="fl">
			<label for="modlgn_passwd"><?php echo JText::_('Password') ?></label>
		</div>
		<div class="fr">
			<input id="modlgn_passwd" type="password" name="passwd" title="<?php echo JText::_('Password') ?>" class="inputbox" size="18" alt="password" />
		</div>
	</div>
	<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	<div id="form-login-remember" class="tr">
		<div class="fl">
			<input id="modlgn_remember" type="checkbox" name="remember" title="<?php echo JText::_('Remember me') ?>" value="yes" class="checkbox" alt="Remember Me" />
		</div>
		<div class="fl">
			<label for="modlgn_remember"><?php echo JText::_('Remember me') ?></label>
		</div>
	</div>
	<?php endif; ?>

	<div id="form-login-button" class="clr tr">
		<div class="fl">
			<button type="submit" title="<?php echo JText::_('LOGIN'); ?>">
				<?php echo JHTML::_('image', "templates/$template/images/icons/silk/lock_open.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('LOGIN'); ?></button>
		</div>
		<?php $usersConfig = &JComponentHelper::getParams( 'com_users' );
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<div class="fr">
			<a id="form-login-register" class="button" href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>" title="<?php echo JText::_('REGISTER'); ?>">
				<?php echo JHTML::_('image', "templates/$template/images/icons/silk/user_add.png", JText::_('Icon'), array('class' => 'png24')) . str_replace('Create an account', 'Register', JText::_('REGISTER')); ?></a>
		</div>
		<?php endif; ?>
	</div>

	<ul id="form-login-forgot">
		<li class="forgot-password">
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>" title="<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?>">
				<?php echo JText::_('Lost Password?'); ?></a>
		</li>
		<li class="forgot-username">
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>" title="<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?>">
				<?php echo JText::_('Forgot Username?'); ?></a>
		</li>
	</ul>

	<?php if (JPluginHelper::isEnabled('authentication', 'openid')) : // support OpenID authentication ?>
	<ul id="openid-support">
		<li class="openid-switcher">
			<a id="openid-switcher" href="javascript:void(0)"><!-- Let JavaScript fill text --></a>
		</li>
		<li class="openid-link">
			<a href="http://openid.net/" title="<?php echo JText::_('What is OpenID?'); ?>" target="_blank">
				<?php echo JText::_('What is OpenID?'); ?></a>
		</li>
	</ul>
	<?php endif; ?>
	</fieldset>

	<?php if ($params->get('posttext') != '') : ?>
	<p class="intro post"><?php echo $params->get('posttext'); ?></p>
	<?php endif; ?>
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php endif; ?>
