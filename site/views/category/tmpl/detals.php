<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

?>
<?php $menu_types = $this->menu_type; ?>
<?php
    $veses = 0.00;
    $prices = 0.00;
    $one_prices = 0.00;
    $one_veses = 0.00;
    $one_veses_voda = 0.00;
    $obslugivanie = 0.00;
    $all_price = 0.00;
    $kol_person = $this->category->koll_person;
?>
    
<?php if($menu_types->params->get('show_category_short_name')): ?>
<div class="title-tab"><?=$this->category->short_name?> "<span><?=$menu_types->name?></span>"</div>
<?php endif; ?>

<form id="form-<?=$menu_types->menu_type_id?>" action="<?php echo JRoute::_ ( 'index.php?option=com_jshopmenu&task=category.open_form' , false); ?>" method="post" class="list-menu">
<?php if($menu_types->params->get('show_category_koll_person')): ?>
    <?php if($menu_types->params->get('category_koll_person_edit')): ?>
        <?php
            $kol_person = isset($_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_per']) && !empty($_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_per']) && $_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_per']>0 ? $_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_per'] : $kol_person;
        ?>
        <p class="personi">Количество персон  <span id="prev" class="col-pers">-</span><input type="text" id="col-per" name="menu[kol_person]" value="<?php echo($menu_types->create_your && !isset($_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_per']) ? 1 : $kol_person); ?>" class="personi collichestvo" data-id="-1" data-menu-type-id="<?=$menu_types->menu_type_id?>" /><span id="next" class="col-pers">+</span></p>
    <?php else: ?>
        <p class="personi">Количество персон  <?=$kol_person?></p>
        <input id="col-per" class="personi collichestvo" type="hidden" name="menu[kol_person]" value="<?=$kol_person?>" />
    <?php endif; ?>
<?php else: ?>
<input id="col-per" class="personi collichestvo" type="hidden" name="menu[kol_person]" value="<?=$kol_person?>" />
<?php endif; ?>

<?php if($menu_types->params->get('show_category_product_type')==1): ?>
    <div class="product-types">
        <i class="glyphicon glyphicon-chevron-left"></i>
        <div class="mf-custom-block-vis">
            <div class="mf-custom-block-width">
            <?php $active = true;$kl=0; ?>
            <?php foreach($this->product_types as $product_type): ?>
                <?php if(!empty($product_type->products)): ?>
                    <div id="ptype-<?=$product_type->product_type_id?>" class="ptype-elem<?php echo ($active ? ' active' : ''); ?>"><a href=""><?=$product_type->name?></a></div>
                    <?php 
                        $active = false;
                        $kl++; 
                    ?>
                <?php endif;?>
            <?php endforeach; ?>
            </div>
        </div>
        <i class="glyphicon glyphicon-chevron-right<?php echo($kl>4 ? ' active' : ''); ?>"></i>
    </div>
