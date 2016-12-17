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
<h3 style="margin-bottom: 40px;">Search for Gifts</h3>

<!-- form id="search-form" method="post" name="search-form" enctype="multipart/form-data" action="<?php #echo JRoute::_('index.php?option=com_wishee&task=search.searchProducts'); ?>"
  -->
    <div id="search-products-bar">
        <?php foreach ($this->form->getFieldset('search-data') as $field) : ?>
            <?php echo $field->label; ?>
            <?php echo $field->input; ?>
        <?php endforeach; ?>
        <!-- input type="hidden" name="joomla_user_id" id="joomla_user_id" value="<?php #echo $user->id; ?>" / -->
        <?php #echo JHtml::_('form.token'); ?>
    </div>
    <p id="category-toggle" class="bold">Refine by Category <i class="fa fa-chevron-down"></i></p>
    <?php foreach ($this->form->getFieldset('categories-data') as $field) : ?>
        <?php echo $field->input; ?>
    <?php endforeach; ?>
    <button type="button" id="search-products-button" class="button">Search</button>
    <a href="#" id="clear-search-button" class="button hollow">Clear</a>
<!-- /form -->
<hr />
<h4>Search Results:</h4>

<div id="search-results">
    <p id="default-msg">Use the search box above to look for gifts!</p>
    <i class="fa fa-circle-o-notch loader"></i>
</div>

<!-- action='<?php #echo JRoute::_('index.php?option=com_wishee&task=search.searchProducts'); ?>'-->