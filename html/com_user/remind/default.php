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

$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';

// get the active template
$template = basename(dirname(dirname(dirname(dirname(__FILE__)))));
?>
<div id="jezComOutput" class="user_remind<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif; ?>
<form action="<?php echo JRoute::_( 'index.php?option=com_user&task=remindusername' ); ?>" method="post" class="josForm form-validate">
<p class="intro"><?php echo JText::_('REMIND_USERNAME_DESCRIPTION'); ?></p>
<fieldset>
<div class="tr">
	<div class="fl tc w30p">
		<label for="email" class="hasTip" title="<?php echo JText::_('REMIND_USERNAME_EMAIL_TIP_TITLE'); ?>::<?php echo JText::_('REMIND_USERNAME_EMAIL_TIP_TEXT'); ?>"><?php echo JText::_('Email Address'); ?></label>
	</div>
	<div class="fl w70p">
		<input id="email" name="email" type="text" title="<?php echo JText::_('Email Address'); ?>" size="30" class="inputbox required validate-email" />
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
