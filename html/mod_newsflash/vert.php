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
<ul class="listing mod_newsflash vert">
<?php for ($i = 0, $n = count($list); $i < $n; $i ++) : ?>
<li>
	<?php modNewsFlashHelper::renderItem($list[$i], $params, $access);

	if ($n > 1 && (($i + 1 < $n) || $params->get('showLastSeparator'))) : ?>
	<span class="article_separator">&nbsp;</span>
 	<?php endif; ?>
 </li>
<?php endfor; ?>
</ul>
