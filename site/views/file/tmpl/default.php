<?php
defined('_JEXEC') or die;
?>
<header style="text-align: center; width: 100%; height: 50px; padding-top: 8px;">
    <a class="button margin20 no-margin-top no-margin-bottom" href="<?php echo JRoute::_ ( 'index.php?option=com_jshopmenu&task=category.uploadfile&menu_type_id='.$this->menu_type_id.'&category_id='.$this->category_id.'&tmpl=component' , false); ?>" target="_blank"> Распечатать файл </a>
    <a class="button margin20 no-margin-top no-margin-bottom" href="<?php echo JRoute::_ ( 'index.php?option=com_jshopmenu&task=category.uploadfile&menu_type_id='.$this->menu_type_id.'&category_id='.$this->category_id.'&tmpl=component' , false); ?>" target="_blank"> Скачать файл </a>
</header>
<iframe src="/<?=$this->file_src?>" width="100%" height="90%" style="width: 100%; margin-top: -50px; height: 99%;">

</iframe>
<style>
.component{
    width: 100%;
    height: 100%;
}
</style>