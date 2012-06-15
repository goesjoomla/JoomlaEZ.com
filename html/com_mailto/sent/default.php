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
?>
<div class="mailto_sent">
	<div class="success">
		<?php echo JText::_('EMAIL_SENT'); ?>
		<button onclick="if (window.parent && window.parent.document.getElementById('sbox-window')) window.parent.document.getElementById('sbox-window').close(); else window.close();" title="<?php echo JText::_('CLOSE_WINDOW'); ?>">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/cross.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('CLOSE_WINDOW'); ?></button>
	</div>
</div>
