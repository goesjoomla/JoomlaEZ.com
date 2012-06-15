<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Wrappers :: header
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
?>
<div class="container wrapper tr">
<?php if ($params->get('_modLogo') || $params->get('logo') != '') : ?>
	<div id="jezLogo" class="fl">
		<?php if ($params->get('_modLogo')) : ?>
		<jdoc:include type="modules" name="logo<?php echo $params->get('_logoChrome'); ?>" />
		<?php elseif ($params->get('logo') != '') : ?>
		<a href="<?php echo $params->get('_baseurl'); ?>/" title="<?php echo $params->get('logoAlt'); ?>">
			<img src="<?php echo $params->get('_baseurl').'/'.$params->get('logo'); ?>" alt="<?php echo $params->get('logoAlt'); ?>" class="logo png24" /></a>
		<?php endif; ?>
	</div>
<?php endif;

if ($params->get('_navCount')) : ?>
	<div id="jezNav" class="fr tr">
		<?php if ($params->get('_modUser3')) : ?>
		<div id="modUser3" class="fl">
			<jdoc:include type="modules" name="user3<?php echo $params->get('_user3Chrome'); ?>" />
		</div>
		<?php endif;

		if ( $params->get('_modTop') || $params->get('fontResizer') ) : ?>
		<div id="modTop" class="fr">
			<?php if ($params->get('_modTop')) : ?>
			<jdoc:include type="modules" name="top<?php echo $params->get('_topChrome'); ?>" />
			<?php elseif ($params->get('fontResizer')) : ?>
			<script type="text/javascript" language="javascript"><!-- // --><![CDATA[
				document.write('<a href="javascript:void(0)" onclick="jezChangeFontSize(5);" id="increaseFontSize" title="<?php echo JText::_('Increase Font Size'); ?>">A</a>');
				document.write('<a href="javascript:void(0)" onclick="jezRevertFontSize();" id="resetFontSize" title="<?php echo JText::_('Reset Font Size'); ?>">A</a>');
				document.write('<a href="javascript:void(0)" onclick="jezChangeFontSize(-5);" id="decreaseFontSize" title="<?php echo JText::_('Decrease Font Size'); ?>">A</a>');
			// ]]></script>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
<?php endif; ?>
</div>