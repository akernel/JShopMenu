<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
class JShopMenuHelper
{
    function LeftPanel (){
        $html = '<div><ul>';
        
        $html .= '<li><a href="index.php?option=com_jshopmenu&view=categories"><span>'.JText::_('COM_JSHOPMENU_LEFTPANEL_MENUS').'</span></a></li>';
        $html .= '<li><a href="index.php?option=com_jshopmenu&view=menutypes"><span>'.JText::_('COM_JSHOPMENU_LEFTPANEL_MENU_TYPES').'</span></a></li>';
        $html .= '<li><a href="index.php?option=com_jshopmenu&view=products"><span>'.JText::_('COM_JSHOPMENU_LEFTPANEL_PRODUCTS').'</span></a></li>';
        $html .= '<li><a href="index.php?option=com_jshopmenu&view=producttypes"><span>'.JText::_('COM_JSHOPMENU_LEFTPANEL_PRODUCT_TYPES').'</span></a></li>';
        $html .= '<li><a href="index.php"><span>'.JText::_('COM_JSHOPMENU_LEFTPANEL_IMPORT_MENU').'</span></a></li>';
        $html .= '<li><a href="index.php"><span>'.JText::_('COM_JSHOPMENU_LEFTPANEL_SETTING').'</span></a></li>';
        
        $html .= '</ul></div>';
        
        echo $html;
    }
}
?>