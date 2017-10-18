<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
 
/**
 * HTML представление редактирования сообщения.
 */
class JShopMenuViewProduct extends JViewLegacy
{
    /**
     * Сообщение.
     *
     * @var  object
     */
    protected $product;
    /**
     * Сообщение.
     *
     * @var  object
     */
    protected $categories;
    /**
     * Сообщение.
     *
     * @var  object
     */
    protected $menu_types;
    /**
     * Сообщение.
     *
     * @var  object
     */
    protected $product_types;
    /**
     * Сообщение.
     *
     * @var  object
     */
    protected $model;
 
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
        $this->product = $this->get('Item');
        
        $this->model = $this->getModel();
        $isNew = ($this->product->product_id == 0);
        if($isNew){
            $this->categories = $this->model->getCategories($this->product->product_id, true);
            $this->product_types = $this->model->getProductTypes($this->product->product_id, true);
        }else{
            $this->categories = $this->model->getCategories($this->product->product_id);
            $category_ids = array();
            foreach ($this->categories as $category){
                if($category['selected'])
                    $category_ids[] = $category['value'];
            }
            $this->menu_types = $this->model->getMenuTypes($this->product->product_id,$category_ids);
            $this->product_types = $this->model->getProductTypes($this->product->product_id);
        }
        
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}
		JFactory::getApplication()->input->set('hidemainmenu', true);

        // Устанавливаем панель инструментов.
        $this->addToolBar();
        // Отображаем представление.

		return parent::display($tpl);
    }
 
    /**
     * Устанавливает панель инструментов.
     *
     * @return  void
     */
    protected function addToolBar()
    {
        $isNew = ($this->product->product_id == 0);
 
        JToolBarHelper::title($isNew ? JText::_('COM_JSHOPMENU_MANAGER_PRODUCT_NEW') : JText::_('COM_JSHOPMENU_MANAGER_PRODUCT_EDIT'), 'product');
        JToolBarHelper::apply('product.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('product.save');
        JToolBarHelper::cancel('product.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}