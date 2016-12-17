<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by BuizKits.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class WisheeViewList extends JViewLegacy {
	/**
	 * Display the Wishee Current User Wish List view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
    
    protected $gift_list;
    
    function display($tpl = null) {
        
        $document = JFactory::getDocument();
        $document->addScript('components/com_wishee/views/js/list.js');
        
        // Assign data to the view
        $this->gift_list = $this->get('giftList');
        
        // Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
 
			return false;
		}
 
		// Display the view
		parent::display($tpl);
	}
}