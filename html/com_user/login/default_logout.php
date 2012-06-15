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

$titleStyle	= $this->params->get('show_logout_title') ? '' : ' hide';

// get the active template
$template = basename(dirname(dirname(dirname(dirname(__FILE__)))));
?>
<div id="jezComOutput" class="user_logout<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get( 'header_logout' ) != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get( 'header_logout' )); ?></h2>
<?php endif; ?>
<form action="<?php echo JRoute::_( 'index.php' ); ?>" method="post">
<?php if ($this->image || $this->params->get('description_logout')) : ?>
<p class="info clearfix">
	<?php
	if ($this->image)
		echo preg_replace('/align="([^"]+)"/i', 'class="\\1"', $this->image);

	if ($this->params->get('description_logout'))
		echo $this->escape($this->params->get('description_logout_text'));
	?>
</p>
<?php endif; ?>
<div class="tac">
	<button type="submit" title="<?php echo JText::_( 'Logout' ); ?>" class="negative">
		<?php echo JHTML::_('image', "templates/$template/images/icons/silk/lock.png", JText::_('Icon'), array('class' => 'png24')) . JText::_( 'Logout' ); ?></button>
</div>
<input type="hidden" name="option" value="com_user" />
<input type="hidden" name="task" value="logout" />
<input type="hidden" name="return" value="<?php echo $this->return; ?>" />
</form>
</div>
