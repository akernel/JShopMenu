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
    protected $menu_types;  
 
    
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
        
		$this->menu_types    = $this->get('MenuTypes');
        
        $table_length_menu = $this->params->get('table_new_length_menu', '[5, 10, 25, -1]');
        $to_array = json_decode($table_length_menu);
        $text_array = array();
        foreach($to_array as $val){
            if($val==-1){
                $text_array[]='Все';
            }else{
                $text_array[]=$val;
            }
        }
        $table_length_menu = array($to_array,$text_array);
        $table_length_menu = json_encode($table_length_menu);
        
        $this->table_opt = array(
                    'type' => $this->params->get('table_type') ? 'dataTable' : 'table',
                    'striped' => $this->params->get('table_striped') ? ' striped' : '',
                    'border' => $this->params->get('table_border') ? ' border' : '',
                    'bordered' => $this->params->get('table_bordered') ? ' bordered' : '',
                    'hovered' => $this->params->get('table_hovered') ? ' hovered' : '',
                    'cell_hovered' => $this->params->get('table_cell_hovered') ? ' cell-hovered' : '',
                    'new_search' => $this->params->get('table_new_search') ? '"searching":true,' : '"searching":false,',
                    'new_paginator' => $this->params->get('table_new_paginator') ? '"paging":true,' : '"paging":false,',
                    'new_info' => $this->params->get('table_new_paginator') ? '"info":true,' : '"info":false,',
                    'new_length' => $this->params->get('table_new_length', 5),
                    'new_length_menu' => $table_length_menu);
                    
        $this->category->file = $this->category->file->toArray();
        
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}
        
        $this->url_return = $this->document->base;

        // Подготавливаем документ.
        $this->_prepareDocument();

        // Отображаем представление.
        parent::display($tpl);
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
            $title = $this->category->name;
        }
 
        // Устанавливаем заголовок страницы в браузере.
        if($this->category->metatitle){
            $title = $this->category->metatitle;
        }
        $this->document->setTitle($title);
 
        // Добавляем поддержку метаданных из пункта меню.
        if($this->category->metadesc){
            $this->document->setDescription($this->category->metadesc);
        }
        elseif ($this->params->get('menu-meta_description'))
        {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }
 
        if($this->category->metakey){
            $this->document->setMetadata('keywords', $this->category->metakey);
        }
        elseif ($this->params->get('menu-meta_keywords'))
        {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }
 
        if ($this->params->get('robots'))
        {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
        if($this->params->get('table_type')==1)
            $this->document->addScript('/templates/eminent/media/metro/js/jquery.dataTables.min.js');
        
        
    }
}
?>