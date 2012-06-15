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

$cparams	=& JComponentHelper::getParams('com_media');
$titleStyle	= $this->params->get('show_page_title', 1) ? '' : ' hide';
?>
<div id="jezComOutput" class="content_blog<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> tr">
<?php if ($this->params->get('page_title') != '') : ?>
<h2 class="componentheading<?php echo $titleStyle; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif;

if ($this->params->def('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
<p class="info clearfix">
	<?php if ($this->params->get('show_description_image') && $this->section->image) : ?>
	<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'. $this->section->image;?>" class="<?php echo $this->section->image_position;?>" alt="<?php echo JText::_('Image'); ?>" />
	<?php
	endif;

	if ($this->params->get('show_description') && $this->section->description)
		echo $this->section->description;
	?>
</p>
<?php endif;

if ($this->params->get('num_leading_articles')) : ?>
<div class="leading">
	<?php for ($i = $this->pagination->limitstart; $i < ($this->pagination->limitstart + $this->params->get('num_leading_articles')); $i++) :
	if ($i < $this->total) :
		$this->item =& $this->getItem($i, $this->params);
		echo $this->loadTemplate('item');
	endif;

	if ($i + 1 < $this->total) : ?>
	<span class="article_separator">&nbsp;</span>
	<?php endif;
	endfor; ?>
</div>
<?php
else :
	$i = $this->pagination->limitstart;
endif;

if ($this->params->get('num_intro_articles') && ($i < $this->total)) :
	$numIntroArticles = $i + $this->params->get('num_intro_articles');
	$numArticles = ($numIntroArticles < $this->total) ? $this->params->get('num_intro_articles') : ($this->total - $i);
	$rows = ceil($numArticles / $this->params->def('num_columns', 2));
?>
<div class="intro">
	<?php if ($this->params->def('multi_column_order', 0)) : // order across ?>
	<div class="tr gr<?php echo $this->params->get('num_columns').($this->params->get('num_columns') + $i >= $this->total && $this->pagination->limitstart == 0 ? ' last' : ''); ?>">
		<?php $counting = 0;
		while ($i < $this->total && $i < $numIntroArticles) :
			if ($counting >= $this->params->get('num_columns') && (floor($counting / $this->params->get('num_columns')) * $this->params->get('num_columns') == $counting)) : ?>
	</div>
	<div class="tr gr<?php echo $this->params->get('num_columns').($this->params->get('num_columns') + $i >= $this->total && $this->pagination->limitstart == 0 ? ' last' : ''); ?>">
			<?php endif; ?>
		<div class="fl<?php echo (($counting + 1) >= $this->params->get('num_columns') && (floor(($counting + 1) / $this->params->get('num_columns')) * $this->params->get('num_columns') == ($counting + 1))) ? '' : ' tc'; ?>">
			<?php
			$this->item =& $this->getItem($i, $this->params);
			echo $this->loadTemplate('item');
			$counting++;
			$i++;
			?>
		</div>
		<?php endwhile; ?>
	</div>
	<?php else : // otherwise, order down
		$last = $this->params->get('num_intro_articles') % $this->params->get('num_columns');
		$counting = $i;
		while ($counting < $this->total && $counting < $numIntroArticles) : ?>
	<div class="tr gr<?php echo $this->params->get('num_columns').($this->params->get('num_columns') + $counting >= $this->total && $this->pagination->limitstart == 0 ? ' last' : ''); ?>">
			<?php for ($j = 0; $j < $this->params->get('num_columns'); $j++) :
				$target = ($last && ($j > $last)) ? (($i + ($j * $rows)) - 1) : ($i + ($j * $rows));
				if ($target < $this->total && $target < $numIntroArticles && $counting < $this->total && $counting < $numIntroArticles) : ?>
		<div class="fl<?php echo (($j + 1) == $this->params->get('num_columns')) ? '' : ' tc'; ?>">
					<?php
					$this->item =& $this->getItem($target, $this->params);
					echo $this->loadTemplate('item');
					$counting++;
					?>
		</div>
				<?php endif;
			endfor;
			$i++; ?>
	</div>
		<?php endwhile;
		$i = $counting;
	endif; ?>
</div>
<?php endif;

if ($this->params->get('num_links') && ($i < $this->total)) : ?>
<div class="more">
	<?php
	$this->links = array_splice($this->items, $i - $this->pagination->limitstart);
	echo $this->loadTemplate('links');
	?>
</div>
<?php endif;

if ($this->params->get('show_pagination') && ($this->pagination->getPagesLinks() != '')) : ?>
<div class="pages_links">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<?php endif;

if ($this->params->get('show_pagination_results') && ($this->pagination->getPagesCounter() != '')) : ?>
<div class="pages_counter">
	<?php echo $this->pagination->getPagesCounter(); ?>
</div>
<?php endif; ?>
</div>
