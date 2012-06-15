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
?>
<tbody>
<?php foreach($this->items as $item) : ?>
<tr class="row<?php echo $item->odd + 1; ?>">
	<td class="tac"><?php echo $item->count + 1; ?></td>
	<td><a href="<?php echo $item->link; ?>" title="<?php echo $item->name; ?>"><?php echo $item->name; ?></a></td>
	<?php if ( $this->params->get( 'show_position' ) ) : ?>
	<td><?php echo $this->escape($item->con_position); ?></td>
	<?php endif;

	if ( $this->params->get( 'show_email' ) ) : ?>
	<td><?php echo $item->email_to; ?></td>
	<?php endif;

	if ( $this->params->get( 'show_telephone' ) ) : ?>
	<td><?php echo $this->escape($item->telephone); ?></td>
	<?php endif;

	if ( $this->params->get( 'show_mobile' ) ) : ?>
	<td><?php echo $this->escape($item->mobile); ?></td>
	<?php endif;

	if ( $this->params->get( 'show_fax' ) ) : ?>
	<td><?php echo $this->escape($item->fax); ?></td>
	<?php endif; ?>
</tr>
<?php endforeach; ?>
</tbody>
