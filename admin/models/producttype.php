<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modeladmin Joomla.
jimport('joomla.application.component.modeladmin');
 
/**
 * Модель Producttype.
 */
class JShopMenuModelProducttype extends JModelAdmin
{
    /**
     * Возвращает ссылку на объект таблицы, всегда его создавая.
     *
     * @param   string  $type    Тип таблицы для подключения.
     * @param   string  $prefix  Префикс класса таблицы. Необязателен.
     * @param   array   $config  Конфигурационный массив. Необязателен.
     *
     * @return  JTable  Объект JTable.
     */
    public function getTable($type = 'Producttype', $prefix = 'JShopMenuTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }
 
    /**
     * Метод для получения формы.
     *
     * @param   array    $data      Данные для формы.
     * @param   boolean  $loadData  True, если форма загружает свои данные (по умолчанию), false если нет.
     *
     * @return  mixed  Объект JForm в случае успеха, в противном случае false.
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Получаем форму.
        $form = $this->loadForm(
            $this->option . '.producttype', 'producttype', array('control' => 'jform', 'load_data' => $loadData)
        );
 
        if (empty($form))
        {
            return false;
        }
 
        return $form;
    }
 
    /**
     * Метод для получения данных, которые должны быть загружены в форму.
     *
     * @return  mixed  Данные для формы.
     */
    protected function loadFormData()
    {
        // Проверка сессии на наличие ранее введеных в форму данных.
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.producttype.data', array());
 
        if (empty($data))
        {
            $data = $this->getItem();
        }
 
        return $data;
    }
    
    
	/**
	 * Method to toggle the napitki setting of a list of selectcitys.
	 *
	 * @return	void
	 * @since	1.6
	 */
	function napitki($id, $value)
	{
        // Создаем новый query объект.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update("#__jshopmenu_product_types")
              ->set("napitki = ".$value)
              ->where("product_type_id = ".$id);
        
        $db->setQuery($query);
        $db->query(); 
        return true;		
	}
}