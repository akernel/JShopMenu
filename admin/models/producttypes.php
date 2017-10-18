<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modellist Joomla.
jimport('joomla.application.component.modellist');
 
/**
 * Модель списка сообщений компонента JShopMenu.
 */
class JShopMenuModelProducttypes extends JModelList
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
                'product_type_id', 'product_type_id',
                'name', 'name',
                'published', 'published',
                'ordering', 'ordering',
                'napitki', 'napitki',
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
        $query->select('product_type_id, name, published, ordering, napitki');
 
        // Из таблицы helloworld.
        $query->from('#__jshopmenu_product_types');
        
        // Фильтруем по состоянию.
        $published = $this->getState('filter.published');
     
        if (is_numeric($published))
        {
            $query->where('published = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(published = 0 OR published = 1)');
        }
        
        
        // Фильтруем по поиску в тексте сообщения.
        $name = $this->getState('filter.name');
     
        if (!empty($name))
        {
            $name = $db->quote('%' . $db->escape($name, true) . '%', false);
            $query->where('name LIKE ' . $name);
        }
        
        // Добавляем сортировку.
        $orderCol  = $this->state->get('list.ordering', 'ordering');
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
        
        parent::populateState('ordering', 'asc');
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
     
        return parent::getStoreId($id);
    }
}
?>