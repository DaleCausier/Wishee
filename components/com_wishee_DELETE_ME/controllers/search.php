<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by Jonathan C James.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class WisheeControllerSearch extends JControllerForm {
    
    /*public function searchProducts() {
        $app = JFactory::getApplication();
        $input = $app->input;
        $model = $this->getModel('search');
        $successURL = $_SERVER['HTTP_REFERER'];
        
        $data = array();
        $data['search_query']   = $input->get('search_query', '', 'STRING');
        $data['categories']     = $input->get('categories', '', 'ARRAY');
        
        $searchResults = $model->getProducts($formData);
    }*/
    
    public function displaySearchResults() {
        parent::display();
    }
    
}