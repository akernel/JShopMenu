<?php
// Запрет прямого доступа.
defined('_JEXEC') or die('Restricted access');
header('Content-Type: text/html; charset=utf-8');
 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
 
/**
 * HTML представление сообщения компонента Category.
 */
class JShopMenuViewForm extends JViewLegacy
{
    /**
     * Сообщение.
     *
     * @var  object
     */
    protected $category_id;
    protected $menu_type_id;
    
    /**
     * Сообщение.
     *
     * @var  object
     */
    protected $category;  
    protected $menu_type;  
 
    
    protected $params;
	protected $state;
    protected $create_your = 0;
    protected $kol_person = 0;
    protected $url_return = '/';
	protected $form;
    
    protected $fields;
    
   /**
     * Переопределяем метод display класса JViewLegacy.
     *
     * @param   string  $tpl  Имя файла шаблона.
     *
     * @return  void
     */
    public function display($tpl = null)
    {   
		$this->form   = $this->get('Form');
        
        $session = JFactory::getSession();
        $model = $this->getModel();
        
        $this->category_id = JRequest::getVar('category_id');
        $this->menu_type_id = JRequest::getVar('menu_type_id');
        
        $this->category      = $this->get('Category');
        $this->menu_type      = $model->getMenuType($this->menu_type_id);
        
        $this->create_your = JRequest::getVar('create_your', 0);
        
        if($this->create_your){
            $koll = JRequest::getVar('koll',array());
            $session->set( 'jshopmenu.form.koll', $koll );
        }
        
        $this->kol_person = JRequest::getVar('kol_person', $this->category->koll_person);
        $this->url_return = JRequest::getVar('url_return', '/');
                
        
        $this->params        = $this->category->params;
        $this->state         = $this->get('State');
                
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}
      
        // Отображаем представление.
        parent::display();
    }
}
?>