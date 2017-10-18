<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку таблиц Joomla.
jimport('joomla.database.table');

 
/**
 * Класс таблицы Menutype.
 */
class JShopMenuTableMenutype extends JTable
{
    /**
     * Конструктор.
     *
     * @param   JDatabase  &$db  Коннектор объекта базы данных.
     */
    function __construct(&$db) 
    {
        parent::__construct('#__jshopmenu_menu_types', 'menu_type_id', $db);
    }
    /**
     * Переопределяем bind метод JTable.
     *
     * @param   array  $array   Массив значений.
     * @param   array  $ignore  Массив значений, которые должны быть игнорированы.
     *
     * @return  boolean  True если все прошло успешно, в противном случае false.
     */
    public function bind($array, $ignore = array())
    {
        if (isset($array['params']) && is_array($array['params']))
        {
            // Конвертируем поле параметров в JSON строку.
            $parameter = new JRegistry;
            $parameter->loadArray($array['params']);
            $array['params'] = (string) $parameter;
        }
 
        return parent::bind($array, $ignore);
    }
 
    /**
     * Переопределяем load метод JTable.
     *
     * @param   int      $pk     Первичный ключ.
     * @param   boolean  $reset  Сбрасывать данные перед загрузкой или нет.
     *
     * @return  boolean  True если все прошло успешно, в противном случае false.
     */
    public function load($pk = null, $reset = true)
    {
        if (parent::load($pk, $reset))
        {
            // Конвертируем поле параметров в регистр.
            $params = new JRegistry;
            $params->loadString($this->params);
            $this->params = $params;
 
            return true;
        }
        else
        {
            return false;
        }
    }
    /**
     * Метод для изменения состояния сообщения.
     *
     * @param   mixed    $pks     Необязательный массив первичных ключей.
     * @param   integer  $published   Код состояния.
     * @param   integer  $userId  Id пользователя, который производит операцию.
     *
     * @return  boolean  True если состояние успешно установлено.
     *
     * @throws  RuntimeException
     */
    public function publish($pks = null, $published = 1, $userId = 0)
    {
        $k = $this->_tbl_key;
     
        // Очищаем входные параметры.
        JArrayHelper::toInteger($pks);
        $published = (int) $published;
     
        // Если первичные ключи не установлены, то проверяем ключ в текущем объекте.
        if (empty($pks))
        {
            if ($this->$k)
            {
                $pks = array($this->$k);
            }
            else
            {
                throw new RuntimeException(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'), 500);
            }
        }
     
        // Устанавливаем состояние для всех первичных ключей.
        foreach ($pks as $pk)
        {
            // Загружаем сообщение.
            if (!$this->load($pk))
            {
                throw new RuntimeException(JText::_('COM_HELLOWORLD_TABLE_ERROR_RECORD_LOAD'), 500);
            }
     
            $this->published = $published;
     
            // Сохраняем сообщение.
            if (!$this->store())
            {
                throw new RuntimeException(JText::_('COM_HELLOWORLD_TABLE_ERROR_RECORD_STORE'), 500);
            }
        }
     
            return true;
    }
}
?>