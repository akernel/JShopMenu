<?php
// Запрет прямого доступа.
defined('_JEXEC') or die('Restricted access');
 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
 
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
    protected $file;  
    
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
            $menu_type_id = JRequest::getVar('menu_type_id', 0);
            $category_id = JRequest::getVar('category_id', 0);
            if($menu_type_id == 0 || $category_id == 0){
                echo 'Файл не найден!';
                exit();
            }
            
            $model = $this->getModel('category');
            $pathFile = $model->getPathFile($menu_type_id,$category_id);
            
            $exp = explode('/',$pathFile);
            $name = end($exp);
            
            $this->file = JPATH_BASE.$pathFile;
            
            if(!file_exists($this->file)){
                echo 'Файл не найден!';
                exit();
            }
            
            // Получаем параметры приложения.
            $app          = JFactory::getApplication();
            $this->params = $app->getParams();
 
            // Подготавливаем документ.
            $this->_prepareDocument();
    
            // Отображаем представление.
            parent::display('file');
        }
        catch (Exception $e)
        {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_JSHOPMENU_ERROR_OCCURRED'), 'error');
            JLog::add($e->getMessage(), JLog::ERROR, 'com_helloworld');
        }
    }
    
    /**
     * Подготавливает документ.
     *
     * @return  void
     */
    protected function _prepareDocument()
    {
        $app   = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;
 
        // Так как приложение устанавливает заголовок страницы по умолчанию,
        // мы получаем его из пункта меню.
        $menu = $menus->getActive();
 
        if ($menu)
        {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        }
        else
        {
            $this->params->def('page_heading', JText::_('COM_JSHOPMENU_DEFAULT_PAGE_TITLE'));
        }
 
        // Получаем заголовок страницы в браузере из параметров.
        $title = $this->params->get('page_title', '');
 
        if (empty($title))
        {
            $title = $app->getCfg('sitename');
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
        {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
        {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
 
        if (empty($title))
        {
            $title = $this->category;
        }
 
        // Устанавливаем заголовок страницы в браузере.
        $this->document->setTitle($title);
 
        // Добавляем поддержку метаданных из пункта меню.
        if ($this->params->get('menu-meta_description'))
        {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }
 
        if ($this->params->get('menu-meta_keywords'))
        {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }
 
        if ($this->params->get('robots'))
        {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
        
    }
}
?>