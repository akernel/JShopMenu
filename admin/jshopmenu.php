<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');
 
// Устанавливаем обработку ошибок в режим использования Exception.
JError::$legacy = false;

// Подключаем хелпер.
JLoader::register('JShopMenuHelper', dirname(__FILE__) . '/helpers/jshopmenu.php');

// Подключаем библиотеку контроллера Joomla.
jimport('joomla.application.component.controller');
 
// Получаем экземпляр контроллера с префиксом JShopMenu.
$controller = JControllerLegacy::getInstance('JShopMenu');
// Исполняем задачу task из Запроса.
$controller->execute(JFactory::getApplication()->input->get('task'));
// Перенаправляем, если перенаправление установлено в контроллере.
$controller->redirect();
?>