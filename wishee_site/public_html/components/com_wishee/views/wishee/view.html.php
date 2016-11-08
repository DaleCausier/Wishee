<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by BuizKits.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the Wishee Component
 *
 * @since  0.0.1
 */
class WisheeViewWishee extends JViewLegacy {
	/**
	 * Display the main Wishee view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null) {
		// Assign data to the view
		$this->msg = 'Wishee main component';
        $this->foo = $this->get('Foo');
        
        // Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
 
			return false;
		}
 
		// Display the view
		parent::display($tpl);
	}
}