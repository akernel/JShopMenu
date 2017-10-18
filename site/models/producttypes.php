<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
 
// Подключаем библиотеку modellist Joomla.
jimport('joomla.application.component.modellist');
 
/**
 * Модель сообщения компонента ProductTypes.
 */
class JShopMenuModelProductTypes extends JModelList
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
		$query->select('a.product_type_id, a.name, a.napitki, a.params');

		// Из таблицы helloworld.
		$query->from('#__jshopmenu_product_types as a');
        
        
        $category_id = $this->getState('filter.category_id');
        $menu_type_id = $this->getState('filter.menu_type_id');
        if($category_id || $menu_type_id){
            $query->join('LEFT', '#__jshopmenu_product_to_product_type AS p ON p.product_type_id = a.product_type_id');
            $query->join('LEFT', '#__jshopmenu_product_to_menu_type AS m ON m.product_id = p.product_id');
            if($category_id){
                $query->where('m.category_id='.(int)$category_id);
            }
            if($menu_type_id){
                $query->where('m.menu_type_id='.(int)$menu_type_id);
            }
            $query->group('a.product_type_id');
        }
        
        $query->where('published = 1');
        
        
		// Добавляем сортировку.
		$orderCol  = $this->getState('list.ordering', 'a.ordering');
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
        $new_items=array();
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
                $new_items[$item->product_type_id] = $item;
            }
            return $new_items;
        }
        return false;
    }
}
?>