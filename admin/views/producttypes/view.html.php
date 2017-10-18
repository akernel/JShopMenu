<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
 
/**
 * HTML представление списка сообщений компонента JShopMenu.
 */
class JShopMenuViewProducttypes extends JViewLegacy
{
    /**
     * Категории.
     *
     * @var  array 
     */
    protected $producttypes;
 
    /**
     * Постраничная навигация.
     *
     * @var  object
     */
    protected $pagination;
    
    /**
     * Состояние модели.
     *
     * @var  object
     */
    protected $state;
 
    /**
     * Отображаем список сообщений.
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
        $this->producttypes = $this->get('Items');

        // Получаем объект постраничной навигации.
        $this->pagination = $this->get('Pagination');
        
        // Получаем объект состояния модели.
        $this->state = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
    
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}
        
        
        
        
        // Устанавливаем панель инструментов.
        $this->addToolBar();
		$this->sidebar = JHtmlSidebar::render();
            
        // Отображаем представление.
		return parent::display($tpl);
    }
    
     /**
      * Устанавливает панель инструментов.
      *
      * @return void
      */
     protected function addToolBar()
     {
         JToolBarHelper::title(JText::_('COM_JSHOPMENU_MANAGER_CATEGORIES'), 'producttypes');
         JToolBarHelper::addNew('producttype.add');
         JToolBarHelper::editList('producttype.edit');
         JToolBarHelper::divider();
         JToolBarHelper::deleteList('', 'producttypes.delete');
        JToolBarHelper::divider();
        JToolbarHelper::publish('producttypes.publish', 'JTOOLBAR_PUBLISH', true);
        JToolbarHelper::unpublish('producttypes.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        JToolBarHelper::divider();
        JToolBarHelper::preferences('com_jshopmenu');
     }
}