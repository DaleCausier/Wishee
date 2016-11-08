<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by Jonathan C James.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the Wishee Component
 *
 * @since  0.0.1
 */
class WisheeViewSearch extends JViewLegacy {
	/**
	 * Display the Wishee search view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
    
    protected $title;
    protected $results;
    protected $form;
    
	function display($tpl = null) {
		// Assign data to the view
        #$this->results = $this->get('Results');
        $document = JFactory::getDocument();
        $document->addScript('components/com_wishee/views/js/search.js');
        $this->form  = $this->get('Form');
        
        // Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
 
			return false;
		}
 
		// Display the view
		parent::display($tpl);
	}
}