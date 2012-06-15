<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Output Overrides
*
* @package	JEZ Thema
* @version	1.1.0
* @author	JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license	Commercial Proprietary
*
* Please visit http://joomlaez.com/ for more information
*/

/*----------------------------------------------------------------------------*/

defined('_JEXEC') or die;
?>
<form action="index.php" method="post" class="mod_search<?php echo $params->get( 'moduleclass_sfx' ); ?>">
<?php
$output = '<input name="searchword" id="mod_search_searchword" maxlength="'.$maxlength.'" alt="'.$button_text.'" class="inputbox" type="text" size="'.$width.'" value="'.$text.'" onblur="if(this.value==\'\') this.value=\''.$text.'\';" onfocus="if(this.value==\''.$text.'\') this.value=\'\';" />';

if ($button) {
	if ($imagebutton)
		$button = '<input type="image" value="'.$button_text.'" class="button" src="'.$img.'" onclick="this.form.searchword.focus();"/>';
	else
		$button = '<input type="submit" value="'.$button_text.'" class="button" onclick="this.form.searchword.focus();"/>';
}

switch ($button_pos) {
	case 'top' :
		$button = $button.'<br />';
		$output = $button.$output;
		break;

	case 'bottom' :
		$button = '<br />'.$button;
		$output = $output.$button;
		break;

	case 'right' :
		$output = $output.$button;
		break;

	case 'left' :
	default :
		$output = $button.$output;
		break;
}

echo $output;
?>
<input type="hidden" name="task" value="search" />
<input type="hidden" name="option" value="com_search" />
</form>
