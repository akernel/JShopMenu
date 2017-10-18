<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Загружаем тултипы.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
?>

<form action="<?php echo JRoute::_('index.php?option=com_jshopmenu&task=import.import'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <fieldset>
        <legend>Импортировать товар меню из XLS</legend>
        <table width="100%">
            <tr>
                <td>Выберете файл импорта</td>
                <td><input type="file" name="inport_file" value="" /></td>
            </tr>
            <tr>
                <td>Выберете в какие категории импортировать товар</td>
                <td>
                    <select class="cat-list" name="category_id">
                        <?php foreach($this->categories as $category): ?>
                            <option value="<?=$category->category_id?>"><?=$category->name?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Выберете в кокай тип меню импортировать товар</td>
                <td>
                    <select class="menu-list" name="menu_type_id">
                        <option value="0">Выберете тип меню</option>
                        <?php foreach($this->menu_types as $menu_type): ?>
                            <option value="<?=$menu_type->menu_type_id?>"><?=$menu_type->name?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Начало таблицы</td>
                <td>
                    <input type="text" name="start" value="" />
                </td>
            </tr>
            <tr>
                <td>Начало таблицы</td>
                <td>
                    <input type="text" name="end" value="" />
                </td>
            </tr>
            <tr>
                <td>Сортировка</td>
                <td>
                    <label>
                        <input type="radio" name="sort" value="1" />
                        Да
                    </label>
                    <label>
                        <input type="radio" name="sort" value="0" checked="" />
                        Нет
                    </label>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="Импортировать" /></td>
            </tr>
        </table>
    </fieldset>
</form>