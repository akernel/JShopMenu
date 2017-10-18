<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
 
// Подключаем библиотеку modellist Joomla.
jimport('joomla.application.component.modellist');
 
/**
 * Модель сообщения компонента MenuTypes.
 */
class JShopMenuModelMenuTypes extends JModelList
{
    
	/**
	 * Метод для построения SQL запроса для загрузки списка данных.
	 *
	 * @return  string  SQL запрос.
	 */
	protected function getListQuery()
	{
        // Создаем новый query объект.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Выбераем поля.
		$query->select('menu_type_id, name, description, create_your, params');

		// Из таблицы helloworld.
		$query->from('#__jshopmenu_menu_types');
        
        $query->where('published = 1');
        
        $menu_type_id = (int) $this->getState('filter.menu_type_id');
        if($menu_type_id){
            $query->where('menu_type_id = '.(int)$menu_type_id);
        }
            
		// Добавляем сортировку.
		$orderCol  = $this->getState('list.ordering', 'ordering');
		$orderDirn = $this->getState('list.direction', 'asc');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
		
	}
    
	/**
	 * Method to get a list of articles.
	 *
	 * Overriden to inject convert the attribs field into a JParameter object.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItems()
	{
        $params_global = $this->getState('params');
        $items = parent::getItems();
        if($items){
            foreach($items as $item){
                // Convert parameter fields to objects.
				$registry = new Registry;
				$registry->loadString($item->params);
                if($params_global && !empty($item->params)){
                    $item->params = clone $params_global;
                    $item->params->merge($registry);
                }else{
                    $item->params = $registry;
                }
            }
        }
        return $items;
    }
}
?>