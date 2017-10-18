<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
 
/**
 * HTML представление списка сообщений компонента JShopMenu.
 */
class JShopMenuViewProducts extends JViewLegacy
{
    /**
     * Категории.
     *
     * @var  array 
     */
    protected $products;
 
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
        $this->products = $this->get('Items');
        // Получаем объект постраничной навигации.
        $this->pagination = $this->get('Pagination');
        
        // Получаем объект состояния модели.
        $this->state = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
        
        $model = $this->getModel();
        
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}
        
    
        foreach($this->products as $key=>$product){
            $product->categories = $model->getCategoriesToProduct($product->product_id);
            $product->menu_types = $model->getMenuTypesToProduct($product->product_id);
            $product->product_types = $model->getProductTypesToProduct($product->product_id);
            $this->products[$key] = $product;
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
        JToolBarHelper::title(JText::_('COM_JSHOPMENU_MANAGER_CATEGORIES'), 'products');
        JToolBarHelper::addNew('product.add');
        JToolBarHelper::editList('product.edit');
        JToolBarHelper::divider();
        JToolbarHelper::publish('products.publish', 'JTOOLBAR_PUBLISH', true);
        JToolbarHelper::unpublish('products.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        JToolbarHelper::custom('products.featured', 'featured.png', 'featured_f2.png', 'JFEATURE', true);
        JToolbarHelper::custom('products.unfeatured', 'unfeatured.png', 'featured_f2.png', 'JUNFEATURE', true);
        JToolBarHelper::divider();
        JToolbarHelper::archiveList('products.archive');
        
        if ($this->state->get('filter.published') == -2)
		{
            JToolBarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'products.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		else
		{
			JToolbarHelper::trash('products.trash');
		}
        
        
        JToolBarHelper::divider();
        JToolBarHelper::preferences('com_jshopmenu');
        
         
            JToolBarHelper::divider();
            JToolbarHelper::publish('products.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('products.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            
            
     }
}