<?php endif; ?>

    <div class="menu-atribut">
        <ul class="product-table-list">
            <?php if($menu_types->params->get('show_menu_type_image')): ?>
            <li>&nbsp;</li>
            <?php endif; ?>
            <?php if($menu_types->params->get('show_menu_type_name')): ?>
            <li><?php echo JText::_('COM_JSHOPMENU_SHOW_MENU_TYPES_NAME_FIELD'); ?><?php if($menu_types->params->get('show_menu_type_short_description')): ?><?php echo JText::_('COM_JSHOPMENU_SHOW_MENU_TYPES_SHORT_DESCRIPTION_FIELD'); ?><?php endif; ?></li>
            <?php endif; ?>
            <?php if($menu_types->params->get('show_menu_type_ves')): ?>
            <li><?php echo JText::_('COM_JSHOPMENU_SHOW_MENU_TYPES_VES_FIELD'); ?></li>
            <?php endif; ?>
            <?php if($menu_types->params->get('show_menu_type_price')): ?>
            <li><?php echo JText::_('COM_JSHOPMENU_SHOW_MENU_TYPES_PRICE_FIELD'); ?></li>
            <?php endif; ?>
            <?php if($menu_types->params->get('show_menu_type_koll')): ?>
            <li><?php echo JText::_('COM_JSHOPMENU_SHOW_MENU_TYPES_KOLL_FIELD'); ?></li>
            <?php endif; ?>
            <?php if($menu_types->params->get('show_menu_type_prices')): ?>
            <li><?php echo JText::_('COM_JSHOPMENU_SHOW_MENU_TYPES_PRICES_FIELD'); ?></li>
            <?php endif; ?>
            <?php if($menu_types->params->get('show_menu_type_one_ves')): ?>
            <li><?php echo JText::_('COM_JSHOPMENU_SHOW_MENU_TYPES_ONE_VES_FIELD'); ?></li>
            <?php endif; ?>
        </ul>
    </div>
    
    
    <?php if($menu_types->params->get('show_category_product_type')==0): ?>
    <div id="accordion" class="slide-block accordion">
    <?php $active = true;?>
    <?php foreach($this->product_types as $product_type): ?>
        <?php if(!empty($product_type->products)): ?>
        <div class="frame">
        <div class="heading"><h2 class="manufactur"><?=$product_type->name?><i class="mf-tag glyphicon glyphicon-chevron-down"></i></h2></div>
        <div class="content one-slide<?php echo($active ? ' active' : ''); ?>">
        <?php foreach($product_type->products as $product): ?>
            <?php
                 $ves = $product->ves;
                 $price = $product->price;
                 $koll = isset($_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_bld'][$product->product_id]) && !empty($_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_bld'][$product->product_id]) && $_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_bld'][$product->product_id]>=0 ? $_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_bld'][$product->product_id] : (int)$product->koll;
                 
                 $veses += $ves*$koll;
                 $prices += $price*$koll;
                 $one_ves = ($ves*$koll)/$kol_person;
                 $one_veses += $product_type->napitki == 0 ? $one_ves : 0;
                 $one_veses_voda += $product_type->napitki == 1 ? $one_ves : 0;
            ?>
                <div class="product"<?php echo($product_type->napitki ? ' data-napitki="1"' : ' data-napitki="0"') ?>>
                    <div class="detals">
                        <ul class="product-table-list">
                            <?php if($menu_types->params->get('show_menu_type_image')): ?>
                            <li class="image"><span class="image" style="background-image: url(<?=$product->image?>);"></span></li>
                            <?php endif; ?>
                            <?php if($menu_types->params->get('show_menu_type_name')): ?>
                            <li class="name">
                                <span class="name"><?=$product->name?></span>
                                <?php if($menu_types->params->get('show_menu_type_short_description')): ?>
                                <span class="short-desc">(<?=$product->short_description?>)</span>
                                <?php endif; ?>
                            </li>
                            <?php endif; ?>
                            <?php if($menu_types->params->get('show_menu_type_ves')): ?>
                            <li class="ves"><?=(int)$ves?></li>
                            <?php endif; ?>
                            <?php if($menu_types->params->get('show_menu_type_price')): ?>
                            <li class="price"><?=(float)$price?></li>
                            <?php endif; ?>
                            <?php if($menu_types->params->get('show_menu_type_koll')): ?>
                            <li class="koll">
                                <?php if($menu_types->params->get('menu_type_koll_edit')): ?>
                                <span id="prev" class="col-bl">-</span><input id="col-bld" class="collichestvo col-tovar" type="text" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][koll]" value="<?=$koll?>" data-id="<?=$product->product_id?>" data-menu-type-id="<?=$menu_types->menu_type_id?>" /><span id="next" class="col-bl">+</span>
                                <?php else: ?>
                                <?=(int)$koll?>
                                <input id="col-bld" class="collichestvo col-tovar" type="hidden" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][koll]" value="<?=$koll?>" />
                                <?php endif; ?>
                            </li>
                            <?php endif; ?>
                            <?php if($menu_types->params->get('show_menu_type_prices')): ?>
                            <li class="prices"><?=(float)($price*$koll)?></li>
                            <?php endif; ?>
                            <?php if($menu_types->params->get('show_menu_type_one_ves')): ?>
                            <li class="one-ves"><?=(int)$one_ves?></li>
                            <?php endif; ?>
                        </ul>
                            <?php if(!$menu_types->params->get('show_menu_type_koll')): ?>
                            <input id="col-bld" class="collichestvo col-tovar" type="hidden" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][koll]" value="<?=$koll?>" />
                            <?php endif; ?>
                            <input type="hidden" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][name]" value="<?=$product->name?>" />
                            <input class="ves" type="hidden" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][ves]" value="<?=(int)$ves?>" />
                            <input class="price" type="hidden" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][price]" value="<?=$price?>" />
                            <input type="hidden" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][napitki]" value="<?=$product_type->napitki?>" />
                    </div>
                </div>
        <?php endforeach; ?>
        </div>
        </div>
        <?php $active = false; ?>
        <input type="hidden" name="tovar[<?=$product_type->product_type_id?>][name]" value="<?=$product_type->name?>" />
        <?php endif; ?>
    <?php endforeach; ?>
