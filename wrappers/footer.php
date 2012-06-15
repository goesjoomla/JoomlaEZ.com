<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Wrappers :: footer
*
* @package		JEZ Thema
* @version		1.1.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://joomlaez.com/ for more information
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// get the active template
$template = basename(dirname(dirname(__FILE__)));

if ($params->get('_modFooter')) : ?>
<div id="modFooter" class="tac">
	<jdoc:include type="modules" name="footer<?php echo $params->get('_footerChrome'); ?>" />
</div>
<?php endif;

if ($params->get('_modDebug')) : ?>
<hr />
<div id="modDebug">
	<jdoc:include type="modules" name="debug<?php echo $params->get('_debugChrome'); ?>" />
</div>
<?php endif;

if ($params->get('ie6warning')) : ?>
<!--[if lte IE 6]><div id="ie6warning">
	<div class="container tac">
		<?php echo JHTML::_('image', "templates/$template/images/icons/silk/error.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('You are using an obsolete browser. Please consider updating your browser to latest version for better experience with our site.'); ?>
	</div>
</div><![endif]-->
<?php endif;

echo '
<noscript>
<a href="http://www.joomlaez.com/" title="Based on JEZ Thema Joomla template skeleton to create Joomla template">Based on JEZ Thema Joomla template skeleton to create Joomla template</a>
</noscript>
'; ?>
