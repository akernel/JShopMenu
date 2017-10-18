<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modeladmin Joomla.
jimport('joomla.application.component.modeladmin');
 
/**
 * Модель Category.
 */
class JShopMenuModelCategory extends JModelAdmin
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
    public function getTable($type = 'Category', $prefix = 'JShopMenuTable', $config = array())
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
            $this->option . '.category', 'category', array('control' => 'jform', 'load_data' => $loadData)
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
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.category.data', array());
 
        if (empty($data))
        {
            $data = $this->getItem();
        }
 
        return $data;
    }
    
    public function save($data){
        $db = $this->_db;
        
        
        if(isset($data['file'])){
            $data['file']=json_encode($data['file']);
        }
        
        if(parent::save($data)){
            
            $last_id = $this->getState($this->getName() . '.id');
            
            $table = $this->getTable();
            $table->load($last_id);
            
            $files = $_FILES['jform'];
            $path = JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jshopmenu'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR;
            if(!file_exists($path)){
                mkdir($path);
            }
            
            if(!empty($files['name']['image']) && $files['error']['image'] == 0){
                $type = explode('/',$files['type']['image']);
                $type = $type[0];
                if($type == 'image'){
                    $file = $files['tmp_name']['image'];
                    $filename = 'image-category-'.$last_id.'.jpg';
                    $uploadfile = $path . basename($filename);
                    if (move_uploaded_file($file, $uploadfile)) {
                        $path_image = '/media/com_jshopmenu/images/categories/';
                        $table->image = $path_image.$filename;
                        $table->store();
                    }
                }
            }elseif(isset($_POST['del_image'])){
                $table->image = '';
                $table->store();
            }
            
            return true;
        }
        return false;
    }
    
    public function getMenuTypes(){
        $query = $this->_db->getQuery(true);
        $query->select('menu_type_id, name')
                ->from('#__jshopmenu_menu_types');
        $query->order($this->_db->escape('ordering asc'));
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }
}