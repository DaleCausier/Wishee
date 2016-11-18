<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by BuizKits.  All rights reserved.
 *
 * @author(s)   Jonathan James
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();
$gifts = $this->gift_list;
?>

<h1><?php echo explode(' ',trim($user->name))[0] . "'s List"; ?></h1>
<a class="button" href="<?php echo JRoute::_('index.php?option=com_wishee&view=search'); ?>" title="Search Gifts">
    <i class="fa fa-plus"></i> Add Gift
</a>

<div id="user-gift-list" style="margin-top: 40px;">
    
    <?php if (empty($gifts)) : ?>
    <p id="default-msg">Your list is currently empty.</p>
    <?php endif; ?>
    
    <i class="fa fa-circle-o-notch loader"></i>
    
    <?php foreach ($gifts as $gift) : ?>
    <div class="row" style="margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">

        <div class="col-sm-2">
            <img
                style="max-height: 200px; width: auto;"
                src="<?php echo $gift->product_image_url; ?>"
                alt="<?php echo $gift->product_name; ?>"
            />
        </div>

        <div class="col-sm-4">
            <?php echo $gift->product_name; ?>
            <p><a style="margin-top: 10px;" class="btn btn-default" href="<?php echo $gift->product_store_url; ?>" target="_blank">
                Buy on Amazon
            </a></p>
        </div>

        <div class="col-sm-1">
            <?php echo $gift->product_category; ?>
        </div>

        <div class="col-sm-1" style="text-align: right;">
            <?php echo '&pound;' . substr($gift->product_price, 0, -2) . '.' . substr($gift->product_price, -2); ?>
        </div>

        <div class="col-sm-3" style="text-align: right;">
            <button type="button" class="btn btn-danger delete-gift-btn" id="<?php echo $gift->gift_id; ?>">
                <i class="fa fa-times"></i> Delete
            </button>
        </div>

    </div>
    <?php endforeach; ?>
</div>