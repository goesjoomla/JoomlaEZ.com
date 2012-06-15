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

$config =& JFactory::getConfig();
$publish_up =& JFactory::getDate($this->article->publish_up);
$publish_down =& JFactory::getDate($this->article->publish_down);
$publish_up->setOffset($config->getValue('config.offset'));
$publish_down->setOffset($config->getValue('config.offset'));
$publish_up = $publish_up->toFormat();
$publish_down = $publish_down->toFormat();

$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';
?>
<div id="jezComOutput" class="content_form<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif; ?>
<script type="text/javascript" language="javascript"><!-- // --><![CDATA[
function setgood() {
	// TODO: Put setGood back
	return true;
}
var sectioncategories = new Array;
<?php $i = 0;
foreach ($this->lists['sectioncategories'] as $k=>$items) {
	foreach ($items as $v)
		echo "sectioncategories[".$i++."] = new Array( '$k','".addslashes( $v->id )."','".addslashes( $v->title )."' );\n\t\t";
} ?>
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	try {
		form.onsubmit();
	} catch(e) {
		alert(e);
	}

	// do field validation
	var text = <?php echo $this->editor->getContent( 'text' ); ?>
	if (form.title.value == '')
		return alert ( "<?php echo JText::_( 'Article must have a title', true ); ; ?>" );
	else if (text == '')
		return alert ( "<?php echo JText::_( 'Article must have some text', true ); ; ?>");
	else if (parseInt('<?php echo $this->article->sectionid;?>')) {
		// for articles
		if (form.catid && getSelectedValue('adminForm','catid') < 1)
			return alert ( "<?php echo JText::_( 'Please select a category', true ); ; ?>" );
	}
	<?php echo $this->editor->save( 'text' ); ?>
	submitform(pressbutton);
}
// ]]></script>
<noscript>
<h6>Warning</h6>
<p>This website uses <a href="http://www.joomlaez.com/" title="Joomla Theme JEZ Rego"><em>Joomla Theme JEZ Rego</em></a>.</p>
<p><em>JEZ Rego</em> will not fully functional because your browser either does not support JavaScript or has JavaScript disabled.</p>
<p>Please either switch to a modern web browser, <em><a href="http://www.mozilla.com">FireFox</a></em> is recommended, or enable JavaScript support in your browser for best experience with Joomla theme <em>JEZ Rego</em>.</p>
<p>Visit <em><a href="http://www.joomlaez.com/" title="Download Joomla themes and Joomla modules">JoomlaEZ.com to browse and download professional Joomla themes and Joomla modules for making your Joomla site more attractive</a></em>.</p>
</noscript>
<form action="<?php echo $this->action ; ?>" method="post" name="adminForm" onSubmit="setgood();">
<fieldset>
<legend><?php echo JText::_('Editor'); ?></legend>
<div class="tr">
	<div class="fl tc w10p">
		<label for="title"><?php echo JText::_( 'Title' ); ?>:</label>
	</div>
	<div class="fl tc w50p">
		<input type="text" class="inputbox" id="title" name="title" title="<?php echo JText::_( 'Title' ); ?>" size="50" maxlength="100" value="<?php echo $this->escape($this->article->title); ; ?>" />
		<input type="hidden" id="alias" name="alias" value="<?php echo $this->escape($this->article->alias); ; ?>" />
	</div>
	<div class="fr w40p">
		<button type="button" title="<?php echo JText::_('Cancel') ?>" onclick="submitbutton('cancel')" class="negative fr">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/cross.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Cancel'); ?></button>
		<button type="button" title="<?php echo JText::_('Save') ?>" onclick="submitbutton('save')" class="positive fr">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/tick.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Save') ?></button>
	</div>
</div>
<?php
echo $this->editor->display('text', $this->article->text, '100%', '414', '70', '15');
?>
</fieldset>

<fieldset>
<legend><?php echo JText::_('Publishing'); ?></legend>
<div class="tr">
	<div class="fl tc w30p">
		<label for="sectionid"><?php echo JText::_( 'Section' ); ?>:</label>
	</div>
	<div class="fl w70p">
		<?php echo $this->lists['sectionid']; ?>
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">
		<label for="catid"><?php echo JText::_( 'Category' ); ?>:</label>
	</div>
	<div class="fl w70p">
		<?php echo $this->lists['catid']; ?>
	</div>
