<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by BuizKits.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class WisheeViewSearch extends JViewLegacy {
    
    function display($tpl = null) {
        $input = JFactory::getApplication()->input;
        
        /* Get AJAX post data from jQuery.ajax() function in search.js */
        $formData = array();
        $formData['search_query']   = $input->get('search_query', null, 'STR');
        $formData['category']     = $input->get('category', null, 'STR');
        
        $model = $this->getModel('search');
        $searchResults = $model->getProducts($formData);
        
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo json_encode($searchResults);
    }
    
}