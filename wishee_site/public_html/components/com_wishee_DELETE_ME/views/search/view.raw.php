<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by Jonathan C James.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class WisheeViewSearch extends JViewLegacy {
    
    function display($tpl = null) {
        /*$app = JFactory::getApplication();
        $input = $app->input;
        $model = $this->getModel('search');
        $successURL = $_SERVER['HTTP_REFERER'];
        
        $formData = array();
        $formData['search_query']   = $input->get('search_query', '', 'STRING');
        $formData['categories']     = $input->get('categories', '', 'ARRAY');*/
        
        $searchResults = $model->getProducts($formData);
        
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo json_encode($searchResults);
    }
    
}