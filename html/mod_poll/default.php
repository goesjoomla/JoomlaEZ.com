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
?>
<form action="index.php" method="post" class="mod_poll<?php echo $params->get( 'moduleclass_sfx' ); ?>">
<h4><?php echo $poll->title; ?></h4>
<fieldset>
<?php for ($i = 0, $n = count($options); $i < $n; $i++) : ?>
<div class="tr row<?php echo ($i % 2); ?>">
	<div class="fl tc w20p tac">
		<input type="radio" name="voteid" id="voteid<?php echo $options[$i]->id;?>" value="<?php echo $options[$i]->id;?>" title="<?php echo $options[$i]->text; ?>" />
	</div>
	<div class="fl w80p">
		<label for="voteid<?php echo $options[$i]->id;?>"><?php echo $options[$i]->text; ?></label>
	</div>
</div>
<?php endfor; ?>
<div id="form-poll-button" class="tr">
	<div class="fl">
		<button type="submit" title="<?php echo JText::_('Vote'); ?>">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/pencil.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Vote'); ?></button>
	</div>
	<div class="fr">
		<a class="button" href="<?php echo JRoute::_('index.php?option=com_poll&id='.$poll->slug.$itemid); ?>" title="<?php echo JText::_('Results'); ?>">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/chart_pie.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Results'); ?></a>
	</div>
</div>
</fieldset>
<input type="hidden" name="option" value="com_poll" />
<input type="hidden" name="task" value="vote" />
<input type="hidden" name="id" value="<?php echo $poll->id;?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
