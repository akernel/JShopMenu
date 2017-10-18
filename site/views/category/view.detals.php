<?php
// Запрет прямого доступа.
defined('_JEXEC') or die('Restricted access');
 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
use Joomla\Registry\Registry;
 
/**
 * HTML представление сообщения компонента Category.
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
    protected $menu_type;  
 
    
    protected $params;
	protected $state;
    protected $url_return;
    protected $table_opt = array();
   /**
     * Переопределяем метод display класса JViewLegacy.
     *
     * @param   string  $tpl  Имя файла шаблона.
     *
     * @return  void
     */
    public function display($tpl = null)
    {
        $this->category      = $this->get('Category');
        $this->params        = $this->category->params;
        $this->state         = $this->get('State');
		$this->get('MenuTypes');
        
        $model = $this->getModel();
        $model->updateMenuTypes();
        
		$menu_types    = $this->get('MenuTypes');
		$this->menu_type    = $menu_types[0];
        
        $this->table_opt = array(
                    'type' => $this->params->get('table_type') ? 'dataTable' : 'table',
                    'striped' => $this->params->get('table_striped') ? ' striped' : '',
                    'border' => $this->params->get('table_border') ? ' border' : '',
                    'bordered' => $this->params->get('table_bordered') ? ' bordered' : '',
                    'hovered' => $this->params->get('table_hovered') ? ' hovered' : '',
                    'cell_hovered' => $this->params->get('table_cell_hovered') ? ' cell-hovered' : '');
        
        $this->category->file = $this->category->file->toArray();
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}
                
        $this->url_return = JRequest::getVar('url_return', $this->document->base);

        // Отображаем представление.
        parent::display($tpl);
    }
}
?>