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
    
    function display($tpl = null) {
    
        $jinput = JFactory::getApplication()->input;
    
        $giftID = $jinput->get('gift_id', null, 'INT');

        $model= $this->getModel('list');
        $gifts = $model->deleteGift($giftID);

        JFactory::getDocument()->setMimeEncoding('application/json');
        echo json_encode($gifts);
    
    }
    
}