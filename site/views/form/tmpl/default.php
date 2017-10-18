<?php
defined('_JEXEC') or die;

$min_person = $this->params->get('category_min_koll_person');
$max_person = $this->params->get('category_max_koll_person');
?>
<div class="form-block">
    <div class="form-block-title">
        <h3>Заказать меню</h3>
    </div>
    <div class="form-block-content">
        <form action="/index.php?option=com_jshopmenu&task=category.send_mail" method="post" data-hide-error="7000" data-hide-hint="7000" data-hint-background="bg-red" data-hint-color="fg-white" data-role="validator" novalidate="novalidate" target="_parent">
            <div class="full-size">
                <label for="name">Ваше имя</label>
                <div class="input-control text full-size required" data-role="input">
                    <input
                        id="name" 
                        name="name"
                        type="text"
                        value=""
                        data-validate-func="required"
                        data-validate-hint="Это поле обезательное для заполнения!"
                        data-validate-hint-position="top"
                        required="required"
                         />
                    <button class="button helper-button clear"><span class="mif-cross"></span></button>
                    <span class="input-state-error mif-warning"></span>
                    <span class="input-state-success mif-checkmark"></span>
                </div>
            </div>
            
            <div class="full-size">
                <label for="telefon">Ваш телефон</label>
                <div class="input-control text full-size required" data-role="input">
                    <input
                        id="telefon" 
                        name="telefon"
                        type="text"
                        class="telefon"
                        value=""
                        data-validate-func="pattern"
                        data-validate-arg="^[0-9/+/\s-/(/)/\s/]+$"
                        data-validate-hint="Введите верный телефон"
                        data-validate-hint-position="top"
                        required="required"
                         />
                    <span class="input-state-error mif-warning"></span>
                    <span class="input-state-success mif-checkmark"></span>
                </div>
            </div>
            
            <div class="full-size">
                <label for="email">Ваш E-mail</label>
                <div class="input-control text full-size required" data-role="input">
                    <input
                        id="email" 
                        name="email"
                        type="text"
                        class="email"
                        value=""
                        data-validate-func="email"
                        data-validate-hint="Введите верный E-mail"
                        data-validate-hint-position="top"
                        required="required"
                         />
                </div>
            </div>
            
            <div class="full-size">
                <label for="organization">Организация</label>
                <div class="input-control text full-size" data-role="input">
                    <input
                        id="organization" 
                        name="organization"
                        type="text"
                        value=""
                         />
                    <button class="button helper-button clear"><span class="mif-cross"></span></button>
                </div>
            </div>
            <div class="full-size">
                <label for="date">
                    Дата мероприятия
                </label>
                <div class="input-control text time-input required full-size" data-role="datepicker" data-locale="ru" data-position="bottom">
                    <input
                        name="date"
                        type="text"
                        value=""
                        data-validate-func="required"
                        data-validate-hint="Укажите дату мероприятия"
                        data-validate-hint-position="top"
                         />
                    <button class="button"><span class="mif-calendar"></span></button>
                    <span class="input-state-error mif-warning"></span>
                    <span class="input-state-success mif-checkmark"></span>
                </div>
            </div>
            <div class="full-size">
                <div class="input-control text time-input required full-size" data-role="input">
                    <input
                        name="time_begin"
                        type="text"
                        class="time"
                        placeholder="Начало мероприятия"
                        value=""
                        data-validate-func="pattern"
                        data-validate-arg="^[0-9/:/]+$"
                        data-validate-hint="Укажите время начала мероприятия"
                        data-validate-hint-position="top"
                         />
                    <span class="input-state-error mif-warning"></span>
                    <span class="input-state-success mif-checkmark"></span>
                </div>
            </div>
            <div class="full-size">
                <div class="input-control text required full-size" data-role="input">
                    <input
                        name="time"
                        type="text"
                        class="number"
                        placeholder="Продолжительность мероприятия(часов)"
                        value=""
                        data-validate-func="required"
                        data-validate-hint="Укажите кол-во часов"
                        data-validate-hint-position="top"
                         />
                    <span class="input-state-error mif-warning"></span>
                    <span class="input-state-success mif-checkmark"></span>
                </div>
            </div>
            
            <div class="full-size">
                <label for="mesto">
                    Место проведения мероприятия
                </label>
                <div class="input-control select full-size" data-role="select">
                    <select id="mesto" name="mesto">
                        <option value="Необходимо подобрать">Необходимо подобрать</option>
                        <option value="Есть своя площадка">Есть своя площадка</option>
                    </select>
                </div>
            </div>
            
            <div class="full-size">
                <div class="input-control text block full-size" data-role="input">
                    <input
                        id="adress" 
                        name="adress"
                        type="text"
                        value=""
                        placeholder="Укажите адрес"
                         />
                    <button class="button helper-button clear"><span class="mif-cross"></span></button>
                </div>
            </div>
            
            <div class="full-size">
                <label for="koll-per">Количество персон</label>
                <div class="slider"
                        data-target="#koll-per"
                        data-role="slider"
                        data-position="<?=$this->category->koll_person?>"
                        data-buffer="<?php echo(($min_person*100)/$max_person); ?>"
                        data-show-hint="true"
                        data-min-value="0"
                        data-max-value="<?=$max_person?>"></div>
                <div class="input-control text required" data-role="input">
                    <input id="koll-per" name="kol_person" value="<?=$this->category->koll_person?>" 
                        class="number"
                        data-validate-func="min"
                        data-validate-hint="Укажите кол-во персон, имеет максимальное (<?=$max_person?>) и минемальное (<?=$min_person?>) кол-во, воспользуйтесь скролом, для более подробной информации обратитесь к менеджеру по тел. +7 (495) 970-76-54"
                        data-validate-arg="<?=$min_person?>"
                        data-validate-hint-position="right"
                    />
                </div>
            </div>
            
            <h5>Дополнительные услуги</h5>
            
            <div class="full-size clear-float">
                <label class="input-control checkbox small-check margin5 no-margin-left place-left">
                    <input type="checkbox" name="mesto" value="1" />
                    <span class="check"></span>
                    <span class="caption">подбор места проведения</span>
                </label>
                <label class="input-control checkbox small-check margin5 place-left">
                    <input type="checkbox" name="arenda" value="1" />
                    <span class="check"></span>
                    <span class="caption">аренда мебели</span>
                </label>
                <label class="input-control checkbox small-check margin5 place-left">
                    <input type="checkbox" name="ukrashenie" value="1" />
                    <span class="check"></span>
                    <span class="caption">украшение зала</span>
                </label>
                <label class="input-control checkbox small-check margin5 place-left">
                    <input type="checkbox" name="disco" value="1" />
                    <span class="check"></span>
                    <span class="caption">дискотека</span>
                </label>
                <label class="input-control checkbox small-check margin5 no-margin-right place-left">
                    <input type="checkbox" name="razvlekalka" value="1" />
                    <span class="check"></span>
                    <span class="caption">развлекательная программа</span>
                </label>
            </div>
            <div class="full-size">
                <label class="input-control checkbox small-check">
                    <input type="checkbox" name="excel" value="1" checked="checked" />
                    <span class="check"></span>
                    <span class="caption">прикрепить Excel-меню к вашему письму</span>
                </label>
            </div>
            
            
            <div class="full-size">
                <div class="padding5 align-right">
                    <input type="submit" name="submit" value="Заказать" />
                </div>
            </div>
                            
            <input type="hidden" name="category_id" value="<?=$this->category_id?>" />
            <input type="hidden" name="menu_type_id" value="<?=$this->menu_type_id?>" />
            <input type="hidden" name="url_return" value="<?=$this->url_return?>" />
        </form>
    </div>
</div>