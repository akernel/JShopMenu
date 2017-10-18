<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Загружаем тултипы.
JHtml::_('behavior.tooltip'); 

// Загружаем проверку формы.
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

// Получаем параметры из формы.
$params = $this->form->getFieldsets('params');
?>
<form action="<?php echo JRoute::_('index.php?option=com_jshopmenu&layout=edit&menu_type_id=' . (int) $this->menutype->menu_type_id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
        
        <?php
        $fields = $this->form->getFieldset('details');
        $html = array();
    	foreach ($fields as $field)
    	{
    		$html[] = $field->renderField();
    	}
        echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_JSHOPMENU_MENUTYPE_DETAILS', true));
        echo implode('', $html);
        echo JHtml::_('bootstrap.endTab');
        ?>
    
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