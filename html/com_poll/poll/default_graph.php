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
?>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="borders clr">
<thead>
	<tr>
		<th colspan="3">
			<img src="<?php echo $this->baseurl; ?>/components/com_poll/assets/poll.png" alt="<?php echo JText::_('Icon'); ?>" />
			<?php echo $this->escape($this->poll->title); ?>
		</th>
	</tr>
</thead>
<tbody>
<?php foreach($this->votes as $vote) : ?>
	<tr class="row<?php echo $vote->odd; ?>">
		<th colspan="3"><?php echo $vote->text; ?></th>
	</tr>
	<tr class="row<?php echo $vote->odd; ?>">
		<td class="tac bold" width="10%"><?php echo $this->escape($vote->hits); ?></td>
		<td class="tac" width="30%"><?php echo $this->escape($vote->percent); ?>%</td>
		<td width="60%">
			<div class="<?php echo $vote->class; ?>" style="width:<?php echo $vote->percent; ?>%">&nbsp;</div>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>
<div class="success">
	<div class="tr">
		<div class="fl tc w40p tar"><?php echo JText::_( 'Number of Voters' ); ?>:</div>
		<div class="fl w60p"><?php echo (isset($this->votes[0])) ? $this->votes[0]->voters : '-'; ?></div>
	</div>
	<div class="tr">
		<div class="fl tc w40p tar"><?php echo JText::_( 'First Vote' ); ?>:</div>
		<div class="fl w60p"><?php echo $this->escape($this->first_vote); ?></div>
	</div>
	<div class="tr">
		<div class="fl tc w40p tar"><?php echo JText::_( 'Last Vote' ); ?>:</div>
		<div class="fl w60p"><?php echo $this->escape($this->last_vote); ?></div>
	</div>
</div>
