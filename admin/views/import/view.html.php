<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
 
/**
 * HTML представление списка сообщений компонента JShopMenu.
 */
class JShopMenuViewImport extends JViewLegacy
{    
    /**
     * Меню.
     *
     * @var  array 
     */
    protected $categories;
    /**
     * Типы меню.
     *
     * @var  array 
     */
    protected $menu_types;
    
    
    
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
        try
        {
            $model = $this->getModel();
            $this->categories = $model->getCategoryes();
            $this->menu_types = $model->getMenuTypes();
            
            // Отображаем представление.
            parent::display($tpl);
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }
}