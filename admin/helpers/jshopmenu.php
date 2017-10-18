<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
/**
 * Хелпер JShopMenu компонента.
 */
class JShopMenuHelper extends JHelperContent
{
    /**
     * Конфигурируем подменю.
     *
     * @param   string  $submenu  Активный пункт меню.
     *
     * @return  void
     */
    public static function addSubmenu($submenu)
    {
        // Добавляем пункты подменю.
        JHtmlSidebar::addEntry(
            JText::_('COM_JSHOPMENU_LEFTPANEL_MENUS'),
            'index.php?option=com_jshopmenu&view=categories',
            $submenu == 'categories'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_JSHOPMENU_LEFTPANEL_MENU_TYPES'),
            'index.php?option=com_jshopmenu&view=menutypes',
            $submenu == 'menutypes'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_JSHOPMENU_LEFTPANEL_PRODUCTS'),
            'index.php?option=com_jshopmenu&view=products',
            $submenu == 'products'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_JSHOPMENU_LEFTPANEL_PRODUCT_TYPES'),
            'index.php?option=com_jshopmenu&view=producttypes',
            $submenu == 'producttypes'
        );
    }
}