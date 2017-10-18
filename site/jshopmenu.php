<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем логирование.
JLog::addLogger(
    array('text_file' => 'com_helloworld.php'),
    JLog::ALL,
    array('com_helloworld')
);
// Устанавливаем обработку ошибок в режим использования Exception.
JError::$legacy = false;


// Подключаем хелпер.
JLoader::register('JShopMenuHelper', dirname(__FILE__) . '/helpers/jshopmenu.php');

// Подключаем библиотеку контроллера Joomla.
jimport('joomla.application.component.controller');
 
// Получаем экземпляр контроллера с префиксом HelloWorld.
$controller = JControllerLegacy::getInstance('JShopMenu');
 
// Исполняем задачу task из Запроса.
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task', 'display'));

if(!isset($_SESSION['jshopmenu'])){
    $_SESSION['jshopmenu'] = array();
}

// Перенаправляем, если перенаправление установлено в контроллере.
$controller->redirect();
?>