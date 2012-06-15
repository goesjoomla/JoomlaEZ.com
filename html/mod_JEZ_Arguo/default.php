<?php
/*
 * JoomlaEZ.com's Slideshow Joomla Module
 *
 * @package		View Render
 * @version		1.1.0
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
 * @license		Commercial Proprietary
 *
 * Please visit http://joomlaez.com/ for more information
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// load default layout stylesheet
modJezNewsflasherHelper::loadStylesheets();

// detect print action
if ( !( JRequest::getCmd('print') ) ) : ?>
<script type="text/javascript" language="javascript"><!-- // --><![CDATA[
window.addEvent("<?php echo $params->get('initializeOn'); ?>", function() {
	new jezTextShow($("<?php echo $params->get('instanceID'); ?>"), {<?php echo (isset($config) && is_array($config) ? "\n\t\t".implode(",\n\t\t", $config)."\n\t" : ''); ?>});

	// keep the typography`s vertical rhythm
	jezKeepVertRhythm(document.getElementById("<?php echo $params->get('instanceID'); ?>"));
});
// ]]></script>
<?php echo '
<noscript>
<h6>Warning</h6>
<p>This website uses <a href="http://joomlaez.com/extensions/slideshow-joomla-module-to-create-web-20-joomla-slideshow.html" title="JoomlaEZ.com\'s Slideshow Joomla Module"><em>JoomlaEZ.com\'s Slideshow Joomla Module, a Joomla module developed by JoomlaEZ.com</em></a>, to create slideshow for its content.</p>
<p><em>JoomlaEZ.com\'s Slideshow Joomla Module</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox 3</a></em> is recommended, or enable JavaScript support in your browser for best experience with slideshow created by <em>JoomlaEZ.com\'s Slideshow Joomla Module</em>.</p>
<p>Visit <em><a href="http://joomlaez.com/" title="Download slideshow Joomla module to bring modern web 2.0 slideshow layouts to your Joomla based site">JoomlaEZ.com to download slideshow Joomla module which brings modern web 2.0 slideshow layouts to your Joomla based site</a></em>.</p>
</noscript>
';
endif; ?>
<ul id="<?php echo $params->get('instanceID', ''); ?>">
	<?php $i = 1; foreach ($list as $item) { ?>
	<li id="<?php echo $params->get('instanceID', ''); ?>:<?php echo $i; ?>" class="jezTextShowItem">
		<?php modJezNewsflasherHelper::renderItem($item, $params, $access); $i++; ?>
	</li>
	<?php } ?>
</ul>
