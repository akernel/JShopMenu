<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modeladmin Joomla.
jimport('joomla.application.component.modeladmin');
 
require_once __DIR__ . '/products.php';
/**
 * Модель списка сообщений компонента JShopMenu.
 */
class JShopMenuModelImport extends JShopMenuModelProducts
{
    public function getCategoryes (){
        // Получаем объект базы данных.
        $db = JFactory::getDbo();
        // Конструируем SQL запрос.
        $query = $db->getQuery(true);
        $query->select('category_id, name')
                ->from('#__jshopmenu_categories');
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    public function getMenuTypes (){
        // Получаем объект базы данных.
        $db = JFactory::getDbo();
        // Конструируем SQL запрос.
        $query = $db->getQuery(true);
        $query->select('menu_type_id, name')
                ->from('#__jshopmenu_menu_types');
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
	/**
	* Load a single product_type_id
	*/
    public function getProductTypeIdToName($name = null){
        if(empty($name)){
            return false;
        }
        // Получаем объект базы данных.
        $db = JFactory::getDbo();
        $name = $db->quote($db->escape($name, true), false);
        // Конструируем SQL запрос.
        $query = $db->getQuery(true);
        $query->select('product_type_id')
                ->from('#__jshopmenu_product_types')
                ->where('name LIKE ' . $name);
        $db->setQuery($query);
        return $db->loadResult();
    }
	/**
	* Load a single product_id
	*/
    public function getProductIdToName($name = null){
        if(empty($name)){
            return false;
        }
        // Получаем объект базы данных.
        $db = JFactory::getDbo();
        $name = $db->quote($db->escape($name, true), false);
        // Конструируем SQL запрос.
        $query = $db->getQuery(true);
        $query->select('product_id')
                ->from('#__jshopmenu_products')
                ->where('name LIKE ' . $name);
        $db->setQuery($query);
        return $db->loadResult();
    }
    
    public function ProductToCategory($product_id = 0, $category_id = 0){
        // Получаем объект базы данных.
        $db = JFactory::getDbo();
        // Конструируем SQL запрос.
        $query = $db->getQuery(true);
        $query->select('id')
                ->from('#__jshopmenu_product_to_category')
                ->where('(product_id = '.$product_id.' AND category_id = '.$category_id.')');;
        $db->setQuery($query);
        return $db->loadResult();
    }
    
    public function ProductToMenuType($product_id = 0, $category_id = 0, $menu_type_id = 0){
        // Получаем объект базы данных.
        $db = JFactory::getDbo();
        // Конструируем SQL запрос.
        $query = $db->getQuery(true);
        $query->select('id')
                ->from('#__jshopmenu_product_to_menu_type')
                ->where('(product_id = '.$product_id.' AND category_id = '.$category_id.' AND menu_type_id = '.$menu_type_id.')');;
        $db->setQuery($query);
        return $db->loadResult();
    }
    
    public function ProductToProductType($product_id = 0, $product_type_id = 0){
        // Получаем объект базы данных.
        $db = JFactory::getDbo();
        // Конструируем SQL запрос.
        $query = $db->getQuery(true);
        $query->select('id')
                ->from('#__jshopmenu_product_to_product_type')
                ->where('(product_id = '.$product_id.' AND product_type_id = '.$product_type_id.')');;
        $db->setQuery($query);
        return $db->loadResult();
    }
    
    public function ProductDetalsInfo($product_id = 0, $category_id = 0, $menu_type_id = 0){
        // Получаем объект базы данных.
        $db = JFactory::getDbo();
        // Конструируем SQL запрос.
        $query = $db->getQuery(true);
        $query->select('id')
                ->from('#__jshopmenu_product_detailed_info')
                ->where('(product_id = '.$product_id.' AND category_id = '.$category_id.' AND menu_type_id = '.$menu_type_id.')');;
        $db->setQuery($query);
        return $db->loadResult();
    }
    
    public function ClearMenuType($menu_type_id,$category_id){
        // Получаем объект базы данных.
        $db = JFactory::getDbo();
        // Конструируем SQL запрос.
        //2. Создадим запрос к базе данных, в данном случае мы выбираем статьи
        $sql = "DELETE FROM `#__jshopmenu_product_to_menu_type` WHERE category_id = ".(int)$category_id." AND menu_type_id = ".(int)$menu_type_id.";";
         
        //3. Установим этот запрос в экземпляр класса работы с базами данных
        $db->setQuery($sql);
         
        //4.  Выполним запрос
        return $db->query();;
    }
    
    public function ClearProductDetals($menu_type_id,$category_id){
        // Получаем объект базы данных.
        $db = JFactory::getDbo();
        // Конструируем SQL запрос.
        //2. Создадим запрос к базе данных, в данном случае мы выбираем статьи
        $sql = "DELETE FROM `#__jshopmenu_product_detailed_info` WHERE category_id = ".(int)$category_id." AND menu_type_id = ".(int)$menu_type_id.";";
         
        //3. Установим этот запрос в экземпляр класса работы с базами данных
        $db->setQuery($sql);
         
        //4.  Выполним запрос
        return $db->query();;
    }
}
?>