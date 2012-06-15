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

defined('_JEXEC') or die;

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

$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';
?>
<div id="jezComOutput" class="weblinks_form<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif; ?>
<script type="text/javascript" language="javascript"><!-- // --><![CDATA[
function submitbutton(pressbutton) {
	var form = document.adminForm;

	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (document.getElementById('jformtitle').value == "")
		alert( "<?php echo JText::_( 'Weblink item must have a title', true ); ?>" );
	else if (document.getElementById('jformcatid').value < 1)
		alert( "<?php echo JText::_( 'You must select a category.', true ); ?>" );
	else if (document.getElementById('jformurl').value == "")
		alert( "<?php echo JText::_( 'You must have a url.', true ); ?>" );
	else
		submitform( pressbutton );
}
// ]]></script>
<noscript>
<h6>Warning</h6>
<p>This website uses <a href="http://www.joomlaez.com/" title="Joomla Theme JEZ Rego"><em>Joomla Theme JEZ Rego</em></a>.</p>
<p><em>JEZ Rego</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox</a></em> is recommended, or enable JavaScript support in your browser for best experience with Joomla theme <em>JEZ Rego</em>.</p>
<p>Visit <em><a href="http://www.joomlaez.com/" title="Download Joomla themes and Joomla modules">JoomlaEZ.com to browse and download professional Joomla themes and Joomla modules for making your Joomla site more attractive</a></em>.</p>
</noscript>
<form action="<?php echo $this->action; ?>" method="post" name="adminForm" id="adminForm">
<fieldset>
<div class="tr">
	<div class="fl tc w30p"><label for="jformtitle"><?php echo JText::_( 'Name' ); ?></label></div>
	<div class="fl w70p">
		<input title="<?php echo JText::_( 'Name' ); ?>" type="text" class="inputbox" id="jformtitle" name="jform[title]" size="50" maxlength="250" value="<?php echo $this->escape($this->weblink->title);?>" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p"><label for="jformcatid"><?php echo JText::_( 'Category' ); ?></label></div>
	<div class="fl w70p">
		<?php echo $this->lists['catid']; ?>
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p"><label for="jformurl"><?php echo JText::_( 'URL' ); ?></label></div>
	<div class="fl w70p">
		<input title="<?php echo JText::_( 'URL' ); ?>" type="text" class="inputbox" id="jformurl" name="jform[url]" value="<?php echo $this->escape($this->weblink->url); ?>" size="50" maxlength="250" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p"><label for="jformpublished"><?php echo JText::_( 'Published' ); ?></label></div>
	<div class="fl w70p">
		<?php echo $this->lists['published']; ?>
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p"><label for="jformdescription"><?php echo JText::_( 'Description' ); ?></label></div>
	<div class="fl w70p">
		<textarea title="<?php echo JText::_( 'Description' ); ?>" cols="30" rows="6" id="jformdescription" name="jform[description]"><?php echo $this->escape( $this->weblink->description);?></textarea>
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p"><label for="jformordering"><?php echo JText::_( 'Ordering' ); ?></label></div>
	<div class="fl w70p">
		<?php echo $this->lists['ordering']; ?>
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">&nbsp;</div>
	<div class="fl w70p">
		<button type="button" title="<?php echo JText::_('Save') ?>" onclick="submitbutton('save')" class="positive fl">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/tick.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Save') ?></button>
		<button type="button" title="<?php echo JText::_('Cancel') ?>" onclick="submitbutton('cancel')" class="negative fl">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/cross.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Cancel') ?></button>
	</div>
</div>
</fieldset>
<input type="hidden" name="jform[id]" value="<?php echo $this->weblink->id; ?>" />
<input type="hidden" name="jform[ordering]" value="<?php echo $this->weblink->ordering; ?>" />
<input type="hidden" name="jform[approved]" value="<?php echo $this->weblink->approved; ?>" />
<input type="hidden" name="option" value="com_weblinks" />
<input type="hidden" name="controller" value="weblink" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
