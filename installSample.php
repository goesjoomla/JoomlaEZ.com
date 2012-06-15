<?php
/*
* JEZ Rego Joomla! 1.5 Template :: Installer :: sample data
*
* @package		JEZ Rego
* @version		1.5.0
* @author		JoomlaEZ.com
* @copyright	Copyright (C) 2008, 2009 JoomlaEZ. All rights reserved unless otherwise stated.
* @license		Commercial Proprietary
*
* Please visit http://www.joomlaez.com/ for more information
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (JRequest::getCmd('task') != 'install') : ?>
<h1>Thank you for choosing JEZ Rego Joomla! 1.5 template!</h1>
<p>So you want to install the sample data as seen in <a href="http://demo.joomlaez.com/joomla-theme-jez-rego/" target="_blank">JEZ Rego live demo</a> website. Before going further, please verify following conditions:</p>
<ul>
	<li>Installing sample data is not recommended for production website. So please make sure you are going to install sample data on a new fresh Joomla! 1.5 installation.</li>
	<li><a href="http://www.joomlaez.com/joomla-modules/joomla-slideshow-module.html" target="joomlaez">Joomla slideshow module JEZ Arguo</a> is installed in your Joomla! 1.5 website.</li>
	<li><a href="http://www.joomlaez.com/joomla-plugins/load-joomla-module-anytime-anywhere.html" target="joomlaez">JEZ Module Loader Joomla plugin</a> is installed in your Joomla! 1.5 website.</li>
	<li><a href="http://www.joomlaez.com/joomla-plugins/joomla-captcha-solution.html" target="joomlaez">JEZ reCAPTCHA Integrator Joomla plugin</a> is installed in your Joomla! 1.5 website.</li>
	<li>JEZ Rego's dev mode is enabled.</li>
</ul>
<p>If all above conditions are met, please <a href="index.php?template=jez_rego&tmpl=installSample&task=install">click here</a> to install JEZ Rego sample data.</p>
<?php else : ?>
<h1>Installing JEZ Rego sample data ...
<?php
	if ( $sampleData = @file(dirname(__FILE__).DS.'sampleData.sql') ) {
		$sql =& JFactory::getDBO();
		$query = '';
		foreach ($sampleData AS $line) {
			if ( !preg_match('/;[\r\n]*$/', $line) )
				$query .= $line;
			else {
				$sql->setQuery($query.$line);
				if (!$sql->query()) {
					echo ' fail!</h1>';
					echo '<pre>'.$sql->getErrorMsg().'</pre>';
					break;
				}
				$query = '';
			}
		}
		echo ' done!</h1>';
		if ( !(@unlink(__FILE__)) )
			echo '<p>Please remove the file<br/><br/><strong>'.__FILE__.'</strong><br/><br/> immediately before continue!</p>';
		echo '<p><a href="index.php?template=jez_rego">Click here</a> to see JEZ Rego with sample data loaded.</p>';
	} else {
		echo ' fail!</h1>';
		echo '<pre>Cannot read sample data file.</pre>';
	}
endif;
?>