<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
 
// Подключаем библиотеку modellist Joomla.
jimport('joomla.application.component.modellist');
 
/**
 * Модель сообщения компонента Products.
 */
class JShopMenuModelProducts extends JModelList
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
		$query->select('a.product_id, a.name, a.short_description, a.image, a.price as default_price, a.ves as default_ves, a.koll as default_koll, d.price, d.ves, d.koll, a.params');

		// Из таблицы helloworld.
		$query->from('#__jshopmenu_products as a');
        
        
        $category_id = $this->getState('filter.category_id');
        $menu_type_id = $this->getState('filter.menu_type_id');
        $product_type_id = $this->getState('filter.product_type_id');
        if($category_id || $menu_type_id || $product_type_id){
            $query->join('LEFT', '#__jshopmenu_product_to_product_type AS p ON p.product_id = a.product_id');
            $query->join('LEFT', '#__jshopmenu_product_detailed_info AS d ON d.product_id = a.product_id');
            if($category_id){
                $query->where('d.category_id='.(int)$category_id);
            }
            if($menu_type_id){
                $query->where('d.menu_type_id='.(int)$menu_type_id);
            }
            if($product_type_id){
                $query->where('p.product_type_id='.(int)$product_type_id);
            }
            $query->group('a.product_id');
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
                if(empty($item->image)){
                    $item->image = '/media/com_jshopmenu/images/noimage.gif';
                }
            }
        }
        return $items;
    }
}
?>