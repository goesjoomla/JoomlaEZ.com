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
?>
<script type="text/javascript" language="javascript"><!-- // --><![CDATA[
function insertPagebreak(editor) {
	// Get the pagebreak title
	var title = document.getElementById("title").value;
	if (title != '')
		title = "title=\""+title+"\" ";

	// Get the pagebreak toc alias -- not inserting for now
	// don't know which attribute to use...
	var alt = document.getElementById("alt").value;
	if (alt != '')
		alt = "alt=\""+alt+"\" ";

	var tag = "<hr class=\"system-pagebreak\" "+title+" "+alt+"/>";

	window.parent.jInsertEditorText(tag, '<?php echo preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', JRequest::getVar('e_name') ); ?>');
	window.parent.document.getElementById('sbox-window').close();
	return false;
}
// ]]></script>
<noscript>
<h6>Warning</h6>
<p>This website uses <a href="http://www.joomlaez.com/" title="JEZ Thema"><em>JEZ Thema</em></a> as the base for its Joomla template.</p>
<p><em>JEZ Thema</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox</a></em> is recommended, or enable JavaScript support in your browser for best experience with Joomla template created based on <em>JEZ Thema</em>.</p>
<p>Visit <em><a href="http://joomlaez.com/" title="Download Joomla themes and Joomla modules">JoomlaEZ.com to JoomlaEZ.com to browse and download professional Joomla themes and Joomla modules for making your Joomla site more attractive</a></em>.</p>
</noscript>
<form class="content_pagebreak">
<fieldset>
<div class="tr gr2">
	<div class="fl tc tar">
		<label for="title"><?php echo JText::_( 'PGB PAGE TITLE' ); ?></label>
	</div>
	<div class="fl tal">
		<input type="text" class="inputbox" id="title" name="title" title="<?php echo JText::_( 'PGB PAGE TITLE' ); ?>" size="20" />
	</div>
</div>
<div class="tr gr2">
	<div class="fl tc tar">
		<label for="alias"><?php echo JText::_( 'PGB TOC ALIAS PROMPT' ); ?></label>
	</div>
	<div class="fl tal">
		<input type="text" class="inputbox" id="alt" name="alt" title="<?php echo JText::_( 'PGB TOC ALIAS PROMPT' ); ?>" size="20" />
	</div>
</div>
<div class="tr gr2">
	<div class="fl tc tar">&nbsp;</div>
	<div class="fl tal">
		<button onclick="insertPagebreak();" title="<?php echo JText::_( 'PGB INS PAGEBRK' ); ?>"><?php echo JText::_( 'PGB INS PAGEBRK' ); ?></button>
	</div>
</div>
</fieldset>
</form>
