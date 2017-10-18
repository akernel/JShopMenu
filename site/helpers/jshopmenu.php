<?php
// ������ ������� �������.
defined('_JEXEC') or die;
 
/**
 * ������ JShopMenu ����������.
 */
abstract class JShopMenuHelper
{
    
    /**
     * ������������� �������.
     *
     * @param   string  $submenu  �������� ����� ����.
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