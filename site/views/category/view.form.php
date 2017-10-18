<?php
// Запрет прямого доступа.
defined('_JEXEC') or die('Restricted access');
header('Content-Type: text/html; charset=utf-8');
 
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
    protected $menu_type;  
    
   /**
     * Переопределяем метод display класса JViewLegacy.
     *
     * @param   string  $tpl  Имя файла шаблона.
     *
     * @return  void
     */
    public function display($tpl = null)
    {
        
        $model = $this->getModel('category');
        $menu = JRequest::getVar('menu', array());
        
        $this->menu_type = $model->getMenuType($menu['menu_type_id']);
        
        if($this->menu_type->create_your){
            $tovars = JRequest::getVar('tovar', array());
        }else{
            $tovars = array();
        }
        
        $_SESSION['jshopmenu']['tovar'] = $tovars;
        $url_return = JRequest::getVar('url_return', '/');
        $this->assignRef('menu', $menu);
        $this->assignRef('url_return', $url_return);

        // Отображаем представление.
        parent::display('form');
        
        exit();
    }
}
?>