<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
/**
 * Хелпер JShopMenu компонента.
 */
abstract class JShopMenuHelper
{
    
    /**
     * Конфигурируем подменю.
     *
     * @param   string  $submenu  Активный пункт меню.
     *
     * @return  void
     */
    public static function getCategoryRoute($id,$alias)
    {
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');
        
    	$component	= JComponentHelper::getComponent('com_jshopmenu');
    
    	$attributes = array('component_id');
    	$values = array($component->id);
    
    	$items		= $menus->getItems($attributes, $values);
        
        foreach($items as $item){
            if($item->alias == $alias){
                $link = 'index.php?Itemid='.$item->id;
            }
        }
        
		// Create the link
        if(empty($link)){
            $link = 'index.php?option=com_jshopmenu&view=category&category_id=' . $id;
        }
        return $link;
    }
}