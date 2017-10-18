<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

?>
<form action="<?php echo JRoute::_('index.php?option=com_jshopmenu&layout=edit&product_type_id=' . (int) $this->producttype->product_type_id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
        
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_JSHOPMENU_PRODUCTTYPE_DETAILS', true)); ?>
        <?php
        $fields = $this->form->getFieldset('details');
        $html = array();
    	foreach ($fields as $field)
    	{
    		$html[] = $field->renderField();
    	}
        echo implode('', $html);
        ?>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'params', JText::_('COM_JSHOPMENU_EDIT_PARAMS', true)); ?>
        <?php
        $fields = $this->form->getFieldset('params');
        $html = array();
    	foreach ($fields as $field)
    	{
    		$html[] = $field->renderField();
    	}
        echo implode('', $html);
        ?>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
     </div>
     
     
	<div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>