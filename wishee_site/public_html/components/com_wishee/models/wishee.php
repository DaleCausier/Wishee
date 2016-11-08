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
 * Wishee Model
 *
 * @since  0.0.1
 */
class WisheeModelWishee extends JModelLegacy
{
    
    public function getFoo() {
        
        $this->foo = "foo";
        
        return $this->foo;
        
    }
    
}