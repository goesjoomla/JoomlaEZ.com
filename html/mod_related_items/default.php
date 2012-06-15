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

defined('_JEXEC') or die;
?>
<ul class="listing mod_relateditems<?php echo $params->get( 'moduleclass_sfx' ); ?>">
<?php foreach ($list as $item) :	?>
<li>
	<a href="<?php echo $item->route; ?>" title="<?php echo $item->title; ?>">
		<?php if ($showDate) { echo $item->created . " - "; } echo $item->title; ?></a>
</li>
<?php endforeach; ?>
</ul>
