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

$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';
?>
<div id="jezComOutput<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" class="wrapper tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif; ?>
<script type="text/javascript" language="javascript"><!-- // --><![CDATA[
function iFrameHeight() {
	var h = 0;
	if ( !document.all )
		h = document.getElementById('blockrandom').contentDocument.height + 60;
	else if ( document.all )
		h = document.frames('blockrandom').document.body.scrollHeight + 20;

	if (!h) // fix for Opera
		h = document.getElementById('blockrandom').height;

	if (h > 0) {
		var vR = jezGetVertRhythm(document.getElementById("jezComOutput<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"));
		if ( !document.all )
			document.getElementById('blockrandom').style.height = (((parseInt(h / vR.lineHeight) + 1) * vR.lineHeight) / vR.fontSize) + 'em';
		else if ( document.all )
			document.all.blockrandom.style.height = (((parseInt(h / vR.lineHeight) + 1) * vR.lineHeight) / vR.fontSize) + 'em';
	}
}
// ]]></script>
<noscript>
<h6>Warning</h6>
<p>This website uses <a href="http://www.joomlaez.com/" title="JEZ Thema"><em>JEZ Thema</em></a> as the base for its Joomla template.</p>
<p><em>JEZ Thema</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox</a></em> is recommended, or enable JavaScript support in your browser for best experience with Joomla template created based on <em>JEZ Thema</em>.</p>
<p>Visit <em><a href="http://joomlaez.com/" title="Download Joomla themes and Joomla modules">JoomlaEZ.com to JoomlaEZ.com to browse and download professional Joomla themes and Joomla modules for making your Joomla site more attractive</a></em>.</p>
</noscript>
<iframe <?php echo $this->wrapper->load; ?>
	id="blockrandom"
	name="iframe"
	src="<?php echo $this->wrapper->url; ?>"
	width="<?php echo $this->params->get( 'width' ); ?>"
	height="<?php echo $this->params->get( 'height' ); ?>"
	scrolling="<?php echo $this->params->get( 'scrolling' ); ?>"
	align="top"
	frameborder="0"
	class="wrapper">
	<?php echo JText::_( 'NO_IFRAMES' ); ?>
</iframe>
</div>
