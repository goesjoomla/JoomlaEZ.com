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

defined( '_JEXEC' ) or die( 'Restricted access' );

$cparams = JComponentHelper::getParams ('com_media');
$titleStyle	= ($this->params->get( 'show_page_title', 1 ) && !$this->contact->params->get( 'popup' ) && $this->params->get('page_title') != $this->contact->name) ? '' : ' hide';
?>
<div id="jezComOutput" class="contact<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif;

if ( $this->params->get( 'show_contact_list' ) && count( $this->contacts ) > 1) : ?>
<div class="fr filter">
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="selectForm" id="selectForm">
		<label for="contact_id"><?php echo JText::_( 'Select Contact' ); ?></label>
		<?php echo JHTML::_('select.genericlist', $this->contacts, 'contact_id', 'title="'.JText::_( 'Contact List' ).'" onchange="this.form.submit()"', 'id', 'name', $this->contact->id); ?>
		&nbsp;&nbsp;<input type="submit" title="<?php echo JText::_('View'); ?>" value="<?php echo JText::_('View'); ?>" />
		<input type="hidden" name="option" value="com_contact" />
	</form>
</div>
<br class="clr" />
<?php endif;

if ( $this->contact->name && $this->contact->params->get( 'show_name' ) ) : ?>
<h2><?php echo $this->contact->name; ?></h2>
<?php endif;

if (($this->contact->con_position && $this->contact->params->get( 'show_position' )) || ($this->contact->image && $this->contact->params->get( 'show_image' ))) : ?>
<p class="info clearfix">
	<?php
	if ($this->contact->image && $this->contact->params->get( 'show_image' ))
		echo JHTML::_('image', 'images/stories/'.$this->contact->image, JText::_('Image'), array('class' => 'right'));

	echo $this->escape($this->contact->con_position);
	?>
</p>
<?php endif;

echo $this->loadTemplate('address');

if ( $this->contact->params->get( 'allow_vcard' ) ) : ?>
<p>
	<?php echo JText::_( 'Download information as a' );?>
	<a href="<?php echo JURI::base(); ?>index.php?option=com_contact&amp;task=vcard&amp;contact_id=<?php echo $this->contact->id; ?>&amp;format=raw&amp;tmpl=component" title="<?php echo JText::_( 'Download contact vCard' );?>"><?php echo JText::_( 'VCard' );?></a>
</p>
<?php
endif;

if ( $this->contact->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id))
	echo $this->loadTemplate('form');
?>
</div>
