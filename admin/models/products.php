<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modellist Joomla.
jimport('joomla.application.component.modellist');
 
/**
 * Модель списка сообщений компонента JShopMenu.
 */
class JShopMenuModelProducts extends JModelList
{
    /**
     * Конструктор.
     *
     * @param   array  $config  Массив с конфигурационными параметрами.
     */
    public function __construct($config = array())
    {
        // Добавляем валидные поля для фильтров и сортировки.
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'product_id', 'p.product_id',
                'name', 'p.name',
                'published', 'p.published',
                'ordering', 'p.ordering',
                'price', 'p.price',
                'ves', 'p.ves',
                'koll', 'p.koll',
            );
        }
        parent::__construct($config);
    }
    /**
     * Метод для построения SQL запроса для загрузки списка данных.
     *
     * @return  string  SQL запрос.
     */
    protected function getListQuery()
    {
        // Создаем новый query объект.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
 
        // Выбераем поля.
        $query->select('p.product_id, p.name, p.published, p.ordering, p.image, p.price, p.ves, p.koll');
 
        // Из таблицы helloworld.
        $query->from('#__jshopmenu_products AS p');
 
        // Фильтруем по состоянию.
        $published = $this->getState('filter.published');
     
        if (is_numeric($published))
        {
            $query->where('p.published = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(p.published = 0 OR p.published = 1)');
        }
        
        $category = $this->getState('filter.category');
        if($category){
            $query->leftJoin('#__jshopmenu_product_to_category AS c ON c.product_id = p.product_id');
            $query->where('c.category_id = '.$category);
        }
        $menu_type = $this->getState('filter.menu_type');
        if($menu_type){
            $query->leftJoin('#__jshopmenu_product_to_menu_type AS mt ON mt.product_id = p.product_id');
            if($category){
                $query->where('mt.category_id = '.$category);
            }
            $query->where('mt.menu_type_id = '.$menu_type);
        }
        $product_type = $this->getState('filter.product_type');
        if($product_type){
            $query->leftJoin('#__jshopmenu_product_to_product_type AS pt ON pt.product_id = p.product_id');
            $query->where('pt.product_type_id = '.$product_type);
        }
        
        // Фильтруем по поиску в тексте сообщения.
        $name = $this->getState('filter.name');
     
        if (!empty($name))
        {
            $name = $db->quote('%' . $db->escape($name, true) . '%', false);
            $query->where('p.name LIKE ' . $name);
        }
        
        // Добавляем сортировку.
        $orderCol  = $this->state->get('list.ordering', 'p.ordering');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));
        
        return $query;
    }
    /**
     * Метод для авто-заполнения состояния модели.
     *
     * @param   string  $ordering   Поле сортировки.
     * @param   string  $direction  Направление сортировки (asc|desc).
     *
     * @return  void
     */
    protected function populateState($ordering = null, $direction = null)
    {
        // Получаем и устанавливаем значение фильтра состояния.
        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
        $this->setState('filter.published', $published);
          
        // Получаем и устанавливаем значение фильтра поиска по тексту сообщения.
        $name = $this->getUserStateFromRequest($this->context . '.filter.name', 'filter_name');
        $this->setState('filter.name', $name);
        
        
        // Получаем и устанавливаем значение фильтра состояния.
        $category = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', '');
        $this->setState('filter.category', $category);
        // Получаем и устанавливаем значение фильтра состояния.
        $menu_type = $this->getUserStateFromRequest($this->context . '.filter.menu_type_id', 'filter_menu_type_id', '');
        $this->setState('filter.menu_type', $menu_type);
        // Получаем и устанавливаем значение фильтра состояния.
        $product_type = $this->getUserStateFromRequest($this->context . '.filter.product_type_id', 'filter_product_type_id', '');
        $this->setState('filter.product_type', $product_type);
        
        parent::populateState('p.ordering', 'asc');
    }
    
    /**
     * Метод для получения store id, которое основывается на состоянии модели.
     *
     * @param   string  $id  Идентификационная строка для генерации store id.
     *
     * @return  string  Store id.
     */
    protected function getStoreId($id = '')
    {
        // Компилируем store id.
        $id .= ':' . $this->getState('filter.name');
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.categories');
        $id .= ':' . $this->getState('filter.menu_types');
        $id .= ':' . $this->getState('filter.product_types');
     
        return parent::getStoreId($id);
    }
    
    public function getCategoriesToProduct($product_id = null){
        $query = $this->_db->getQuery(true);
        $query->select('c.category_id, c.name')
                ->from('#__jshopmenu_categories AS c');
                
        if($product_id){
            $query->leftJoin('#__jshopmenu_product_to_category AS h ON c.category_id = h.category_id')
                    ->where('h.product_id = '.$product_id);
        }
        
        $query->order($this->_db->escape('ordering asc'));
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }
    public function getMenuTypesToProduct($product_id = null){
        $query = $this->_db->getQuery(true);
        $query->select('mt.menu_type_id, mt.name')
                ->from('#__jshopmenu_menu_types AS mt');
                
        if($product_id){
            $query->leftJoin('#__jshopmenu_product_to_menu_type AS h ON mt.menu_type_id = h.menu_type_id')
                    ->where('h.product_id = '.$product_id)
                    ->group($this->_db->quoteName('menu_type_id'));
        }      
                
        $query->order($this->_db->escape('ordering asc'));
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }
    public function getProductTypesToProduct($product_id = null){
        $query = $this->_db->getQuery(true);
        $query->select('pt.product_type_id, pt.name')
                ->from('#__jshopmenu_product_types AS pt');
                
        if($product_id){
            $query->leftJoin('#__jshopmenu_product_to_product_type AS h ON pt.product_type_id = h.product_type_id')
                    ->where('h.product_id = '.$product_id);
        }      
                
        $query->order($this->_db->escape('ordering asc'));
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }
}
?>