<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modelitem Joomla.
jimport('joomla.application.component.modelitem');
 
/**
 * Модель сообщения компонента File.
 */
class JShopMenuModelFile extends JModelItem
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
    
    public function getPathFile($menu_type_id,$category_id){
        // Конструируем SQL запрос.
        $query = $this->_db->getQuery(true);
        $query->select('file')
                ->from('#__jshopmenu_categories')
                ->where('category_id = '.(int)$category_id);
        $this->_db->setQuery($query);
        $json = $this->_db->loadResult();
        
        $files = json_decode($json);
        return $files->$menu_type_id;
    }    
}
?>