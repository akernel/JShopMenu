<?php 
header('Content-Type: text/html; charset=utf-8');
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку controlleradmin Joomla.
jimport('joomla.application.component.controlleradmin');
 
/**
 * Import контроллер.
 */
class JShopMenuControllerImport extends JControllerAdmin
{
    
    function import(){
        
        $text = 'Выстовите конец обрезки страницы "$start" , "$end" в файле: '.__DIR__.'/import.php; строка 18';
        $start = $_POST['start'] ? $_POST['start'] : 0;
        $end = $_POST['end'] ? $_POST['end'] : 0;
        if(!$start && !$end){
            exit($text);
        }
        
        $model = parent::getModel('Import', 'JShopMenuModel', array('ignore_request' => true));
        
        if(isset($_FILES['inport_file']) && isset($_POST['menu_type_id']) && isset($_POST['category_id'])){
            
            $menu_type_id = isset($_POST['menu_type_id']) ? $_POST['menu_type_id'] : 0;
            $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : 0;
            
            $db = JFactory::getDBO();
            /* Include PHPExcel_IOFactory */
            require_once JPATH_COMPONENT .DIRECTORY_SEPARATOR. 'phpexcel'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'IOFactory.php';
            
            if($category_id && $menu_type_id){
                $model->ClearMenuType($menu_type_id,$category_id);
                $model->ClearProductDetals($menu_type_id,$category_id);
            }
            
            $filename = $_FILES['inport_file']['tmp_name'];
            if (!file_exists($filename)) {
                exit("No file.");
            }
            
            $objPHPExcel = PHPExcel_IOFactory::load($filename);
            $allData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            
            $count = count($allData);
            for($i=1;$i<$start;$i++){
                unset($allData[$i]);
            }
            
            for($i=$end;$i<=$count;$i++){
                unset($allData[$i]);
            }
            
            $product_id = false;
            $product_type['product_type_id'] = false;
            $product_type['name'] = null;
            
            $person = 50;
            
            $order = 0;
            $sort = $_POST['sort'] ? true : false;
            
            foreach($allData as $key=>$row){
                $product_id = false;
                if(empty($row['A'])){
                    if(empty($row['B'])) continue;
                    $product_type['name'] = mb_strtolower($row['B'], 'UTF-8');
                    if(!($product_type['product_type_id'] = $model->getProductTypeIdToName($product_type['name']))){
                        $product_type['name'] = mb_convert_case($product_type['name'], MB_CASE_TITLE, "UTF-8");
                        $insert = "INSERT INTO `#__jshopmenu_product_types`(`product_type_id`,`name`,`published`,`napitki`) VALUES (NULL,'".$product_type['name']."',1,0)";
                        $db->setQuery($insert);
                        if($db->query()){
                            $product_type['product_type_id'] = $db->insertid();
                        }
                    }
                    continue;
                }
                $ves = str_replace(',','',$row['A']);
                $price = str_replace(',','',$row['C']);
                
                $ves = !empty($row['A']) ? round((float)$ves,2) : 0.00;
                $name_desc = !empty($row['B']) ? $row['B'] : '';
                $price = !empty($row['C']) ? round((float)$price,2) : 0.00;
                $koll = !empty($row['D']) || (int)$row['D'] == 0 ? ((int)$row['D'])/$person : 0.00;
                $name = '';
                $short_description = '';
                if($product_type['product_type_id']){
                    $name = explode('(' , $name_desc);
                    if(isset($name[1])){
                        $short_description = explode(')' , $name[1]);
                        if(is_array($short_description))  $short_description = trim($short_description[0]);
                    }
                    $name = trim($name[0]);
                    
                    if(empty($name)) continue;
                    
                    $name = mb_strtolower($name, 'UTF-8');
                    if((boolean)($product_id = $model->getProductIdToName($name))){
                        if($category_id != 0){
                            if(!$model->ProductToCategory($product_id,$category_id)){
                                $insert = "INSERT INTO `#__jshopmenu_product_to_category`(`id`, `product_id`, `category_id`) VALUES (NULL, ".$product_id.", ".$category_id.")";
                                $db->setQuery($insert);
                                $db->query();
                            }
                        }
                        if($menu_type_id != 0){ 
                            if(!$model->ProductToMenuType($product_id,$category_id,$menu_type_id)){
                                $insert = "INSERT INTO `#__jshopmenu_product_to_menu_type`(`id`, `product_id`, `category_id`, `menu_type_id`) VALUES (NULL, ".$product_id.", ".$category_id.", ".$menu_type_id.")";
                                $db->setQuery($insert);
                                $db->query();
                            }
                        }
                        if($product_type['product_type_id']){
                            if(!$model->ProductToProductType($product_id,$product_type['product_type_id'])){
                                $insert = "INSERT INTO `#__jshopmenu_product_to_product_type`(`id`, `product_id`, `product_type_id`) VALUES (NULL, ".$product_id.", ".$product_type['product_type_id'].")";
                                $db->setQuery($insert);
                                $db->query();
                            }
                        }
                        
                        if($product_id && $category_id != 0 && $menu_type_id != 0){
                            if($result = $model->ProductDetalsInfo($product_id,$category_id,$menu_type_id)){
                                $update = "UPDATE `#__jshopmenu_product_detailed_info` SET `ves`=".$ves.",`price`=".$price.",`koll`=".$koll." WHERE `id`=".$result;
                                $db->setQuery($update);
                                $db->query();
                            }else{
                                $insert = "INSERT INTO `#__jshopmenu_product_detailed_info`(`id`, `product_id`, `category_id`, `menu_type_id`, `price`, `ves`, `koll`) VALUES (NULL, ".$product_id.", ".$category_id.", ".$menu_type_id.", ".$price.", ".$ves.", ".$koll.")";
                                $db->setQuery($insert);
                                $db->query();
                            }
                        }
                        
                        if($product_id && $sort){
                            $update = "UPDATE `#__jshopmenu_products` SET `ordering`=".$order." WHERE `product_id`=".$product_id;
                            $db->setQuery($update);
                            if($db->query()){
                                $order++;
                            }
                        }
                        
                    }else{
                        $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
                        $ordering = $sort ? $order : 0;
                        $insert = "INSERT INTO `#__jshopmenu_products` (`product_id`, `name`, `short_description`, `published`, `ordering`, `image`, `price`, `ves`, `koll`, `params`) VALUES (NULL, '".$name."', '".$short_description."', 1, ".$ordering.", '', ".$price.", ".$ves.", ".$koll.", '{}');";
                        $db->setQuery($insert);
                        if($db->query()){
                            $product_id = $db->insertid();
                            $order++;
                        }
                        if($product_id){
                            if($category_id != 0){
                                if(!$model->ProductToCategory($product_id,$category_id)){
                                    $insert = "INSERT INTO `#__jshopmenu_product_to_category`(`id`, `product_id`, `category_id`) VALUES (NULL, ".$product_id.", ".$category_id.")";
                                    $db->setQuery($insert);
                                    $db->query();
                                }
                            }
                            if($menu_type_id != 0){ 
                                if(!$model->ProductToMenuType($product_id,$category_id,$menu_type_id)){
                                    $insert = "INSERT INTO `#__jshopmenu_product_to_menu_type`(`id`, `product_id`, `category_id`, `menu_type_id`) VALUES (NULL, ".$product_id.", ".$category_id.", ".$menu_type_id.")";
                                    $db->setQuery($insert);
                                    $db->query();
                                }
                            }
                            if($product_type['product_type_id']){
                                if(!$model->ProductToProductType($product_id,$product_type['product_type_id'])){
                                    $insert = "INSERT INTO `#__jshopmenu_product_to_product_type`(`id`, `product_id`, `product_type_id`) VALUES (NULL, ".$product_id.", ".$product_type['product_type_id'].")";
                                    $db->setQuery($insert);
                                    $db->query();
                                }
                            }
                            
                            if($product_id != 0 && $category_id != 0 && $menu_type_id != 0){
                                if($result = $model->ProductDetalsInfo($product_id,$category_id,$menu_type_id)){
                                    $update = "UPDATE `#__jshopmenu_product_detailed_info` SET `ves`=".$ves.",`price`=".$price.",`koll`=".$koll." WHERE `id`=".$result;
                                    $db->setQuery($update);
                                    $db->query();
                                }else{
                                    $insert = "INSERT INTO `#__jshopmenu_product_detailed_info`(`id`, `product_id`, `category_id`, `menu_type_id`, `price`, `ves`, `koll`) VALUES (NULL, ".$product_id.", ".$category_id.", ".$menu_type_id.", ".$price.", ".$ves.", ".$koll.")";
                                    $db->setQuery($insert);
                                    $db->query();
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->setRedirect('index.php?option=com_jshopmenu&view=import');
    }
}
?>