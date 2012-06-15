<?php
/*
 * JEZ Arguo Joomla! 1.5 Module
 *
 * @package		View Render
 * @version		1.1.4
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008 JoomlaEZ.com. All rights reserved
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
});
// ]]></script>
<?php echo '
<noscript>
<h6>Warning</h6>
<p>This website uses <a href="http://www.joomlaez.com/"><em>JEZ Arguo - Slideshow Joomla module developed by JoomlaEZ.com</em></a>, to create slideshow for its content.</p>
<p><em>JEZ Arguo</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox</a></em> is recommended, or enable JavaScript support in your browser for best experience with slideshow created by <em>JEZ Arguo</em>.</p>
<p>Visit <em><a href="http://www.joomlaez.com/">JoomlaEZ.com to download Joomla slideshow module which brings modern web 2.0 slideshow layouts to your Joomla based site</a></em>.</p>
</noscript>
';
endif; ?>
<ul id="<?php echo $params->get('instanceID', ''); ?>">
	<?php $i = 1; foreach ($list AS $source => $items) {
		$params->set('source', $source);
		foreach ($items AS $item) { ?>
	<li id="<?php echo $params->get('instanceID', ''); ?>:<?php echo $i; ?>" class="jezTextShowItem">
		<?php modJezNewsflasherHelper::renderItem($item, $params, $access); $i++; ?>
	</li>
		<?php }
	} ?>
</ul>
<br style="clear:both;line-height:0" />
