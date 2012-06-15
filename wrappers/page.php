<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Wrappers :: header & body
*
* @package		JEZ Rego
* @version		1.5.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://www.joomlaez.com/ for more information
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!(isset($_COOKIE['jezTplName']) && isset($_COOKIE['jezTplDir']))) {
	// get template directory
	$tpl_dir = dirname(dirname(__FILE__));
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

if ($params->get('_standardPage')) : ?>
<div class="container wrapper">
<?php endif;

if ($params->get('_colsCount'))
	jezWrapper($params->get('wrapperContent'), 'content', $params, 'jezContent', '', true, $params->get('_colsCount'));

if (!defined('RAW_OUTPUT') && $params->get('_blksCount'))
	jezWrapper($params->get('wrapperTop'), 'top', $params, 'jezTop', 'container wrapper', true, $params->get('_blksCount'));

if (!defined('RAW_OUTPUT') && $params->get('_modBottom')) : ?>
	<div id="modBottom" class="tac">
		<jdoc:include type="modules" name="bottom<?php echo $params->get('_bottomChrome'); ?>" />
	</div>
<?php endif;

if ($params->get('_standardPage')) :
	if ($params->get('_modTool')) : ?>
	<div id="modTool">
		<jdoc:include type="modules" name="tool<?php echo $params->get('_toolChrome'); ?>" />
	</div>
	<?php elseif ($params->get('switchThemeViaUrl') && $params->get('showThemeSwitcher')) :
		$activeTheme = isset($_COOKIE['tpl_theme']) ? $_COOKIE['tpl_theme'] : 'default';

		$document = &JFactory::getDocument();
		$document->addScriptDeclaration('
function switchTheme(theme) {
	var thisLoc = location.href.replace(/(\?|&)tpl_theme=([a-zA-Z0-9_\-\.]+)/gi, "");
	location.href = thisLoc + (thisLoc.indexOf("?") > -1 ? "&" : "?") + "tpl_theme=" + theme;
}
'); ?>
	<div id="jezThemeSwitcher">
		<a id="<?php echo $activeTheme == 'default' ? 'activeTheme' : 'theme-default'; ?>" href="javascript:void(0)" rel="nofollow" onclick="switchTheme('default')" class="black" title="<?php echo $activeTheme == 'default' ? JText::_('Current Color Scheme') : JText::_('Black Color Scheme'); ?>"></a>
		<a id="<?php echo $activeTheme == 'blue' ? 'activeTheme' : 'theme-blue'; ?>" href="javascript:void(0)" rel="nofollow" onclick="switchTheme('blue')" class="blue" title="<?php echo $activeTheme == 'blue' ? JText::_('Current Color Scheme') : JText::_('Blue Color Scheme'); ?>"></a>
		<a id="<?php echo $activeTheme == 'green' ? 'activeTheme' : 'theme-green'; ?>" href="javascript:void(0)" rel="nofollow" onclick="switchTheme('green')" class="green" title="<?php echo $activeTheme == 'green' ? JText::_('Current Color Scheme') : JText::_('Green Color Scheme'); ?>"></a>
		<a id="<?php echo $activeTheme == 'leaf' ? 'activeTheme' : 'theme-leaf'; ?>" href="javascript:void(0)" rel="nofollow" onclick="switchTheme('leaf')" class="leaf" title="<?php echo $activeTheme == 'leaf' ? JText::_('Current Color Scheme') : JText::_('Leaf Color Scheme'); ?>"></a>
		<a id="<?php echo $activeTheme == 'orange' ? 'activeTheme' : 'theme-orange'; ?>" href="javascript:void(0)" rel="nofollow" onclick="switchTheme('orange')" class="orange" title="<?php echo $activeTheme == 'orange' ? JText::_('Current Color Scheme') : JText::_('Orange Color Scheme'); ?>"></a>
		<a id="<?php echo $activeTheme == 'violet' ? 'activeTheme' : 'theme-violet'; ?>" href="javascript:void(0)" rel="nofollow" onclick="switchTheme('violet')" class="violet" title="<?php echo $activeTheme == 'violet' ? JText::_('Current Color Scheme') : JText::_('Violet Color Scheme'); ?>"></a>
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>