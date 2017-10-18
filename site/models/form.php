<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем библиотеку modellist Joomla.
jimport('joomla.application.component.modellist');
use Joomla\Registry\Registry;
 
/**
 * Модель сообщения компонента Category.
 */
class JShopMenuModelForm extends JModelForm
{
	protected $_item = null;
    
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_jshopmenu.form', 'form', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}
    
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
		$category_id = $app->input->getInt('category_id', 0);
		$this->setState('category.category_id', $category_id);
        
		$menu_type_id = JRequest::getVar('menu_type_id',0);
        if($menu_type_id){
            $this->setState('filter.menu_type_id', $menu_type_id);
        }
		
        
        // Load the parameters. Merge Global and Menu Item params into new object
		$params = $app->getParams();
        $this->setState('params', $params);
        
		$menuParams = new Registry;

		if ($menu = $app->getMenu()->getActive())
		{
			$menuParams->loadString($menu->params);
		}

		$mergedParams = clone $menuParams;
		$mergedParams->merge($params);

		$this->setState('params.all', $mergedParams);
        
		parent::populateState();
	}
    
    public function getCategory($pk = null){
        
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('category.category_id');
        
		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk]))
		{
            try
			{
				$db = $this->getDbo();
                $query = $db->getQuery(true);
                $query->select('category_id, name, short_name, short_description, description, image, koll_person, file, params');
                $query->from('#__jshopmenu_categories');
                $query->where('category_id='.(int)$pk);
        		$db->setQuery($query);
        		$data = $db->loadObject();
                
                // Генерируем исключение, если категория не найдена.
				if (empty($data))
				{
					return JError::raiseError(404, 'Не верно указана категория');
				}
                
				// Convert parameter fields to objects.
				$registry = new Registry;
				$registry->loadString($data->params);

				$data->params = clone $this->getState('params.all');
                $data->params->merge($registry);
                
				$registry = new Registry;
				$registry->loadString($data->file);
                $data->file = $registry;
                
				$this->_item[$pk] = $data;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError(404, $e->getMessage());
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
        }
		return $this->_item[$pk];
    }
    
    public function getMenuType($id=0){

		if ($id == 0)
		{
			JError::raiseError(404, '');
		}

        $model = JModelLegacy::getInstance('MenuTypes', 'JShopMenuModel', array('ignore_request' => true));
        $model->setState('params', $this->getState('params'));
        
        $model->setState('filter.menu_type_id', $id);
        
        $menu_types = $model->getItems();
            
		if ($menu_types === false)
		{
			$this->setError($model->getError());
		}
            
		return $menu_types[0];
    }
    
    public function updateMenuTypes($menu_types=array()){
        if($this->_menu_types === null){
            $this->_menu_types = $menu_types;
        }
        foreach($this->_menu_types as $menu_type){
            $menu_type->product_types = $this->getProductTypes($menu_type->menu_type_id);
            foreach($menu_type->product_types as $key=>$product_type){
                $product_type->products = $this->getProducts($menu_type->menu_type_id,$product_type->product_type_id);
            }
        }
    }
    
    public function getProductTypes($menu_type_id){
        $model = JModelLegacy::getInstance('ProductTypes', 'JShopMenuModel', array('ignore_request' => true));
        $category_id = (int) $this->getState('category.category_id');
        
        $model->setState('params', $this->getState('params'));
        $model->setState('filter.category_id', $category_id);
        $model->setState('filter.menu_type_id', $menu_type_id);
        
        $product_types = $model->getItems();
        if ($product_types === false)
		{
			return array();
		}
        return $product_types;
    }
    
    public function getProducts($menu_type_id,$product_type_id){
        $model = JModelLegacy::getInstance('Products', 'JShopMenuModel', array('ignore_request' => true));
        $category_id = (int) $this->getState('category.category_id');
        
        $model->setState('params', $this->getState('params'));
        $model->setState('filter.category_id', $category_id);
        $model->setState('filter.menu_type_id', $menu_type_id);
        $model->setState('filter.product_type_id', $product_type_id);
        
        $products = $model->getItems();
        if ($products === false)
		{
			return array();
		}
        return $products;
    }
    
    
    
    public function getPathFile($menu_type_id,$category_id){
        // Конструируем SQL запрос.
        $query = $this->_db->getQuery(true);
        $query->select('file')
                ->from('#__jshopmenu_categories')
                ->where('category_id = '.(int)$category_id);
        $this->_db->setQuery($query);
        $json = $this->_db->loadResult();
        
		$registry = new Registry;
		$registry->loadString($json);
        $files = $registry;
        $files = $files->toArray();
        
        return $files[$menu_type_id];
    }
        
}
?>