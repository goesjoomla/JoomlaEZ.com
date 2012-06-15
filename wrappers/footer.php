<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Wrappers :: footer
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
<?php if ($params->get('_modFooter')) : ?>
	<div id="modFooter" class="fl">
		<jdoc:include type="modules" name="footer<?php echo $params->get('_footerChrome'); ?>" />
	</div>
<?php endif;

if ($params->get('_modSyndicate')) : ?>
	<div id="modSyndicate" class="fr">
		<jdoc:include type="modules" name="syndicate<?php echo $params->get('_syndicateChrome'); ?>" />
	</div>
<?php endif; ?>
</div>

<?php if ($params->get('ie6Warning')) : ?>
<!--[if lte IE 6]><div id="ie6Warning">
	<div class="container wrapper tac">
		<?php echo JHTML::_('image', "templates/$template/images/icons/silk/error.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('You are using an obsolete browser. Please consider updating your browser to latest version for better experience with our site.'); ?>
	</div>
</div><![endif]-->
<?php endif;

echo '
<noscript>
<a href="http://www.joomlaez.com/" title="Joomla theme JEZ Rego">JEZ Rego - Joomla theme by JoomlaEZ.com</a>
</noscript>
'; ?>