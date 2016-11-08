<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by BuizKits.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class WisheeControllerSearch extends JControllerForm {
    
    /*public function searchProducts() {
        $app = JFactory::getApplication();
        $input = $app->input;
        $method = $input->getMethod();
        $model = $this->getModel('search');
        $successURL = $_SERVER['HTTP_REFERER'];
        
        $data = array();
        $data['search_query'] = $input->$method->get('search_query', '', 'STRING');   
    }*/
    
    public function displaySearchResults() {
        parent::display();
    }
    
}