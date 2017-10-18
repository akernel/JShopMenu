<?php
    defined('_JEXEC') or die;
?>
<div class="mod-form-block">  
    <div class="mod-form-form">
        <div class="mod-form-title">
            <h3>Заказать меню</h3>
        </div>
                    
        <div class="mod-form-form">
            <form action="<?php echo JRoute::_ ( 'index.php?option=com_jshopmenu&view=category&category_id='.(int)$this->menu['category_id'] , false); ?>" method="post" name="menu">
            <div class="input-form-popup text">
                <input type="text" required="1" placeholder="Имя" value="" name="name" class="" id="name" />
            </div>
            <div class="input-form-popup telefon">   
                <span id="telefon">Телефон</span><div>+7(<input id="tel1" class="numbersOnly required" type="tel" name="telefon[]" placeholder="___" maxlength="3" size="2" />) <input id="tel2" class="numbersOnly required" type="tel" name="telefon[]" placeholder="___" maxlength="3" size="2" /> - <input id="tel3" class="numbersOnly required" type="tel" name="telefon[]" placeholder="__" maxlength="2" size="1" /> - <input id="tel4" class="numbersOnly required" type="tel" name="telefon[]" placeholder="__" maxlength="2" size="1" /></div>
            </div>
            <div class="input-form-popup text">
                <input type="text" required="1" placeholder="email" value="" name="email" class="" id="email" />
            </div>
            <div class="input-form-popup text"> 
            <?php
                $den = date ("d");
                $mes = date ("m");
                $god = date ("Y");
            ?>    
                <div class="input-append">
                    <input id="data" type="text" placeholder="Дата мероприятия" value="<?php echo $den.'-'.$mes.'-'.$god; ?>" name="data" title="" />
                    <button id="open-calendar" class="btn" type="button">
                        <span class="icon-calendar"></span>
                    </button>
                </div>
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        Calendar.setup({
                			// Id of the input field
                			inputField: "data",
                			// Format of the input field
                			ifFormat: "%d-%m-%Y",
                			// Trigger for the calendar (button ID)
                			button: "open-calendar",
                			// Alignment (defaults to "Bl")
                			align: "Tl",
                			singleClick: true,
                			firstDay: 1
            			});
                    });
                </script>                       
            </div>
            <div class="input-form-popup time">
                <span id="time">Начало мероприятия</span><div><input id="time1" type="tel" class="numbersOnly" name="time[]" placeholder="00" maxlength="2" size="1" />:<input id="time2" type="tel" class="numbersOnly" name="time[]" placeholder="00" maxlength="2" size="1" /></div>
            </div>
            <div class="input-form-popup text">
                <input id="kol_chas" type="text" name="kol_chas" class="numbersOnly" placeholder="Продолжительность мероприятия" />
            </div>
            <div class="input-form-popup text">
                <input type="text" placeholder="Кол-во персон"<?php echo ($this->menu_type->params->get('show_category_koll_person') ? ' readonly="readonly"' : ''); ?> value="<?php echo ($this->menu_type->params->get('show_category_koll_person') ? $this->menu['kol_person'] : ''); ?>" name="gosti" class="" id="gosti" />
            </div>
            <div class="dop-uslugi">
                <h3>Дополнительные услуги</h3>
            </div>                   
            <div class="input-form-popup checkbox">
                <input type="checkbox" value="подбор места проведения" name="uslugi[]" class="" id="podbor" />
                <label for="podbor">подбор места проведения</label>
            </div>
            <div class="input-form-popup checkbox">
                <input type="checkbox" value="аренда мебели" name="uslugi[]" class="" id="arenda" />
                <label for="arenda">аренда мебели</label>
            </div>
            <div class="input-form-popup checkbox">
                <input type="checkbox" value="украшение зала" name="uslugi[]" class="" id="ukrashenie" />
                <label for="ukrashenie">украшение зала</label>
            </div>
            <div class="input-form-popup checkbox">
                <input type="checkbox" value="дискотека" name="uslugi[]" class="" id="disco" />
                <label for="disco">дискотека</label>
            </div>
            <div class="input-form-popup checkbox">
                <input type="checkbox" value="развлекательная программа" name="uslugi[]" class="" id="razvlek" />
                <label for="razvlek">развлекательная программа</label>
            </div>
            <div class="input-submit">
                <input type="submit" value="Заказать" name="submit" class="btn-primary" />
            </div>
            <input type="hidden" name="sendmenu" value="1" />
            <?php foreach($this->menu as $key=>$value): ?>
            <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
            <?php endforeach; ?>
            </form>
            <a href="#" class="cloze-form-popup">Закрыть</a>
        </div>
    </div>
</div>