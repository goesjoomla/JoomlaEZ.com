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

if (
	($this->contact->params->get( 'address_check' ) > 0)
	&&
	(
		$this->contact->address
		||
		$this->contact->suburb
		||
		$this->contact->state
		||
		$this->contact->country
		||
		$this->contact->postcode
	)
) : ?>
<div class="tr">
	<?php if ( $this->contact->params->get( 'address_check' ) > 0 ) : ?>
	<div class="fl tc w10p tac">
		<?php echo $this->contact->params->get( 'marker_address' ); ?>
	</div>
	<?php endif; ?>
	<address class="fl w90p">
		<?php
		if ( $this->contact->address && $this->contact->params->get( 'show_street_address' ) )
			echo nl2br($this->escape($this->contact->address)).'<br/>';

		if ( $this->contact->suburb && $this->contact->params->get( 'show_suburb' ) )
			echo $this->escape($this->contact->suburb).'<br/>';

		if ( $this->contact->state && $this->contact->params->get( 'show_state' ) )
			echo $this->escape($this->contact->state).'<br/>';

		if ( $this->contact->postcode && $this->contact->params->get( 'show_postcode' ) )
			echo $this->escape($this->contact->postcode).'<br/>';

		if ( $this->contact->country && $this->contact->params->get( 'show_country' ) )
			echo $this->escape($this->contact->country).'<br/>';
		?>
	</address>
</div>
<?php endif;

if ( $this->contact->email_to && $this->contact->params->get( 'show_email' ) ) : ?>
<div class="tr">
	<div class="fl tc w10p tac">
		<?php echo $this->contact->params->get( 'marker_email' ); ?>
	</div>
	<p class="fl w90p">
		<?php echo $this->contact->email_to; ?>
	</p>
</div>
<?php endif;

if ( $this->contact->telephone && $this->contact->params->get( 'show_telephone' ) ) : ?>
<div class="tr">
	<div class="fl tc w10p tac">
		<?php echo $this->contact->params->get( 'marker_telephone' ); ?>
	</div>
	<p class="fl w90p">
		<?php echo nl2br($this->escape($this->contact->telephone)); ?>
	</p>
</div>
<?php endif;

if ( $this->contact->fax && $this->contact->params->get( 'show_fax' ) ) : ?>
<div class="tr">
	<div class="fl tc w10p tac">
		<?php echo $this->contact->params->get( 'marker_fax' ); ?>
	</div>
	<p class="fl w90p">
		<?php echo nl2br($this->escape($this->contact->fax)); ?>
	</p>
</div>
<?php endif;

if ( $this->contact->mobile && $this->contact->params->get( 'show_mobile' ) ) : ?>
<div class="tr">
	<div class="fl tc w10p tac">
		<?php echo $this->contact->params->get( 'marker_mobile' ); ?>
	</div>
	<p class="fl w90p">
		<?php echo nl2br($this->escape($this->contact->mobile)); ?>
	</p>
</div>
<?php endif;

if ( $this->contact->webpage && $this->contact->params->get( 'show_webpage' )) : ?>
<div class="tr">
	<div class="fl tc w10p tac">
		<?php echo JHTML::_('image', 'images/M_images/weblink.png', JText::_('Icon')); ?>
	</div>
	<p class="fl w90p">
		<a href="<?php echo $this->escape($this->contact->webpage); ?>" title="<?php echo JText::_('Web page'); ?>" target="_blank"><?php echo $this->escape($this->contact->webpage); ?></a>
	</p>
</div>
<?php endif;

if ( $this->contact->misc && $this->contact->params->get( 'show_misc' ) ) : ?>
<div class="tr">
	<div class="fl tc w10p tac">
		<?php echo $this->contact->params->get( 'marker_misc' ); ?>
	</div>
	<p class="fl w90p">
		<?php echo $this->escape($this->contact->misc); ?>
	</p>
</div>
<?php endif; ?>
