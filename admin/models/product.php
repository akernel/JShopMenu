<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modeladmin Joomla.
jimport('joomla.application.component.modeladmin');
 
/**
 * Модель Product.
 */
class JShopMenuModelProduct extends JModelAdmin
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
    public function getTable($type = 'Product', $prefix = 'JShopMenuTable', $config = array())
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
            $this->option . '.product', 'product', array('control' => 'jform', 'load_data' => $loadData)
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
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.product.data', array());
 
        if (empty($data))
        {
            $data = $this->getItem();
        }
 
        return $data;
    }
    
    public function save($data){
        if(parent::save($data)){
            if(empty($data['product_id']) || $data['product_id']==0){
                $last_id = $this->_db->insertid();
            }else{
                $last_id = $data['product_id'];
            }
            $files = $_FILES['jform'];
            $path = JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jshopmenu'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR;
            if(!file_exists($path)){
                mkdir($path);
            }
            
            if(!empty($files['name']['image']) && $files['error']['image'] == 0){
                $type = explode('/',$files['type']['image']);
                $type = $type[0];
                if($type == 'image'){
                    $file = $files['tmp_name']['image'];
                    $filename = 'image-product-'.$last_id.'.jpg';
                    $uploadfile = $path . basename($filename);
                    if (move_uploaded_file($file, $uploadfile)) {
                        $path_image = '/media/com_jshopmenu/images/products/';
                        $query = "UPDATE `#__jshopmenu_products` SET `image`='".$path_image.$filename."' WHERE `product_id`=".$last_id;
                        $this->_db->setQuery($query);
                        $this->_db->query();
                    }
                }
            }
            
            if(isset($data['category_ids'])){
                foreach($data['category_ids'] as $category_id){
                    $query = $this->_db->getQuery(true);
                    $query->select('id')
                            ->from('#__jshopmenu_product_to_category')
                            ->where('product_id = '.$last_id)
                            ->where('category_id = '.$category_id);
                    $this->_db->setQuery($query);
                    
                    if(!$this->_db->loadResult()){
                        $query = $this->_db->getQuery(true);
                        $query->insert($this->_db->quoteName('#__jshopmenu_product_to_category'))
                                ->columns(array($this->_db->quoteName('product_id'),$this->_db->quoteName('category_id')))
                                ->values((int)$last_id .', '. (int)$category_id);
                        $this->_db->setQuery($query);
                        $this->_db->query();
                    }
                }
                
                $query = $this->_db->getQuery(true);
                $query->select('category_id')
                        ->from('#__jshopmenu_product_to_category')
                        ->where('product_id = '.$last_id);
                $this->_db->setQuery($query);
                if($categories = $this->_db->loadObjectList()){
                    foreach($categories as $category){
                        if(!in_array($category->category_id,$data['category_ids'])){
                            $query = $this->_db->getQuery(true);
                            $query->delete()
                                    ->from('#__jshopmenu_product_to_category')
                                    ->where('product_id = '.$last_id)
                                    ->where('category_id = '.$category->category_id);
                            $this->_db->setQuery($query);
                            $this->_db->query();
                        }
                    }
                }
                
            }
            
            if(isset($data['menu_type_ids'])){
                foreach($data['menu_type_ids'] as $category_id=>$menu_type_cat){
                    foreach($menu_type_cat as $menu_type_id){
                        $query = $this->_db->getQuery(true);
                        $query->select('id')
                                ->from('#__jshopmenu_product_to_menu_type')
                                ->where('product_id = '.$last_id)
                                ->where('category_id = '.$category_id)
                                ->where('menu_type_id = '.$menu_type_id);
                        $this->_db->setQuery($query);
                        
                        if(!$this->_db->loadResult()){
                            $query = $this->_db->getQuery(true);
                            $query->insert($this->_db->quoteName('#__jshopmenu_product_to_menu_type'))
                                    ->columns(array($this->_db->quoteName('product_id'),$this->_db->quoteName('category_id'),$this->_db->quoteName('menu_type_id')))
                                    ->values((int)$last_id . ', ' . (int)$category_id . ', ' . (int)$menu_type_id);
                            $this->_db->setQuery($query);
                            $this->_db->query();
                        }
                    }
                }
                foreach($data['menu_type_ids'] as $category_id=>$menu_type_cat){
                    $query = $this->_db->getQuery(true);
                    $query->select('menu_type_id')
                            ->from('#__jshopmenu_product_to_menu_type')
                            ->where('product_id = '.$last_id)
                            ->where('category_id = '.$category_id);
                    $this->_db->setQuery($query);
                    if($menu_types = $this->_db->loadObjectList()){
                        foreach($menu_types as $menu_type){
                            if(!in_array($menu_type->menu_type_id,$menu_type_cat)){
                                $query = $this->_db->getQuery(true);
                                $query->delete()
                                        ->from('#__jshopmenu_product_to_menu_type')
                                        ->where('product_id = '.$last_id)
                                        ->where('category_id = '.$category_id)
                                        ->where('menu_type_id = '.$menu_type->menu_type_id);
                                $this->_db->setQuery($query);
                                $this->_db->query();
                            }
                        }
                    }
                }
            }
            
            if(isset($data['product_type_ids'])){
                foreach($data['product_type_ids'] as $product_type_id){
                    $query = $this->_db->getQuery(true);
                    $query->select('id')
                            ->from('#__jshopmenu_product_to_product_type')
                            ->where('product_id = '.$last_id)
                            ->where('product_type_id = '.$product_type_id);
                    $this->_db->setQuery($query);
                    
                    if(!$this->_db->loadResult()){
                        $query = $this->_db->getQuery(true);
                        $query->insert($this->_db->quoteName('#__jshopmenu_product_to_product_type'))
                                ->columns(array($this->_db->quoteName('product_id'),$this->_db->quoteName('product_type_id')))
                                ->values((int)$last_id .', '. (int)$product_type_id);
                        $this->_db->setQuery($query);
                        $this->_db->query();
                    }
                }
                
                $query = $this->_db->getQuery(true);
                $query->select('product_type_id')
                        ->from('#__jshopmenu_product_to_product_type')
                        ->where('product_id = '.$last_id);
                $this->_db->setQuery($query);
                if($product_types = $this->_db->loadObjectList()){
                    foreach($product_types as $product_type){
                        if(!in_array($product_type->product_type_id,$data['product_type_ids'])){
                            $query = $this->_db->getQuery(true);
                            $query->delete()
                                    ->from('#__jshopmenu_product_to_product_type')
                                    ->where('product_id = '.$last_id)
                                    ->where('product_type_id = '.$product_type->product_type_id);
                            $this->_db->setQuery($query);
                            $this->_db->query();
                        }
                    }
                }
                
            }
            if(isset($data['product_info'])){
                foreach($data['product_info'] as $category_id=>$menu_types){
                    foreach($menu_types as $menu_type_id=>$detal_info){
                        $query = $this->_db->getQuery(true);
                        $query->select('id')
                                ->from('#__jshopmenu_product_detailed_info')
                                ->where('product_id = '.$last_id)
                                ->where('category_id = '.$category_id)
                                ->where('menu_type_id = '.$menu_type_id);
                        $this->_db->setQuery($query);
                        if($id = $this->_db->loadResult()){
                            $query = $this->_db->getQuery(true);
                            $query->update($this->_db->quoteName('#__jshopmenu_product_detailed_info'))
                                    ->set($this->_db->quoteName('price') . ' = ' . $detal_info['price'] .', ' . 
                                            $this->_db->quoteName('ves') . ' = ' . $detal_info['ves'] .', ' .
                                            $this->_db->quoteName('koll') . ' = ' . $detal_info['koll'] )
                                    ->where('id = '.$id);
                            $this->_db->setQuery($query);
                            $this->_db->query();
                        }else{
                            $query = $this->_db->getQuery(true);
                            $query->insert($this->_db->quoteName('#__jshopmenu_product_detailed_info'))
                                    ->columns(array(
                                                $this->_db->quoteName('product_id'),
                                                $this->_db->quoteName('category_id'),
                                                $this->_db->quoteName('menu_type_id'),
                                                $this->_db->quoteName('price'),
                                                $this->_db->quoteName('ves'),
                                                $this->_db->quoteName('koll')))
                                    ->values((int)$last_id . ', ' .
                                             (int)$category_id . ', ' .
                                             (int)$menu_type_id . ', ' .
                                             (float)$detal_info['price'] . ', ' .
                                             (float)$detal_info['ves'] . ', ' .
                                             (int)$detal_info['koll']);
                            $this->_db->setQuery($query);
                            $this->_db->query();
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }
    
    public function getCategories($product_id = 0, $isNew = false){
        // Конструируем SQL запрос.
        $query = $this->_db->getQuery(true);
        $query->select('category_id,name')
                ->from('#__jshopmenu_categories')
                ->where('published = 1')
                ->order($this->_db->escape('ordering asc'));
        $this->_db->setQuery($query);
        $categories = $this->_db->loadObjectList();
        $categories_ids = array();
        if(!$isNew){
            $query = $this->_db->getQuery(true);
            $query->select('category_id')
                    ->from('#__jshopmenu_product_to_category')
                    ->where('product_id = '.$product_id)
                    ->order($this->_db->escape('category_id asc'));
            $this->_db->setQuery($query);
            $categories_to_product = $this->_db->loadObjectList();
            
            if($categories_to_product){
                foreach($categories_to_product as $categories_id){
                    $categories_ids[]=$categories_id->category_id;
                }
            }
        }
        
        
        if($categories){
            $categories_list=array();
            foreach($categories as $category){
                $categories_list[] = array(
                                        'value'=>$category->category_id,
                                        'text'=>$category->name,
                                        'selected'=>(in_array($category->category_id,$categories_ids) ? 1 : 0));
            }
            return $categories_list;
        }else{
            return array();
        }
    }
    
    public function getMenuTypes($product_id = 0, $category_ids = array()){
        // Конструируем SQL запрос.
        $query = $this->_db->getQuery(true);
        $query->select('menu_type_id,name')
                ->from('#__jshopmenu_menu_types')
                ->where('published = 1')
                ->order($this->_db->escape('ordering asc'));
        $this->_db->setQuery($query);
        $menu_types = $this->_db->loadObjectList();
        
        foreach($category_ids as $category_id){
            $query = $this->_db->getQuery(true);
            $query->select('menu_type_id')
                    ->from('#__jshopmenu_product_to_menu_type')
                    ->where('product_id = '.$product_id)
                    ->where('category_id = '.$category_id)
                    ->order($this->_db->escape('menu_type_id asc'));
            $this->_db->setQuery($query);
            $menu_types_to_product[$category_id] = $this->_db->loadObjectList();
        }
        
        $menu_types_ids = array();
        if($menu_types_to_product){
            foreach($menu_types_to_product as $category_id=>$menu_types_cat){
                $menu_types_ids[$category_id] = array();
                foreach($menu_types_cat as $menu_type){
                    $menu_types_ids[$category_id][]=$menu_type->menu_type_id;
                }
            }
        }
        
        
        if($menu_types){
            foreach($category_ids as $category_id){
                $menu_types_list[$category_id]=array();
                foreach($menu_types as $menu_type){
                    $menu_types_list[$category_id][] = array(
                                            'value'=>$menu_type->menu_type_id,
                                            'text'=>$menu_type->name,
                                            'selected'=>(in_array($menu_type->menu_type_id,$menu_types_ids[$category_id]) ? 1 : 0));
                }
            }
            return $menu_types_list;
        }else{
            return array();
        }
    }
    
    public function getProductTypes($product_id = 0, $isNew = false){
        // Конструируем SQL запрос.
        $query = $this->_db->getQuery(true);
        $query->select('product_type_id,name')
                ->from('#__jshopmenu_product_types')
                ->where('published = 1')
                ->order($this->_db->escape('ordering asc'));
        $this->_db->setQuery($query);
        $product_types = $this->_db->loadObjectList();
        $product_types_ids = array();
        if(!$isNew){
            $query = $this->_db->getQuery(true);
            $query->select('product_type_id')
                    ->from('#__jshopmenu_product_to_product_type')
                    ->where('product_id = '.$product_id)
                    ->order($this->_db->escape('product_type_id asc'));
            $this->_db->setQuery($query);
            $product_types_to_product = $this->_db->loadObjectList();
            
            if($product_types_to_product){
                foreach($product_types_to_product as $product_types_id){
                    $product_types_ids[]=$product_types_id->product_type_id;
                }
            }
        }
        
        
        if($product_types){
            $product_types_list=array();
            foreach($product_types as $product_type){
                $product_types_list[] = array(
                                        'value'=>$product_type->product_type_id,
                                        'text'=>$product_type->name,
                                        'selected'=>(in_array($product_type->product_type_id,$product_types_ids) ? 1 : 0));
            }
            return $product_types_list;
        }else{
            return array();
        }
    }
    
    public function getOptions($product_id,$menu_type_id,$category_id){
        $query = $this->_db->getQuery(true);
        $query->select('price,ves,koll')
                ->from('#__jshopmenu_product_detailed_info')
                ->where('product_id = '.$product_id)
                ->where('menu_type_id = '.$menu_type_id)
                ->where('category_id = '.$category_id);
        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }
    
    /**
	 * Method to toggle the featured setting of articles.
	 *
	 * @param   array    $pks    The ids of the items to toggle.
	 * @param   integer  $value  The value to toggle to.
	 *
	 * @return  boolean  True on success.
	 */
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks))
		{
			$this->setError(JText::_('COM_CONTENT_NO_ITEM_SELECTED'));

			return false;
		}

		try
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
						->update($db->quoteName('#__jshopmenu_products'))
						->set('featured = ' . (int) $value)
						->where('id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query);
			$db->execute();
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$this->cleanCache();

		return true;
	}
    
}