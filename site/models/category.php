<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем библиотеку modellist Joomla.
jimport('joomla.application.component.modellist');
use Joomla\Registry\Registry;
 
/**
 * Модель сообщения компонента Category.
 */
class JShopMenuModelCategory extends JModelList
{
	protected $_item = null;

	protected $_menu_types = null;
    
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
                $query->select('category_id, name, short_name, short_description, description, image, koll_person, file, metatitle, metakey, metadesc, params');
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
    
    public function getMenuTypes(){

		if ($this->_menu_types === null)
		{
			$this->_menu_types = array();
		}

		if (!isset($this->_menu_types[0]))
		{
            $model = JModelLegacy::getInstance('MenuTypes', 'JShopMenuModel', array('ignore_request' => true));
            $model->setState('params', $this->getState('params'));
            
            $menu_type_id = (int) $this->getState('filter.menu_type_id');
            if($menu_type_id){
                $model->setState('filter.menu_type_id', $menu_type_id);
            }
            
            $this->_menu_types = $model->getItems();
                
			if ($this->_menu_types === false)
			{
				$this->setError($model->getError());
			}
        }
		return $this->_menu_types;
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
    
    
    function SendMail ($post) {
        
        // Получаем параметры приложения.
        $app = JFactory::getApplication();
		$category_id = JRequest::getVar('category_id',0);
		$this->setState('category.category_id', $category_id);
        
		$menu_type_id = JRequest::getVar('menu_type_id',0);
        if($menu_type_id){
            $this->setState('filter.menu_type_id', $category_id);
        }
        $url_return = JRequest::getVar('url_return','/');
        
        
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
        
        $category = $this->getCategory();
        $menu_types = $this->getMenuTypes();
        $menu_type = $menu_types[0];
        $params = $category->params;
        
        $config = JFactory::getConfig();
        $to = "<".$params->get('email_to',$config->get('mailfrom')).">";
        $from = isset($post['email']) ? "<".$post['email'].">" : false;
        
        $message_admin = '';
        $message_user = '';
        
            $subject_admin = $params->get('email_subject_admin') ? $params->get('email_subject_admin') : 'Заказ Меню';
            $subject_user = $params->get('email_subject_user') ? $params->get('email_subject_user') : 'Заказ Меню';
            
            $message = '<h2>Заказ Меню</h2><br />';
            $message_admin .= $message;
            $message_user .= $message;
            
            $message_admin .= '<p>Был зделан заказ на сайте '.$_SERVER['SERVER_NAME'].', письмо с уведомлением было отправлено заказчику успешно</p><br />';
            $message_user .= '<p>Спасибо за заказ на нашем сайте, мы свяжемся с вами в ближайшее время</p><br />';
            
            
            
            $message = '<h2>Меню: '.$category->name.'</h2>';
            $message_admin .= $message;
            $message_user .= $message;
            
            $message = '<h3>Тип меню: '.$menu_type->name.'</h3>';
            $message_admin .= $message;
            $message_user .= $message;
            
            $message_admin .= '<table border="1">
                            <tr>
                                <td colspan="2" align="center">Данные заказчика</td>
                            </tr>';
            $message_user .= '<table border="1">
                            <tr>
                                <td colspan="2" align="center">Ваши данные</td>
                            </tr>';
                            
             $message =    '<tr>
                                <td>ФИО</td>
                                <td>'.$post['name'].'</td>
                            </tr>
                            <tr>
                                <td>Телефон</td>
                                <td>'.$post['telefon'].'</td>
                            </tr>
                            <tr>
                                <td>eMail</td>
                                <td>'.$post['email'].'</td>
                            </tr>
                        </table><br />
                        
                        <table width="100%" border="1" bgcolor="#F1B5B5">
                            <thead>
                                <tr>
                                    <td colspan="3" align="center">Данные заказа</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" align="right">Дата мероприятия:</td>
                                    <td>'.$post['date'].'</td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Начало мероприятия:</td>
                                    <td>'.$post['time_begin'].'</td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Продолжительность мероприятия:</td>
                                    <td>'.$post['time'].'</td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Дополнительные услуги</td>
                                    <td>';
            if(isset($post['dop_uslugi'])){
                foreach($post['dop_uslugi'] as $usluga){
                    $message .= $usluga.'<br />';
                }
            }
                                    
            $message .='            </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Кол-во персон:</td>
                                    <td>'.$post['kol_person'].'</td>
                                </tr>
                            </tbody>
                        </table>';
            $message_admin .= $message;
            $message_user .= $message;
            
            $message = '<br /><br /><br /><table width="100%" border="1">';
            if(isset($_SESSION['jshopmenu']['tovar']) && !empty($_SESSION['jshopmenu']['tovar'])){
                
                $tovars_types = array();
                $tovars_info = $_SESSION['jshopmenu']['tovar'];
                $message .='<tbody bgcolor="#F1B5B5" style="font-weight: bold;">
                                    <tr>
                                        <td>Выход гр.</td>
                                        <td>Наименование блюда</td>
                                        <td>Цена</td>
                                        <td>Кол-во</td>
                                        <td>Сумма</td>
                                        <td>Выход гр/мл на персону</td>
                                    </tr>
                                </tbody>';
                                if(count($tovar)>0){
                                    $message .= '<tbody>';
                                }
                foreach($tovar as $mf){                                    
                  $message .=       '<tr>
                                        <td colspan="6" align="center">'.$mf['name'].'</td>
                                    </tr>';
                  if(isset($mf['product'])){
                        foreach($mf['product'] as $product){
                            if($product['koll'] > 0){
                                $message .= '<tr>
                                            <td>'.$product['ves'].'</td>
                                            <td>'.$product['name'].'</td>
                                            <td>'.$product['price'].'</td>
                                            <td>'.$product['koll'].'</td>
                                            <td>'.$product['price']*$product['koll'].'</td>
                                            <td>'.($product['price']*$product['koll'])/$post['gosti'].'</td>
                                        </tr>';
                                $post['prices'] += $product['price']*$product['koll'];
                                $post['veses'] += $product['ves']*$product['koll'];
                            }
                        }
                  }
                }
                
                if(count($tovar)>0){
                    $message .= '</tbody>';
                }
                
                $post['one_prices'] = $post['prices']/$post['gosti'];
                $post['obslugivanie'] = ($post['prices']*10)/100;
                $post['all_price'] = $post['prices'] + $post['obslugivanie'];
            }
            
            
                                
                            
             /*$message .='<tbody bgcolor="#F1B5B5">
                                <tr>
                                    <td colspan="3">Полная стоимость организации питания:</td>
                                    <td colspan="3">'.round($post['prices'], 2).' руб.</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Стоимость питания на персону:</td>
                                    <td colspan="3">'.round($post['one_prices'], 2).' руб.</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Выход грамм на персону:</td>
                                    <td colspan="3">'.(int)$post['one_veses'].' гр.</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Выход мл. на персону:</td>
                                    <td colspan="3">'.(int)$post['one_veses_voda'].' мл.</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Обслуживание 10%</td>
                                    <td colspan="3">'.round($post['obslugivanie'], 2).' руб.</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Итого к оплате:</td>
                                    <td colspan="3">'.round($post['all_price'], 2).' руб.</td>
                                </tr>
                            </tbody>
                        </table><br /><br />';
                        
            $message_admin .= $message;
            $message_user .= $message;*/
            
            
            
            $message_admin .= $params->get('email_desc_admin');
            $message_user .= $params->get('email_desc_user');
            
            $message_admin .= '<br /><br />
            <a href="'.$url_return.'">Ссылка на страницу откуда было выслано сообщение</a>
            ';
            $message_user .= '<br /><br />
            <a href="'.$url_return.'">Ссылка на страницу откуда вы зделали заказ</a>
            ';
                
            $headers_admin  = "Content-type: text/html; charset=utf-8 \r\n"; 
            $headers_admin .= "From: ".($from ? $from : "<user@".$_SERVER['SERVER_NAME'].">")."\r\n";
            
            $headers_user  = "Content-type: text/html; charset=utf-8 \r\n"; 
            $headers_user .= "From: ".$to."\r\n";
             
            unset($_SESSION['jshopmenu']['tovar']);
            
            if(mail($to, $subject_admin, $message_admin, $headers_admin)){
                if($from){
                    mail($from, $subject_user, $message_user, $headers_user);
                }
                return true;
            }else{
                return false;
            }
    }
    
}
?>