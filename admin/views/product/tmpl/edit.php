<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

// Получаем параметры из формы.
$params = $this->form->getFieldsets('params');
$select_categoryes=false;
?>
<form action="<?php echo JRoute::_('index.php?option=com_jshopmenu&layout=edit&product_id=' . (int) $this->product->product_id); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate">
<div class="form-horizontal">
    <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
    
    <?php
    $fields = $this->form->getFieldset('details');
    $html = array();
	foreach ($fields as $field)
	{
		$html[] = $field->renderField();
	}
    echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_JSHOPMENU_PRODUCT_DETAILS', true));
    echo implode('', $html);
    echo JHtml::_('bootstrap.endTab');
    ?>
    
    <?php
    $fields = $this->form->getFieldset('images');
    $html = array();
	foreach ($fields as $field)
	{
		$html[] = $field->renderField();
	}
    echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_JSHOPMENU_PRODUCT_FIELD_IMAGE_LABEL', true));
    echo implode('', $html);
    echo JHtml::_('bootstrap.endTab');
    ?>
    
    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'info', JText::_('COM_JSHOPMENU_PRODUCT_INFO_DETALS_LABEL', true)); ?>
    <div class="info">
        <fieldset>
            <legend><?php echo JText::_('COM_JSHOPMENU_PRODUCT_INFO_DETAL_DESC') ?></legend>
                <div class="control-group">
                    <div class="control-label">
                        <label for="jform_category-ids"><?php echo JText::_('COM_JSHOPMENU_PRODUCT_CATEGORI_IDS_LABEL') ?></label>
                    </div> 
                    <div class="controls">
                        <select id="jform_category-ids" name="jform[category_ids][]" multiple="multiple">
                            <option value="0" disabled="disabled"><?php echo JText::_('COM_JSHOPMENU_PRODUCT_CATEGORI_IDS_DESC') ?></option>
                            <?php foreach($this->categories as $category): ?>
                            <option value="<?=$category['value']?>"<?php echo((bool)$category['selected'] ? ' selected="selected"' : ''); ?>><?=$category['text']?></option>
                            <?php if($category['selected']) $select_categoryes=true; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php if($select_categoryes): ?>
                <?php foreach ($this->categories as $category): ?>
                    <?php if($category['selected']==1): ?>
                <div class="control-group">
                    <div class="control-label">
                        <label for="jform_menu_type-ids-<?=$category['value']?>"><?=$category['text']?></label>
                    </div>
                    <div class="controls">
                        <select id="jform_menu_type-ids-<?=$category['value']?>" name="jform[menu_type_ids][<?=$category['value']?>][]" multiple="multiple">
                            <option value="0" disabled="disabled"><?php echo JText::_('COM_JSHOPMENU_PRODUCT_MENU_TYPE_IDS_DESC') ?></option>
                            <?php foreach($this->menu_types[$category['value']] as $menu_type): ?>
                            <option value="<?=$menu_type['value']?>"<?php echo((bool)$menu_type['selected'] ? ' selected="selected"' : ''); ?>><?=$menu_type['text']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php else: ?>
                    <div id="system-message">
            			<div class="notice">Внимание</dt>
            			<div class="notice message fade">
            				<ul>
            					<li>Не одного меню не выбрана, выберете хотябы одно меню для выбора типов меню для каждого меню</li>
            				</ul>
            			</div>
            		</div>
                <?php endif; ?>
                <div class="control-group">
                    <div class="control-label">
                        <label for="jform_product_type-ids"><?php echo JText::_('COM_JSHOPMENU_PRODUCT_PRODUCT_TYPE_IDS_LABEL') ?></label>
                    </div>
                    <div class="controls">
                        <select id="jform_product_type-ids" name="jform[product_type_ids][]" multiple="multiple">
                            <option value="0" disabled="disabled"><?php echo JText::_('COM_JSHOPMENU_PRODUCT_PRODUCT_TYPE_IDS_DESC') ?></option>
                            <?php foreach($this->product_types as $product_type): ?>
                            <option value="<?=$product_type['value']?>"<?php echo((bool)$product_type['selected'] ? ' selected="selected"' : ''); ?>><?=$product_type['text']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
        </fieldset>
        
        <fieldset>
            <legend><?php echo JText::_('COM_JSHOPMENU_PRODUCT_INFO_DETALS_DESC') ?></legend>
            <table class="adminform" width="100%">
                <tr>
                    <td>
                    <?php if($this->categories): ?>
                        <?php foreach($this->categories as $category): ?>
                        <?php if($category['selected']==1): ?>
                            <fieldset style="border: 2px solid darkred;">
                                <legend style="color: darkred;"><?=$category['text']?></legend>
                                <table width="100%">
                                    <?php foreach($this->menu_types[$category['value']] as $menu_type): ?>
                                    <?php if($menu_type['selected']==1): ?>
                                    <?php
                                        $option = $this->model->getOptions($this->product->product_id,$menu_type['value'],$category['value']);
                                    ?>
                                    <tr>
                                        <td>
                                            <fieldset style="border: 2px solid darkgrey;">
                                                <legend><?=$menu_type['text']?></legend>
                                                <table width="100%">
                                                    <tr>
                                                        <td>Цена одной порции</td>
                                                        <td><input type="text" name="jform[product_info][<?=$category['value']?>][<?=$menu_type['value']?>][price]" value="<?php echo ($option->price ? $option->price : 0.00); ?>" placeholder="0.00" /></td>
                                                        
                                                        <td>Выход одной порции</td>
                                                        <td><input type="text" name="jform[product_info][<?=$category['value']?>][<?=$menu_type['value']?>][ves]" value="<?php echo ($option->ves ? $option->ves : 0.00); ?>" placeholder="0.00" /></td>
                                                        
                                                        <td>Количество порций на одну персону</td>
                                                        <td><input type="text" name="jform[product_info][<?=$category['value']?>][<?=$menu_type['value']?>][koll]" value="<?php echo ($option->koll ? $option->koll : 0); ?>" placeholder="0" /></td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </table>
                            </fieldset>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </td>
                </tr>
            </table>
        
        </fieldset>
    </div>
    <?php echo JHtml::_('bootstrap.endTab'); ?>
    
    <?php
    $fields = $this->form->getFieldset('params');
    $html = array();
	foreach ($fields as $field)
	{
		$html[] = $field->renderField();
	}
    echo JHtml::_('bootstrap.addTab', 'myTab', 'params', JText::_('COM_JSHOPMENU_EDIT_PARAMS', true));
    echo implode('', $html);
    echo JHtml::_('bootstrap.endTab');
    ?>
    
    <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</div>
</form>