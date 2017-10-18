<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "category.cancel" || document.formvalidator.isValid(document.getElementById("item-form")))
		{
			' . $this->form->getField("description")->save() . '
			Joomla.submitform(task, document.getElementById("item-form"));
		}
	};
');
?>
<form action="<?php echo JRoute::_('index.php?option=com_jshopmenu&layout=edit&category_id=' . (int) $this->category->category_id); ?>" method="post" name="adminForm" id="item-form" class="form-validate" enctype="multipart/form-data">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
        <?php
        $fields = $this->form->getFieldset('details');
        $html = array();
    	foreach ($fields as $field)
    	{
    		$html[] = $field->renderField();
    	}
        echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_JSHOPMENU_CATEGORY_DETAILS', true));
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
        echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_JSHOPMENU_CATEGORY_IMAGES', true));
        echo implode('', $html);
        if($this->category->image != '' && !empty($this->category->image)){
            echo '<div class="control-group">';
            echo '<div class="control-label">';
            echo '<label></label>';
            echo '</div>';
            echo '<div class="controls">';
            echo '<input id="del_image" class="pull-left" name="del_image" type="checkbox" style="margin-right: 5px;">';
            echo '<label for="del_image">Установите этот флажок, чтобы удалить текущее изображение</label>';
            echo '</div>';
            echo '</div>';
            echo '<div class="control-group">';
            echo '<div class="control-label">';
            echo '<label>Просмотр</label>';
            echo '</div>';
            echo '<div class="controls">';
            echo '<img src="'.$this->category->image.'" />';
            echo '</div>';
            echo '</div>';
        }
        echo JHtml::_('bootstrap.endTab');
        ?>
        
        <?php
        $fields = $this->form->getFieldset('metadata');
        $html = array();
    	foreach ($fields as $field)
    	{
    		$html[] = $field->renderField();
    	}
        echo JHtml::_('bootstrap.addTab', 'myTab', 'metadata', 'Meta-данные');
        echo implode('', $html);
        echo JHtml::_('bootstrap.endTab');
        ?>
        
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'files', JText::_('COM_JSHOPMENU_CATEGORY_FILES', true)); ?>
        <div class="files">
            <?php foreach($this->menu_types as $menu_type):?>
            <?php $menu_type_id = $menu_type->menu_type_id; ?>
            <div class="control-group">
                <div class="control-label">
                    <label for="jform-file-<?=$menu_type->menu_type_id?>"><?=$menu_type->name?></label>
                </div>
                <div class="controls">
                    <input id="jform-file-<?=$menu_type->menu_type_id?>" name="jform[file][<?=$menu_type->menu_type_id?>]" value="<?=$this->category->file->$menu_type_id?>" />
                </div>
            </div>
            <?php endforeach; ?>
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
    </div>
    
	<div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>