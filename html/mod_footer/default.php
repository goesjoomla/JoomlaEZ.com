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
<div class="mod_footer<?php echo $params->get( 'moduleclass_sfx' ); ?>">
	<div>
		<?php echo $lineone; ?>
	</div>
	<div>
		<?php echo JText::_( 'FOOTER_LINE2' ); ?>
	</div>
	<p>
		<a href="http://www.joomlaez.com/" title="Based on JEZ Thema Joomla template skeleton to create Joomla template">Based on JEZ Thema Joomla template skeleton to create Joomla template</a>.
	</p>
</div>
