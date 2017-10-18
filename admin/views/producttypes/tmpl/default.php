<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_jshopmenu&task=producttypes.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'categoryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_jshopmenu&view=producttypes'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-sidebar-container" class="span2">
    	<?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php if (empty($this->producttypes)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
        <table class="table table-striped" id="categoryList">
            <thead>
                <tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('searchtools.sort', '', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('searchtools.sort', 'COM_JSHOPMENU_PRODUCTTYPES_HEADING_NAME', 'name', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'COM_JSHOPMENU_PRODUCTTYPES_HEADING_ID', 'product_type_id', $listDirn, $listOrder); ?>
					</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->producttypes as $i => $producttype) : ?>
                    <tr>
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
								<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $producttype->product_type_id; ?>" />
							<?php endif; ?>
						</td>
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $producttype->product_type_id); ?>
						</td>
						<td class="center">
							<?php echo JHtml::_('jgrid.published', $producttype->published, $i, 'producttypes.'); ?>
                            <?php echo JHtml::_('contentadministrator.napitki', $producttype->napitki, $i, true); ?>
						</td>
                        
                        <td>
                            <a href="<?php echo JRoute::_('index.php?option=com_jshopmenu&task=producttype.edit&product_type_id=' . (int) $producttype->product_type_id); ?>" >
                                <?php echo $producttype->name; ?>
                            </a>
                        </td>
                        <td class="center">
                            <?php echo (int)$producttype->product_type_id; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
                </tr>
            </tfoot>
        </table>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $this->escape($this->state->get('list.ordering')); ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->escape($this->state->get('list.direction')); ?>" />
        <?php echo JHtml::_('form.token'); ?>
        <?php endif;?>
    </div>
</form>