</div>
    <?php else: ?>
        <div class="ptype-desc">
            <?php $active = true; ?>
            <?php foreach($this->product_types as $product_type): ?>
                <?php if(!empty($product_type->products)): ?>
                <div id="ptype-<?=$product_type->product_type_id?>" class="ptype-cont<?php echo ($active ? ' active' : ''); ?>">
                    <?php foreach($product_type->products as $product): ?>
                        <?php
                             $ves = $product->ves;
                             $price = $product->price;
                             $koll = isset($_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_bld'][$product->product_id]) && !empty($_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_bld'][$product->product_id]) && $_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_bld'][$product->product_id]>=0 ? $_SESSION['jshopmenu'][$this->category->category_id][$menu_types->menu_type_id]['col_bld'][$product->product_id] : (int)$product->koll;
                             
                             $veses += $ves*$koll;
                             $prices += $price*$koll;
                             $one_ves = ($ves*$koll)/$kol_person;
                             $one_veses += $product_type->napitki == 0 ? $one_ves : 0;
                             $one_veses_voda += $product_type->napitki == 1 ? $one_ves : 0;
                        ?>
                            <div class="product"<?php echo($product_type->napitki ? ' data-napitki="1"' : ' data-napitki="0"') ?>>
                                <div class="detals">
                                    <ul class="product-table-list">
                                        <?php if($menu_types->params->get('show_menu_type_image')): ?>
                                        <li class="image"><span class="image" style="background-image: url(<?=$product->image?>);"></span></li>
                                        <?php endif; ?>
                                        <?php if($menu_types->params->get('show_menu_type_name')): ?>
                                        <li class="name">
                                            <span class="name"><?=$product->name?></span>
                                            <?php if($menu_types->params->get('show_menu_type_short_description')): ?>
                                            <span class="short-desc">(<?=$product->short_description?>)</span>
                                            <?php endif; ?>
                                        </li>
                                        <?php endif; ?>
                                        <?php if($menu_types->params->get('show_menu_type_ves')): ?>
                                        <li class="ves"><?=(int)$ves?></li>
                                        <?php endif; ?>
                                        <?php if($menu_types->params->get('show_menu_type_price')): ?>
                                        <li class="price"><?=(float)$price?></li>
                                        <?php endif; ?>
                                        <?php if($menu_types->params->get('show_menu_type_koll')): ?>
                                        <li class="koll">
                                            <?php if($menu_types->params->get('menu_type_koll_edit')): ?>
                                            <span id="prev" class="col-bl">-</span><input id="col-bld" class="collichestvo col-tovar" type="text" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][koll]" value="<?=$koll?>" data-id="<?=$product->product_id?>" data-menu-type-id="<?=$menu_types->menu_type_id?>" /><span id="next" class="col-bl">+</span>
                                            <?php else: ?>
                                            <?=(int)$koll?>
                                            <input id="col-bld" class="collichestvo col-tovar" type="hidden" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][koll]" value="<?=$koll?>" />
                                            <?php endif; ?>
                                        </li>
                                        <?php endif; ?>
                                        <?php if($menu_types->params->get('show_menu_type_prices')): ?>
                                        <li class="prices"><?=(float)($price*$koll)?></li>
                                        <?php endif; ?>
                                        <?php if($menu_types->params->get('show_menu_type_one_ves')): ?>
                                        <li class="one-ves"><?=(int)$one_ves?></li>
                                        <?php endif; ?>
                                    </ul>
                                        <?php if(!$menu_types->params->get('show_menu_type_koll')): ?>
                                        <input id="col-bld" class="collichestvo col-tovar" type="hidden" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][koll]" value="<?=$koll?>" />
                                        <?php endif; ?>
                                        <input type="hidden" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][name]" value="<?=$product->name?>" />
                                        <input class="ves" type="hidden" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][ves]" value="<?=(int)$ves?>" />
                                        <input class="price" type="hidden" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][price]" value="<?=$price?>" />
                                        <input type="hidden" name="tovar[<?=$product_type->product_type_id?>][product][<?=$product->product_id?>][napitki]" value="<?=$product_type->napitki?>" />
                                </div>
                            </div>
                    <?php endforeach; ?>
                </div>
                <?php $active = false; ?>
                <input type="hidden" name="tovar[<?=$product_type->product_type_id?>][name]" value="<?=$product_type->name?>" />
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php
        $obslugivanie = ($prices*10)/100;
        $one_prices = ($prices/$kol_person) + ($obslugivanie/$kol_person);
        $all_price = $obslugivanie+$prices;
    ?>
    <div class="one-veses hidden-opt<?php echo($menu_types->params->get('show_menu_type_one_veses') ? ' active' : ''); ?>">
        Выход меню на 1 персону:  <span><?=(int)$one_veses?></span>  гр. 
    </div>
    <div class="one-veses-voda hidden-opt<?php echo($menu_types->params->get('show_menu_type_one_veses_voda') ? ' active' : ''); ?>">
        Выход напитков на 1 персону:  <span><?=(int)$one_veses_voda?></span>  мл. 
    </div>
    <div class="prices hidden-opt<?php echo($menu_types->params->get('show_menu_type_prices_l') ? ' active' : ''); ?>">
        Полная стоимость организации питания:  <span><?=round($prices,2)?></span>  руб. 
    </div>
    <div class="obslugivanie hidden-opt<?php echo($menu_types->params->get('show_menu_type_obslugivanie') ? ' active' : ''); ?>">
        Обслуживание 10%  <span><?=round($obslugivanie,2)?></span>  руб. 
    </div>
    <div class="one-prices hidden-opt<?php echo($menu_types->params->get('show_menu_type_one_prices') ? ' active' : ''); ?>">
        Полная стоимость на 1 персону:  <span><?=round($one_prices,2)?></span>  руб. 
    </div>
    <div class="all-price hidden-opt<?php echo($menu_types->params->get('show_menu_type_all_price') ? ' active' : ''); ?>">
        <strong>Итого к оплате:  <span><?=round($all_price,2)?></span>  руб. </strong>
    </div>
    <div class="button-menu">
        <?php 
            $file_id = $menu_types->menu_type_id;
        ?>
        <?php if(!empty($this->category->file->$file_id)): ?>
        <a id="razdel-<?=$menu_types->menu_type_id?>" class="btn btn-primary pricing-btn zakazat-menu" href="<?php echo JRoute::_ ( 'index.php?option=com_jshopmenu&task=category.uploadfile&menu_type_id='.$menu_types->menu_type_id.'&category_id='.$this->category->category_id , false); ?>" target="_blank"> Скачать меню </a>
        <?php endif; ?>
        <input type="submit" id="razdel-<?=$menu_types->menu_type_id?>" class="btn btn-primary pricing-btn zakazat-menu" name="submit" value=" Заказать меню " />
    </div>
    <input type="hidden" name="menu[category_id]" value="<?=$this->category->category_id?>" />
    <input type="hidden" name="menu[menu_type_id]" value="<?=$menu_types->menu_type_id?>" />
    <input type="hidden" name="menu[veses]" value="<?=$veses?>" />
    <input type="hidden" name="menu[prices]" value="<?=$prices?>" />
    <input type="hidden" name="menu[one_prices]" value="<?=$one_prices?>" />
    <input type="hidden" name="menu[one_veses]" value="<?=$one_veses?>" />
    <input type="hidden" name="menu[one_veses_voda]" value="<?=$one_veses_voda?>" />
    <input type="hidden" name="menu[obslugivanie]" value="<?=$obslugivanie?>" />
    <input type="hidden" name="menu[all_price]" value="<?=$all_price?>" />
    <input type="hidden" name="url_return" value="<?=$this->url_return?>" />
    <input type="hidden" name="menu[create_your]" value="<?php echo ($menu_types->create_your ? 1 : 0); ?>" />
</form>