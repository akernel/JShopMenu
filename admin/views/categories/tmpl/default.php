<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_jshopmenu&task=categories.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'categoryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_jshopmenu&view=categories'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-sidebar-container" class="span2">
    	<?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php if (empty($this->categories)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
        <table class="table table-striped" id="categoryList">
            <thead>
                <tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('searchtools.sort', 'COM_JSHOPMENU_CATEGORIES_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
					</th>
                    <th>
                        <?php echo JHtml::_('searchtools.sort', 'COM_JSHOPMENU_CATEGORIES_HEADING_SHORT_NAME', 'a.short_name', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('searchtools.sort', 'COM_JSHOPMENU_CATEGORIES_HEADING_ALIAS', 'a.slug', $listDirn, $listOrder); ?>
                    </th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'COM_JSHOPMENU_CATEGORIES_HEADING_ID', 'a.category_id', $listDirn, $listOrder); ?>
					</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->categories as $i => $category) : ?>
                    <tr class="row<?php echo $i % 2; ?>" sortable-group-id="1" item-id="<?php echo $category->category_id ?>" level="1">
                        
                        <td class="order nowrap center hidden-phone">
							<?php
							$iconClass = '';
							if (!$saveOrder)
							{
								$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
							}
							?>
							<span class="sortable-handler<?php echo $iconClass ?>">
								<span class="icon-menu"></span>
							</span>
							<?php if ($saveOrder) : ?>
								<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $category->ordering; ?>" />
							<?php endif; ?>
						</td>
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $category->category_id); ?>
						</td>
						<td class="center">
							<?php echo JHtml::_('jgrid.published', $category->published, $i, 'categories.'); ?>
						</td>
                        <td>
                            <a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_jshopmenu&task=category.edit&category_id=' . (int) $category->category_id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
                                <?php echo $this->escape($category->name); ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $category->short_name; ?>
                        </td>
                        <td>
                            <?php echo $category->slug; ?>
                        </td>
						<td class="hidden-phone center">
						      <?php echo (int) $category->category_id; ?></span>
						</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
                </tr>
            </tfoot>
        </table>
        <?php endif ?>
        
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $this->escape($this->state->get('list.ordering')); ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->escape($this->state->get('list.direction')); ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>