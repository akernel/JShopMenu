<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'p.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_jshopmenu&task=products.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'productList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}
$default_image = '/media/com_jshopmenu/images/noimage.gif';

$filter_categories = $this->state->get('filter.categories');
$filter_menu_types = $this->state->get('filter.menu_types');
$filter_product_types = $this->state->get('filter.product_types');
?>
<form action="<?php echo JRoute::_('index.php?option=com_jshopmenu&view=products'); ?>" method="post" name="adminForm" id="adminForm">
    
    <div id="j-sidebar-container" class="span2">
    	<?php echo $this->sidebar; ?>
    </div>

    <div id="j-main-container" class="span10">
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php if (empty($this->products)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
        <table class="table table-striped" id="productList">
            <thead>
                <tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('searchtools.sort', '', 'p.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'p.published', $listDirn, $listOrder); ?>
					</th>
                    <th>
                        <?php echo JText::_('COM_JSHOPMENU_PRODUCTS_HEADING_IMAGE'); ?>
                    </th>
					<th>
						<?php echo JHtml::_('searchtools.sort', 'COM_JSHOPMENU_PRODUCTS_HEADING_NAME', 'p.name', $listDirn, $listOrder); ?>
					</th>
                    <th>
                        <?php echo JText::_('COM_JSHOPMENU_PRODUCTS_HEADING_CATEGORIES'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_JSHOPMENU_PRODUCTS_HEADING_MENUTYPES'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_JSHOPMENU_PRODUCTS_HEADING_PRODUCTTYPES'); ?>
                    </th>
                    <th width="1%">
                        <?php echo JHtml::_('searchtools.sort', 'COM_JSHOPMENU_PRODUCTS_HEADING_PRICE', 'p.price', $listDirn, $listOrder); ?>
                    </th>
                    <th width="1%">
                        <?php echo JHtml::_('searchtools.sort', 'COM_JSHOPMENU_PRODUCTS_HEADING_VES', 'p.ves', $listDirn, $listOrder); ?>
                    </th>
                    <th width="1%">
                        <?php echo JHtml::_('searchtools.sort', 'COM_JSHOPMENU_PRODUCTS_HEADING_KOLL', 'p.koll', $listDirn, $listOrder); ?>
                    </th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'COM_JSHOPMENU_PRODUCTS_HEADING_ID', 'p.product_id', $listDirn, $listOrder); ?>
					</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->products as $i => $product) : ?>
                    <tr class="row<?php echo $i % 2; ?>" sortable-group-id="1" item-id="<?php echo $product->product_id ?>" level="1">
                        
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
								<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $product->ordering; ?>" />
							<?php endif; ?>
						</td>
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $product->product_id); ?>
						</td>
						<td class="center">
							<?php echo JHtml::_('jgrid.published', $product->published, $i, 'products.'); ?>
						</td>
                        
                        <td class="center">
                            <img src="<?php echo(empty($product->image) ? $default_image : $product->image); ?>" width="80" />
                        </td>
                        <td>
                            <a href="<?php echo JRoute::_('index.php?option=com_jshopmenu&task=product.edit&product_id=' . (int) $product->product_id); ?>" >
                                <?php echo $product->name; ?>
                            </a>
                        </td>
                        <td>
                            <ul style="margin: 0px; padding: 0px; list-style: outside none none;">
                            <?php foreach($product->categories as $category): ?>
                                    <li style="margin: 0px; padding: 0px; white-space: nowrap;"><a href="<?php echo JRoute::_('index.php?option=com_jshopmenu&task=category.edit&category_id=' . (int) $category->category_id); ?>"><?=$category->name?></a></li>
                            <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <ul style="margin: 0px; padding: 0px; list-style: outside none none;">
                            <?php foreach($product->menu_types as $menu_type): ?>
                                    <li style="margin: 0px; padding: 0px; white-space: nowrap;"><a href="<?php echo JRoute::_('index.php?option=com_jshopmenu&task=menutype.edit&menu_type_id=' . (int) $menu_type->menu_type_id); ?>"><?=$menu_type->name?></a></li>
                            <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <ul style="margin: 0px; padding: 0px; list-style: outside none none;">
                            <?php foreach($product->product_types as $product_type): ?>
                                    <li style="margin: 0px; padding: 0px; white-space: nowrap;"><a href="<?php echo JRoute::_('index.php?option=com_jshopmenu&task=producttype.edit&product_type_id=' . (int) $product_type->product_type_id); ?>"><?=$product_type->name?></a></li>
                            <?php endforeach; ?>
                            </ul>
                        </td>
                        <td class="center">
                            <?php echo $product->price; ?>
                        </td>
                        <td class="center">
                            <?php echo $product->ves; ?>
                        </td>
                        <td class="center">
                            <?php echo $product->koll; ?>
                        </td>
						<td class="hidden-phone center">
						     <?php echo (int) $product->product_id; ?>
						</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
                </tr>
            </tfoot>
        </table>
        <?php endif; ?>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $this->escape($this->state->get('list.ordering')); ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->escape($this->state->get('list.direction')); ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>