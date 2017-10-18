<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modelitem Joomla.
jimport('joomla.application.component.modelitem');
 
/**
 * Модель сообщения компонента JShopMenu.
 */
class JShopMenuModelCategories extends JModelItem
{
    /**
     * Возвращает ссылку на объект таблицы.
     *
     * @param   string  $type    Тип таблицы.
     * @param   string  $prefix  Префикс имени класса таблицы. Необязателен.
     * @param   array   $config  Конифгурационный массив для таблицы. Необязателен.
     *
     * @return  JTable  Объект таблицы.
     */
    public function getTable($type = 'Category', $prefix = 'JShopMenuTable', $config = array()) 
    {
        return JTable::getInstance($type, $prefix, $config);
    }
    
    public function getCategories(){
        // Конструируем SQL запрос.
        $query = $this->_db->getQuery(true);
        $query->select('*')
                ->from('#__jshopmenu_categories')
                ->where('published = 1')
                ->order($this->_db->escape('ordering asc'));
        $this->_db->setQuery($query);
        $categories = $this->_db->loadObjectList();
        
        // Генерируем исключение, если сообщение не найдено.
        if (empty($categories))
        {
            throw new Exception(JText::_('COM_HELLOWORLD_ERROR_MESSAGE_NOT_FOUND'), 404);
        }

        foreach($categories as $key=>$category){
            
            // Загружаем JSON строку параметров.
            $params = new JRegistry;
            $params->loadString($category->params);
            $category->params = $params;
 
            // Объединяем глобальные параметры с индивидуальными.
            $params = clone $this->getState('params');
            $params->merge($category->params);
            $category->params = $params;
            
            if(empty($category->image)) $category->image = '/media/com_jshopmenu/images/noimage.gif';
            
            $categories[$key] = $category;
        }
        
        return $categories;
    }
    
    /**
     * Метод для авто-заполнения состояния модели.
     *
     * Заметка. Вызов метода getState в этом методе приведет к рекурсии.
     *
     * @return  void
     */
    protected function populateState()
    {
        $app = JFactory::getApplication();
 
        // Загружаем глобальные параметры.
        $params = $app->getParams();
        
        // Добавляем параметры в состояние модели.
        $this->setState('params', $params);
 
        parent::populateState();
    }
    
}