<?php
// Запрет прямого доступа.
defined('_JEXEC') or die('Restricted access');
 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
 
/**
 * HTML представление сообщения компонента File.
 */
class JShopMenuViewFile extends JViewLegacy
{
    /**
     * Сообщение.
     *
     * @var  object
     */
    protected $file_path;  
    protected $file_name;  
    protected $file_src;  
    protected $menu_type_id;  
    protected $category_id;  
    
   /**
     * Переопределяем метод display класса JViewLegacy.
     *
     * @param   string  $tpl  Имя файла шаблона.
     *
     * @return  void
     */
    public function display($tpl = null)
    {
        try
        {
            $this->menu_type_id = JRequest::getVar('menu_type_id', 0);
            $this->category_id = JRequest::getVar('category_id', 0);
            if($this->menu_type_id == 0 || $this->category_id == 0){
                echo 'Файл не найден!';
                exit();
            }
            
            $model = $this->getModel();
            $this->file_src = $model->getPathFile($this->menu_type_id,$this->category_id);
            
            $exp = explode('/',$this->file_src);
            $this->file_name = end($exp);
            
            $this->file_path = JPATH_BASE.DIRECTORY_SEPARATOR.$this->file_src;
            
            if(!file_exists($this->file_path)){
                echo 'Файл не найден!';
                exit();
            }
            
            // Получаем параметры приложения.
            $app          = JFactory::getApplication();
            $this->params = $app->getParams();
 
            $this->document->setTitle($this->file_name);
    
            // Отображаем представление.
            parent::display();
        }
        catch (Exception $e)
        {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_JSHOPMENU_ERROR_OCCURRED'), 'error');
            JLog::add($e->getMessage(), JLog::ERROR, 'com_helloworld');
        }
    }
}
?>