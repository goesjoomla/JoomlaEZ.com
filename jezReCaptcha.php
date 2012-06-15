<?php
/*
 * JEZ reCAPTCHA Integrator Joomla! 1.5 plugin
 *
 * @package		JEZ reCAPTCHA Integrator
 * @version		1.0.0
 * @author		JoomlaEZ.com
 * @copyright	Copyright (C) 2008 JoomlaEZ.com. All rights reserved
 * @license		Creative Commons Attribution-Noncommercial-Share Alike 3.0 Unported
 *
 * Please visit http://joomlaez.com/ for more information
 */

// no direct access
defined('_JEXEC') or die();

// require the helper class
require_once( dirname( __FILE__ ).DS.'jezReCaptcha'.DS.'helper.php' );

class plgSystemJezReCaptcha extends JPlugin {
	// variable to hold the plugin params
	var $params = null;

	// construct plugin
	function plgSystemJezReCaptcha(&$subject, $config) {
		parent::__construct($subject, $config);

		// get plugin params if enabled
		$this->params = plgSystemJezReCaptchaHelper::isEnabled();
	}

	// function to execute at system event onAfterInitialise
	function onAfterInitialise() {
		// is JEZ reCAPTCHA Integrator plugin enabled and set to auto-verify?
	 	if ($this->params && $this->params->def('auto_verify', 1))
			plgSystemJezReCaptchaHelper::verifyCaptcha($this->params);
	}

	// function to execute at system event onAfterRender
	function onAfterRender() {
		global $mainframe;
		if (!$mainframe->isAdmin()) {
			// get the document body
			$body = JResponse::getBody();

			// is JEZ reCAPTCHA Integrator plugin and inclusion syntax enabled?
		 	if ($this->params && $this->params->def('inclusion_syntax', 0) && plgSystemJezReCaptchaHelper::getDocType() == 'html') {
				// html page, parse inclusion syntax
				$altered = plgSystemJezReCaptchaHelper::parseInclusionSyntax($body, $this->params);
			} elseif (!$mainframe->isAdmin()) {
				// clear inclusion syntax
				$altered = plgSystemJezReCaptchaHelper::clearInclusionSyntax($body);
			}

			// set the new document body
			if ($altered)
				JResponse::setBody($body);
		}
	}

	// function to execute at custom event onCaptchaDisplay
	function onCaptchaDisplay() {
		// is JEZ reCAPTCHA Integrator plugin enabled and configured?
		if ($this->params)
			echo plgSystemJezReCaptchaHelper::renderCaptcha($this->params);
	}

	// function to execute at custom event onCaptchaConfirm
	function onCaptchaConfirm() {
		// is JEZ reCAPTCHA Integrator plugin enabled and configured?
		if ($this->params)
			plgSystemJezReCaptchaHelper::verifyCaptcha($this->params);
	}
}
?>