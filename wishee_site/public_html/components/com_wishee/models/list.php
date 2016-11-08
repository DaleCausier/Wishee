<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by BuizKits.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

#require_once JPATH_SITE.'/components/com_wishee/vendor/autoload.php';

#use ApaiIO\Configuration\GenericConfiguration;
#use ApaiIO\Operations\Search;
#use ApaiIO\ApaiIO;
/**
 * Wishee Search Model
 *
 * @since  0.0.1
 */
class WisheeModelList extends JModelList {
    
    public function getGiftList() {
        
        $userID = JFactory::getUser()->id;
        
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $query->select('*')
              ->from($db->quoteName('#__wishee_gifts', 'gifts'))
              ->where($db->quoteName('gifts.user_id') . ' = ' . $userID);
        
        $db->setQuery($query);
        
        $gifts = $db->loadObjectList();
        
        return $gifts;
        
    }
    
    public function deleteGift($giftID) {
        
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $query->delete($db->quoteName('#__wishee_gifts'))
              ->where($db->quoteName('#__wishee_gifts.gift_id') . ' = ' . $giftID);
        
        #echo $query; exit();
        
        $db->setQuery($query);
        $db->execute();
        
        $gifts = $this->getGiftList();
        
        return $gifts;
        
    }
    
}