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

$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';
?>
<div id="jezComOutput" class="user_form<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif; ?>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
function submitbutton(pressbutton) {
	var form = document.userform;
	var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");

	if (pressbutton == 'cancel') {
		form.task.value = 'cancel';
		form.submit();
		return;
	}

	// do field validation
	if (form.name.value == "")
		alert( "<?php echo JText::_( 'Please enter your name.', true );?>" );
	else if (form.email.value == "")
		alert( "<?php echo JText::_( 'Please enter a valid e-mail address.', true );?>" );
	else if (((form.password.value != "") || (form.password2.value != "")) && (form.password.value != form.password2.value))
		alert( "<?php echo JText::_( 'REGWARN_VPASS2', true );?>" );
	else if (r.exec(form.password.value))
		alert( "<?php printf( JText::_( 'VALID_AZ09', true ), JText::_( 'Password', true ), 4 );?>" );
	else
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
<form action="<?php echo JRoute::_( 'index.php' ); ?>" method="post" name="userform" autocomplete="off">
<fieldset>
<legend><?php echo JText::_('Account'); ?></legend>
<div class="tr">
	<div class="fl tc w30p"><label for="username"><?php echo JText::_( 'User Name' ); ?></label></div>
	<div class="fl w70p"><span><?php echo $this->user->get('username');?></span></div>
</div>
<div class="tr">
	<div class="fl tc w30p"><label for="name"><?php echo JText::_( 'Your Name' ); ?></label></div>
	<div class="fl w70p">
		<input title="<?php echo JText::_( 'Your Name' ); ?>" type="text" class="inputbox" id="name" name="name" value="<?php echo $this->escape($this->user->get('name'));?>" size="50" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p"><label for="email"><?php echo JText::_( 'email' ); ?></label></div>
	<div class="fl w70p">
		<input title="<?php echo JText::_( 'email' ); ?>" type="text" class="inputbox" id="email" name="email" value="<?php echo $this->escape($this->user->get('email'));?>" size="50" />
	</div>
</div>
<?php if($this->user->get('password')) : ?>
<div class="tr">
	<div class="fl tc w30p"><label for="password"><?php echo JText::_( 'Password' ); ?></label></div>
	<div class="fl w70p">
		<input title="<?php echo JText::_( 'Password' ); ?>" type="password" class="inputbox" id="password" name="password" value="" size="50" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p"><label for="password2"><?php echo JText::_( 'Verify Password' ); ?></label></div>
	<div class="fl w70p">
		<input title="<?php echo JText::_( 'Verify Password' ); ?>" type="password" class="inputbox" id="password2" name="password2" size="50" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">&nbsp;</div>
	<div class="fl w70p">
		<button type="button" title="<?php echo JText::_('Save'); ?>" onclick="submitbutton('save');" class="positive fl">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/key.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Save'); ?></button>
		<button type="button" title="<?php echo JText::_('Cancel') ?>" onclick="submitbutton('cancel');" class="negative fl">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/cross.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Cancel'); ?></button>
	</div>
</div>
<?php endif; ?>
</fieldset>
<?php if (isset($this->params)) : ?>
<fieldset>
<legend><?php echo JText::_('Options'); ?></legend>
<?php echo preg_replace(
		array(
			'/<table[^>]+>\n/i',
			'/<tr>\n<td\s+.*class="paramlist_key">(.+)<\/td>\n<td\s+.*class="paramlist_value">(.+)<\/td>\n<\/tr>\n/i',
			'/<\/table>/i'
		),
		array(
			'',
			"<div class=\"tr\">\n\t<div class=\"fl tc w30p\">\\1</div>\n\t<div class=\"fl w70p\">\\2</div>\n</div>\n",
			"<div class=\"tr\">\n\t<div class=\"fl tc w30p\">&nbsp;</div>\n\t<div class=\"fl w70p\"><button title=\"".JText::_('Save')."\" type=\"button\" onclick=\"submitbutton('save');\" class=\"positive fl\">".JHTML::_('image', "templates/$template/images/icons/silk/user_edit.png", JText::_('Icon'), array('class' => 'png24')).JText::_('Save')."</button><button title=\"".JText::_('Cancel')."\" type=\"button\" onclick=\"submitbutton('cancel');\" class=\"negative fl\">".JHTML::_('image', "templates/$template/images/icons/silk/cross.png", JText::_('Icon'), array('class' => 'png24')).JText::_('Cancel')."</button></div>\n</div>\n"
		),
		$this->params->render( 'params' )
); ?>
</fieldset>
<?php endif; ?>
<input type="hidden" name="username" value="<?php echo $this->user->get('username');?>" />
<input type="hidden" name="id" value="<?php echo $this->user->get('id');?>" />
<input type="hidden" name="gid" value="<?php echo $this->user->get('gid');?>" />
<input type="hidden" name="option" value="com_user" />
<input type="hidden" name="task" value="save" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
