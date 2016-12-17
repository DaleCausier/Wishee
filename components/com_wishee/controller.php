<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by BuizKits.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.controller');

/**
 * Wishess Component Controller
 *
 * @since  0.0.1
 */
class WisheeController extends JControllerLegacy
{
    public function addGift(){
        $app = JFactory::getApplication();
        $jinput = $app->input;
        $productID = $jinput->get('product_id');
        $listModel = $this->getModel('list');

        $addGiftSuccess = $listModel->addGift($productID);

        if($addGiftSuccess) {
            $app->enqueueMessage('Gift added!', 'success');
        } else {
            $app->redirect('index.php');
        }

        $app->redirect(JRoute::_('index.php?option=com_wishee&view=list'));
    
    }
}