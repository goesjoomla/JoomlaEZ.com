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
?>
<ul class="mod_relateditems<?php echo $params->get( 'moduleclass_sfx' ); ?> listing">
<?php foreach ($list as $item) :	?>
<li>
	<a href="<?php echo $item->route; ?>" title="<?php echo $item->title; ?>">
		<?php if ($showDate) { echo $item->created . " - "; } echo $item->title; ?></a>
</li>
<?php endforeach; ?>
</ul>
