<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
 
/**
 * HTML представление редактирования сообщения.
 */
class JShopMenuViewProducttype extends JViewLegacy
{
    /**
     * Сообщение.
     *
     * @var  object
     */
    protected $producttype;
 
    /**
     * Объект формы.
     *
     * @var  array
     */
    protected $form;
 
    /**
     * Отображает представление.
     *
     * @param   string  $tpl  Имя файла шаблона.
     *
     * @return  void
     *
     * @throws  Exception
     */
    public function display($tpl = null)
    {
        try
        {
            // Получаем данные из модели.
            $this->form = $this->get('Form');
            $this->producttype = $this->get('Item');
 
            // Устанавливаем панель инструментов.
            $this->addToolBar();
 
            // Отображаем представление.
            parent::display($tpl);
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }
 
    /**
     * Устанавливает панель инструментов.
     *
     * @return  void
     */
    protected function addToolBar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);
        $isNew = ($this->producttype->product_type_id == 0);
 
        JToolBarHelper::title($isNew ? JText::_('COM_JSHOPMENU_MANAGER_PRODUCTTYPE_NEW') : JText::_('COM_JSHOPMENU_MANAGER_PRODUCTTYPE_EDIT'), 'producttype');
        JToolBarHelper::apply('producttype.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('producttype.save');
        JToolBarHelper::cancel('producttype.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}