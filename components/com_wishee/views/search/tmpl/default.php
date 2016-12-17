<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_wishee
 *
 * @copyright   Copyright (C) 2016 by Jonathan C James.  All rights reserved.
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();
JHtml::_('behavior.formvalidator');
?>

<style type="text/css">
    #search-results .row {
        margin-bottom: 30px;
    }
    #search-results .product {
        position: relative;
        height: 550px;
        box-sizing: border-box;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 3px;
    }
    #search-results .product img {
        max-height: 250px;
    }
    #search-results .product .product-name {
        margin: 20px 0 0 0;
    }
    #search-results .product .product-name a {
        font-size: 16px;
    }
    #search-results .product .product-details {
        position: absolute;
        left: 0; bottom: 0;
        box-sizing: border-box;
        width: 100%; height: auto;
    }
    #search-results .product .product-details .product-category {
        margin-left: 20px;
    }
    #search-results .product .product-details .rrp-price {
        margin: 0 0 0 20px;
        color: #969696;
        text-decoration: line-through;
        min-height: 20px;
    }
    #search-results .product .product-details .product-price {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 20px 20px;
    }
    #search-results .product .product-details .product-buttons {
        border-top: 1px solid #ddd;
    }
    #search-results .product .product-details .product-button {
        background-color: #eee;
        box-sizing: border-box;
        text-align: center;
        padding: 0;
        transition: 0.2s all ease-in-out;
    }
    #search-results .product .product-details .product-button:hover {
        background-color: #591c56;
    }
    #search-results .product .product-details .product-button:hover a {
        color: #fff;
    }
    #search-results .product .product-details .product-button:first-child {
        border-right: 1px solid #ddd;
        border-radius: 0 0 0 3px;
    }
    #search-results .product .product-details .product-button:last-child {
        border-radius: 0 0 3px 0;
    }
    #search-results .product .product-details .product-button a {
        display: block;
        padding: 15px 20px;
        color: #969696;
        text-decoration: none !important;
        font-weight: 700;
    }
    #search-results .product .product-details .product-button a i.fa {
        margin-right: 5px;
        font-size: 18px;
    }
</style>

<h3 style="margin: 20px 0 30px 0;"><i class="fa fa-gift"></i> Search for Gifts</h3>

<form id="search-form" method="post" name="search-form">
    <div id="search-products-bar">
        <?php foreach ($this->form->getFieldset('search-data') as $field) : ?>
            <div class="form-group">
                <?php echo $field->input; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php foreach ($this->form->getFieldset('categories-data') as $field) : ?>
        <div class="form-group">
            <p class="search-label">
                <i class="fa fa-binoculars"></i> Refine Search by Category:
            </p>
            <?php echo $field->input; ?>
        </div>
    <?php endforeach; ?>
    <div class="form-group">
        <button type="submit" id="search-products-button" class="btn btn-primary">
            <i class="fa fa-search"></i> Search
        </button>
        <a href="#" id="clear-search-button" class="btn btn-default">
            <i class="fa fa-refresh"></i> Clear
        </a>
    </div>
</form>

<hr />

<h4>Search Results:</h4>

<div id="search-results">
    <p id="default-msg">Use the search box above to look for gifts!</p>
    <i class="fa fa-circle-o-notch loader"></i>
</div>

<!-- action='<?php #echo JRoute::_('index.php?option=com_wishee&task=search.searchProducts'); ?>'-->