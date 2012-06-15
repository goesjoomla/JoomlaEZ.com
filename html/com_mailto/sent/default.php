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
?>
<div class="mailto_sent">
	<div class="success">
		<?php echo JText::_('EMAIL_SENT'); ?>
		<button onclick="if (window.parent && window.parent.document.getElementById('sbox-window')) window.parent.document.getElementById('sbox-window').close(); else window.close();" title="<?php echo JText::_('CLOSE_WINDOW'); ?>">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/cross.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('CLOSE_WINDOW'); ?></button>
	</div>
</div>
