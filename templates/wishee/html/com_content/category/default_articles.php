<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Create some shortcuts.
$params    = &$this->item->params;
$n         = count($this->items);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

// Check for at least one editable article
$isEditable = false;

if (!empty($this->items))
{
	foreach ($this->items as $article)
	{
		if ($article->params->get('access-edit'))
		{
			$isEditable = true;
			break;
		}
	}
}
?>
<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
<?php if ($this->params->get('show_headings') || $this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) :?>
	<fieldset class="filters btn-toolbar clearfix">
		<?php if ($this->params->get('filter_field') != 'hide') :?>
			<div class="btn-group">
				<?php if ($this->params->get('filter_field') != 'tag') :?>
					<label class="filter-search-lbl element-invisible" for="filter-search">
						<?php echo JText::_('COM_CONTENT_' . $this->params->get('filter_field') . '_FILTER_LABEL') . '&#160;'; ?>
					</label>
                    <select
                        name="filter-search"
                        id="filter-search"
                        onchange="document.adminForm.submit();"
                        class="form-control"
                        title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>"
                    >
                        <option value="">-- Select --</option>
                        <option value="">All Authors</option>
                        <option value="Dale Causier">Dale Causier</option>
                        <option value="Laura Higgins">Laura Higgins</option>
                        <option value="Jonathan James">Jonathan James</option>
                        <option value="Ross Hayes">Ross Hayes</option>
                        <option value="Tom Cave">Tom Cave</option>
                    </select>
                    <span style="margin-left: 15px;">
                        <?php if (empty($this->escape($this->state->get('list.filter')))) {
                            echo 'Showing results for: &nbsp;<b>All Authors</b>';
                        } else {
                            echo 'Showing results for: &nbsp;<b>'.$this->escape($this->state->get('list.filter')).'</b>';
                        } ?>
                    </span>
				<?php else :?>
					<select class="form-control" name="filter_tag" id="filter_tag" onchange="document.adminForm.submit();" >
						<option value=""><?php echo JText::_('JOPTION_SELECT_TAG'); ?></option>
						<?php echo JHtml::_('select.options', JHtml::_('tag.options', true, true), 'value', 'text', $this->state->get('filter.tag')); ?>
					</select>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="btn-group pull-right">
				<label for="limit" class="element-invisible">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif; ?>

		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
		<input type="hidden" name="task" value="" />
	</fieldset>
<?php endif; ?>

<?php if (empty($this->items)) : ?>

	<?php if ($this->params->get('show_no_articles', 1)) : ?>
		<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
	<?php endif; ?>

<?php else : ?>
    
    <?php foreach ($this->items as $i => $article) : ?>
        
        <div class="row admin-article-list-link">
            <a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language)); ?>">
            <?php
                switch($article->author) {
                    case "Jonathan James":
                        $authorImage = '/images/contacts/jonathan_james.png';
                        break;
                    case "Dale Causier":
                        $authorImage = '/images/contacts/dale_causier.jpg';
                        break;
                    default:
                        $authorImage = false;
                }
            ?>
            
            <div class="col-sm-12">
                <?php if ($authorImage) : ?>
                    <img
                         src="<?php echo $authorImage; ?>"
                         alt="Author Image"
                         class="img-responsive pull-left"
                     />
                <?php endif; ?>
                <?php if (in_array($article->access, $this->user->getAuthorisedViewLevels())) : ?>
                    <h3>
                        <?php echo $this->escape($article->title); ?>
                    </h3>
                <?php else: ?>
                    <?php
                    echo $this->escape($article->title) . ' : ';
                    $menu   = JFactory::getApplication()->getMenu();
                    $active = $menu->getActive();
                    $itemId = $active->id;
                    $link   = new JUri(JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false));
                    $link->setVar('return', base64_encode(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language)));
                    ?>
                    <a href="<?php echo $link; ?>" class="register">
                        <?php echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE'); ?>
                    </a>
                <?php endif; ?>

                
                <?php if ($this->params->get('list_show_author', 1)) : ?>
                    <?php if (!empty($article->author) || !empty($article->created_by_alias)) : ?>
                        <?php $author = $article->author ?>
                        <?php $author = ($article->created_by_alias ? $article->created_by_alias : $author);?>
                        <?php if (!empty($article->contact_link) && $this->params->get('link_author') == true) : ?>
                            <h4><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', $article->contact_link, $author)); ?></h4>
                        <?php else: ?>
                            <h4><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?></h4>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>

                
                <?php if ($this->params->get('list_show_date')) : ?>
                    <p class="small">
                        <?php
                        echo ucfirst($this->params->get('list_show_date')).': &nbsp;'.JHtml::_(
                            'date', $article->displayDate,
                            $this->escape($this->params->get('date_format', JText::_('DATE_FORMAT_LC3')))
                        ); ?>
                    </p>
                <?php endif; ?>
            </div><!-- /.article-details-->
                </a>
        </div><!-- /.row -->
    
    <?php endforeach; ?>
    
<?php endif; ?>


<?php // Add pagination links ?>
<?php if (!empty($this->items)) : ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
		<div class="pagination">

			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter pull-right">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			<?php endif; ?>

			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>
<?php  endif; ?>
</form>