</div>
<?php if ($this->user->authorize('com_content', 'publish', 'content', 'all')) : ?>
<div class="tr">
	<div class="fl tc w30p">
		<label for="state"><?php echo JText::_( 'Published' ); ?>:</label>
	</div>
	<div class="fl w70p">
		<?php echo $this->lists['state']; ?>
	</div>
</div>
<?php endif; ?>
<div class="tr">
	<div class="fl tc w30p">
		<label for="frontpage"><?php echo JText::_( 'Show on Front Page' ); ?>:</label>
	</div>
	<div class="fl w70p">
		<?php echo $this->lists['frontpage']; ?>
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">
		<label for="created_by_alias"><?php echo JText::_( 'Author Alias' ); ?>:</label>
	</div>
	<div class="fl w70p">
		<input type="text" class="inputbox" id="created_by_alias" name="created_by_alias" title="<?php echo JText::_( 'Author Alias' ); ?>" size="50" maxlength="100" value="<?php echo $this->escape($this->article->created_by_alias); ?>" />
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">
		<label for="publish_up"><?php echo JText::_( 'Start Publishing' ); ?>:</label>
	</div>
	<div class="fl w70p">
		<?php echo JHTML::_('calendar', $publish_up, 'publish_up', 'publish_up', '%Y-%m-%d %H:%M:%S', array('title'=>JText::_( 'Start Publishing' ), 'size'=>'50', 'maxlength'=>'19')); ?>
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">
		<label for="publish_down"><?php echo JText::_( 'Finish Publishing' ); ?>:</label>
	</div>
	<div class="fl w70p">
		<?php echo JHTML::_('calendar', $publish_down, 'publish_down', 'publish_down', '%Y-%m-%d %H:%M:%S', array('title'=>JText::_( 'Finish Publishing' ), 'size'=>'50', 'maxlength'=>'19')); ?>
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">
		<label for="access"><?php echo JText::_( 'Access Level' ); ?>:</label>
	</div>
	<div class="fl w70p">
		<?php echo $this->lists['access']; ?>
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">
		<label for="ordering"><?php echo JText::_( 'Ordering' ); ?>:</label>
	</div>
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
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/cross.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Cancel'); ?></button>
	</div>
</div>
</fieldset>

<fieldset>
<legend><?php echo JText::_('Metadata'); ?></legend>
<div class="tr">
	<div class="fl tc w30p">
		<label for="metadesc"><?php echo JText::_( 'Description' ); ?>:</label>
	</div>
	<div class="fl w70p">
		<textarea rows="5" cols="50" class="longer" id="metadesc" name="metadesc" title="<?php echo JText::_( 'Description' ); ?>"><?php echo str_replace('&','&amp;',$this->article->metadesc); ?></textarea>
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">
		<label for="metakey"><?php echo JText::_( 'Keywords' ); ?>:</label>
	</div>
	<div class="fl w70p">
		<textarea rows="5" cols="50" class="shorter" id="metakey" name="metakey" title="<?php echo JText::_( 'Keywords' ); ?>"><?php echo str_replace('&','&amp;',$this->article->metakey); ?></textarea>
	</div>
</div>
<div class="tr">
	<div class="fl tc w30p">&nbsp;</div>
	<div class="fl w70p">
		<button type="button" title="<?php echo JText::_('Save') ?>" onclick="submitbutton('save')" class="positive fl">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/tick.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Save') ?></button>
		<button type="button" title="<?php echo JText::_('Cancel') ?>" onclick="submitbutton('cancel')" class="negative fl">
			<?php echo JHTML::_('image', "templates/$template/images/icons/silk/cross.png", JText::_('Icon'), array('class' => 'png24')) . JText::_('Cancel'); ?></button>
	</div>
</div>
</fieldset>
<input type="hidden" name="option" value="com_content" />
<input type="hidden" name="id" value="<?php echo $this->article->id; ; ?>" />
<input type="hidden" name="version" value="<?php echo $this->article->version; ; ?>" />
<input type="hidden" name="created_by" value="<?php echo $this->article->created_by; ; ?>" />
<input type="hidden" name="referer" value="<?php echo @$_SERVER['HTTP_REFERER']; ; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="" />
</form>
<?php echo JHTML::_('behavior.keepalive'); ?>
</div>
