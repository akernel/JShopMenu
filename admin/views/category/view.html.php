<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
 
/**
 * HTML представление редактирования сообщения.
 */
class JShopMenuViewCategory extends JViewLegacy
{
    /**
     * Сообщение.
     *
     * @var  object
     */
    protected $category;
    /**
     * Сообщение.
     *
     * @var  object
     */
    protected $menu_types;
 
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
        $this->category = $this->get('Item');
        
        if(isset($this->category->file)){
            $this->category->file = json_decode($this->category->file);
        }
        
        $model = $this->getModel();
        $this->menu_types = $model->getMenuTypes();
        
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
        $isNew = ($this->category->category_id == 0);
 
        JToolBarHelper::title($isNew ? JText::_('COM_JSHOPMENU_MANAGER_CATEGORY_NEW') : JText::_('COM_JSHOPMENU_MANAGER_CATEGORY_EDIT'), 'category');
        JToolBarHelper::apply('category.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('category.save');
        JToolBarHelper::cancel('category.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}