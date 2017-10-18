<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем тип поля list.
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * Класс поля формы JSMProductTypes компонента JShopMenu.
 */
class JFormFieldJSMProductTypes extends JFormFieldList
{
    /**
     * Тип поля.
     *
     * @var string
     */
    protected $type = 'JSMProductTypes';

 
    /**
     * Метод для получения списка опций для поля списка.
     *
     * @return  array  Массив JHtml опций.
     */
    protected function getOptions() 
    {
        // Получаем объект базы данных.
        $db = JFactory::getDbo();
 
        // Конструируем SQL запрос.
        $query = $db->getQuery(true);
        $query->select('product_type_id, name')
                ->from('#__jshopmenu_product_types')
                ->where('published = 1');
        $db->setQuery($query);
        $messages = $db->loadObjectList();
 
        // Массив JHtml опций.
        $options = array();

        if ($messages)
        {
            foreach($messages as $message) 
            {
                $options[] = JHtml::_('select.option', $message->product_type_id, $message->name);
            }
        }else{
            $options[] = JHtml::_('select.option', 0, JText::_('COM_JSHOPMENU_ERROR_CATEGORY_NOT_FOUND'));
        }

        $options = array_merge(parent::getOptions(), $options);
 
        return $options;
    }
}
?>