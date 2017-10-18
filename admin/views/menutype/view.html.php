<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
 
/**
 * HTML представление редактирования сообщения.
 */
class JShopMenuViewMenutype extends JViewLegacy
{
    /**
     * Сообщение.
     *
     * @var  object
     */
    protected $menutype;
 
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
        // Получаем данные из модели.
        $this->form = $this->get('Form');
        $this->menutype = $this->get('Item');
 
        
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}
		JFactory::getApplication()->input->set('hidemainmenu', true);
        
		// Устанавливаем панель инструментов.
		$this->addToolBar();

		return parent::display($tpl);
    }
 
    /**
     * Устанавливает панель инструментов.
     *
     * @return  void
     */
    protected function addToolBar()
    {
        $isNew = ($this->menutype->menu_type_id == 0);
 
        JToolBarHelper::title($isNew ? JText::_('COM_JSHOPMENU_MANAGER_MENUTYPE_NEW') : JText::_('COM_JSHOPMENU_MANAGER_MENUTYPE_EDIT'), 'menutype');
        JToolBarHelper::apply('menutype.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('menutype.save');
        JToolBarHelper::cancel('menutype.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}