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