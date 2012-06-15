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
<p class="mod_stats<?php echo $params->get( 'moduleclass_sfx' ); ?> info">
	<?php foreach ($list as $item) : ?>
	<strong><?php echo $item->title ?></strong> : <?php echo $item->data ?><br />
	<?php endforeach; ?>
</p>
