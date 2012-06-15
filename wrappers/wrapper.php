<?php
/*
* JEZ Thema Joomla! 1.5 Theme Base :: Wrappers Helper
*
* @package		JEZ Thema
* @version		1.1.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://joomlaez.com/ for more information
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

function jezWrapper($wrapperType, $includeFile, &$params, $id = '', $class = '', $tablelessRow = false, $colsCount = 0) {
	$wID = $id ? ' id="'.$id.'"' : '';
	$class = $class ? ' '.trim($class) : '';
	$tablelessRow = $tablelessRow ? ' tr'.($colsCount > 0 && $colsCount <= 5 ? ' gr'.$colsCount : '') : '';

	switch ($wrapperType) {
		case 'module':
			$tablelessRow = $tablelessRow ? ' class="'.trim($tablelessRow).'"' : '';
?>
<div<?php echo $wID; ?> class="module<?php echo $class; ?>"><div><div><div<?php echo $tablelessRow; ?>>
<?php
			break;
		case 'round6imgs-nested':
?>
<div<?php echo $wID; ?> class="jezRounded6ImgsNested<?php echo $class; ?>"><div class="in1"><div class="in2"><div class="in3"><div class="in4"><div class="in5<?php echo $tablelessRow; ?>">
<?php
			break;
		case 'round6imgs':
?>
<div<?php echo $wID; ?> class="jezRounded6Imgs<?php echo $class; ?>"><div class="hd"><div class="c"></div></div><div class="bd"><div class="c"><div class="s<?php echo $tablelessRow; ?>">
<?php
			break;
		case 'round1img':
		case 'round1img-scroll':
			$cOpen = $wrapperType == 'round1img-scroll' ? '"><div class="wrapper' : '';
			$cClose = $wrapperType == 'round1img-scroll' ? '></div' : '';
?>
<div<?php echo $wID; ?> class="jezRounded1Img<?php echo $class; ?>"><div class="inner<?php echo $cOpen.$tablelessRow; ?>"><div class="t"></div>
<?php
			break;
		case 'plain':
		default:
			$combinedClass = $class || $tablelessRow ? ' class="'.trim($class).($class && $tablelessRow ? ' ' : '').trim($tablelessRow).'"' : '';
?>
<div<?php echo $wID.$combinedClass; ?>>
<?php
			break;
	}

	// if theme specific wrapper exists, load it instead of default wrapper
	if ( $params->get('theme') && @file_exists(dirname(dirname(__FILE__)).DS.'themes'.DS.$params->get('theme').DS.'wrappers'.DS.$includeFile.'.php') )
		require_once(dirname(dirname(__FILE__)).DS.'themes'.DS.$params->get('theme').DS.'wrappers'.DS.$includeFile.'.php');
	elseif ( @file_exists(dirname(__FILE__).DS.$includeFile.'.php') )
		require_once(dirname(__FILE__).DS.$includeFile.'.php');

	switch ($wrapperType) {
		case 'module':
?>
</div></div></div></div><!-- end <?php echo $id; ?> -->
<?php
			break;
		case 'round6imgs-nested':
?>
</div></div></div></div></div></div><!-- end <?php echo $id; ?> -->
<?php
			break;
		case 'round6imgs':
?>
</div></div></div><div class="ft"><div class="c"></div></div></div><!-- end <?php echo $id; ?> -->
<?php
			break;
		case 'round1img':
?>
</div<?php echo $cClose; ?>><div class="b"><div></div></div></div><!-- end <?php echo $id; ?> -->
<?php
			break;
		case 'plain':
		default:
?>
</div><!-- end <?php echo $id; ?> -->
<?php
			break;
	}
}
?>