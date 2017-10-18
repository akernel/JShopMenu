<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modellist Joomla.
jimport('joomla.application.component.modellist');
 
/**
 * Модель списка сообщений компонента JShopMenu.
 */
class JShopMenuModelMenutypes extends JModelList
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
                'menu_type_id', 'a.menu_type_id',
                'name', 'a.name',
                'published', 'a.published',
                'ordering', 'a.ordering',
                'create_your', 'a.create_your',
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
        $query->select('a.menu_type_id, a.name, a.published, a.ordering, a.create_your');
 
        // Из таблицы menu_types.
        $query->from('#__jshopmenu_menu_types AS a');
        
        // Фильтруем по состоянию.
        $published = $this->getState('filter.published');
     
        if (is_numeric($published))
        {
            $query->where('a.published = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(a.published = 0 OR a.published = 1)');
        }
        
        
        // Фильтруем по поиску в тексте сообщения.
        $name = $this->getState('filter.name');
     
        if (!empty($name))
        {
            $name = $db->quote('%' . $db->escape($name, true) . '%', false);
            $query->where('a.name LIKE ' . $name);
        }
        
        // Добавляем сортировку.
        $orderCol  = $this->state->get('list.ordering', 'a.ordering');
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
        
        parent::populateState('a.ordering', 'asc');
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