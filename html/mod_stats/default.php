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
<p class="info mod_stats<?php echo $params->get( 'moduleclass_sfx' ); ?>">
	<?php foreach ($list as $item) : ?>
	<strong><?php echo $item->title ?></strong> : <?php echo $item->data ?><br />
	<?php endforeach; ?>
</p>
