<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
JHtml::_('behavior.calendar');
?>
<script src="/media/com_jshopmenu/js/widget.js" type="text/javascript"></script>
<script src="/media/com_jshopmenu/js/accordion.js" type="text/javascript"></script>
<div class="jshopmenu-category">
<?php if ($this->params->get('show_page_heading')) : ?>
    <header class="category-header clearfix">
        <h1 class="category-title">
            <?php echo $this->escape($this->params->get('page_heading')); ?>
        </h1>
    </header>
<?php endif; ?>
<section class="category-content">
    <div class="products-list">
        <div class="product-menu">
            <div class="tabs">
                <?php $active = true; ?>
                <?php foreach($this->menu_types as $key=>$menu_types): ?>
                    <div id="tab-<?=$menu_types->menu_type_id?>" class="tab-elem<?php echo($active ? ' active' : ''); ?>" ><a href="#" data-menutypeid="<?=$menu_types->menu_type_id?>"><?=$menu_types->name?></a></div>
                    <?php $active = false; ?>
                <?php endforeach; ?>
            </div>
            <div class="line-tab"></div>
            <div class="tab-desc">
            <?php $active = true; ?>
            <?php foreach($this->menu_types as $key=>$menu_types): ?>
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
                <?php if($key == 0): ?>
                <div id="cont-<?=$menu_types->menu_type_id?>" class="tab-cont<?php echo($active ? ' active' : ''); ?>">
                
                    <div class="css">
                        <style type="text/css">
                            <?php echo $menu_types->params->get('menu_type_css'); ?>
                        </style>
                    </div>
                    
                    <?php if($menu_types->params->get('show_category_short_name')): ?>
                    <div class="title-tab"><?=$this->category->short_name?> "<span><?=$menu_types->name?></span>"</div>
                    <?php endif; ?>
                    
                    <form id="form-<?=$menu_types->menu_type_id?>" action="<?php  echo JRoute::_ ( 'index.php?option=com_jshopmenu&task=category.open_form' , false); ?>" method="post" class="list-menu">
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
                </div>
                <?php else: ?>
                <div id="cont-<?=$menu_types->menu_type_id?>" class="tab-cont<?php echo($active ? ' active' : ''); ?>">
                    <img class="load" src="/media/com_jshopmenu/images/load.png" />
                </div>
                <?php endif; ?>
                <?php $active = false; ?>
            <?php endforeach; ?>
            </div>
            <div class="open-form">
                <div class="mod-form">
                    <div id="form-module-<?=$this->category->virtuemart_category_id?>" class="mod-form-cont">
                        <div class="mod-form-overite">
                            
                        </div>
                    </div>
                </div>
            </div>
            <?php if(isset($this->sendmenuOK)): ?>
            <div class="sendmail">
                <?php if($this->sendmenuOK == 1): ?>
                <p style="color: green;">Ваш заказ отправлен. Наши менедженры свяжутся с Вами в ближайшее время для уточнения деталей заказа.</p>
                <?php else: ?>
                <p style="color: red;">Ошибка при отправлении! Попробуйте еще раз!</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <script type="text/javascript">
            jQuery( document ).ready(function() {
                
                setTimeout(function(){
                    jQuery(".sendmail").remove();
                }, 2500);
                
                jQuery(".product-menu").on('keypress', 'input.collichestvo' ,function(event){
                    if(event.keyCode == 13){
                        return false;
                    }
                    if(event.which!=8 && event.which!=0 && event.which!=109 && event.which!=188 && event.which!=190 && (event.which<48 || event.which>57)) return false;
                });
                
                jQuery(".product-menu").on('submit', 'form.list-menu', function(e){
                    e.preventDefault();
                    jQuery.ajax({
                        type: "POST",
                        url: jQuery(this).attr('action'),
                        data: jQuery(this).serialize(),
                        dataType: "html",
                        success: function(data){
                            jQuery(".open-form .mod-form-overite").html(data);
                            jQuery(".open-form .mod-form-cont").fadeIn(200);
                        }
                    });
                });
                
                jQuery(".open-form").on('click', '.cloze-form-popup', function(e){
                    e.preventDefault();
                    jQuery(".open-form .mod-form-cont").fadeOut(200);
                });
                
                jQuery("#accordion").accordion();
                
                jQuery(".product-menu").on('click', '.product-types .glyphicon', function(e){
                    e.preventDefault();
                    var all_shirena = 0;
                    var shirena = parseInt(jQuery(".product-types .mf-custom-block-vis").width());
                    jQuery(".product-types").find(".ptype-elem").each(function(){
                        all_shirena = all_shirena + parseInt(jQuery(this).width()) + 3;
                    });
                    if(all_shirena < shirena){
                        jQuery(".product-types .glyphicon.glyphicon-chevron-right").removeClass('active');
                    }
                    prokrutitMF(jQuery(this),jQuery(".product-types .mf-custom-block-width"),shirena,all_shirena);
                });
                jQuery(".product-menu").on('click', '.ptype-elem > a', function (e){
                    e.preventDefault();
                    var parentx = jQuery(this).closest(".product-types");
                    var indexx = 0;
                    
                    parentx.find(".ptype-elem").each(function () {
                        jQuery(this).removeClass('active');
                    });
                    
                    jQuery(this).parent().addClass('active');
                        
                    parentx.find(".ptype-elem").each(function (index) {
                        if ( jQuery(this).hasClass('active') ) {
                            indexx = index;
                        }
                    });
                    var cont = parentx.nextAll(".ptype-desc");
                    cont.children('.ptype-cont').each(function(){
                        jQuery(this).removeClass('active');
                    });
                    
                    cont.children('.ptype-cont')[indexx].addClass('active');
                    
                });
                
                jQuery(".product-menu .tabs .tab-elem > a").on('click', function (e){
                    var remove_id;
                    e.preventDefault();
                    jQuery(this).closest('.tabs').find('.tab-elem').each(function(){
                        if(jQuery(this).hasClass('active')){
                            remove_id = jQuery(this).attr('id').split('-');
                            remove_id = remove_id[1];
                        }
                    });
                    if(OnTabChange(jQuery(this))){
                        var menu_type_id = jQuery(this).data('menutypeid');
                        jQuery(".product-menu > .tab-desc > #cont-"+remove_id).html('<img class="load" src="/media/com_jshopmenu/images/load.png" />');
                        jQuery.ajax({
                          type: "GET",
                          url: "<?php echo JRoute::_ ( 'index.php?option=com_jshopmenu&task=category.detals&category_id='.$this->category->category_id , false); ?>",
                          dataType: "html",
                          data: {menu_type_id:menu_type_id},
                          success: function(data){
                                jQuery(".product-menu > .tab-desc > #cont-"+menu_type_id).html(data);
                                
                                jQuery("#accordion").accordion();
                            }
                        });
                    }
                });
                jQuery(".product-menu").on('click', '.personi .col-pers', function (e){
                    e.preventDefault();
                    if(Podschet(jQuery(this),this.id)){
                        onSession(jQuery(this).parent().find(".collichestvo"));
                    }
                });
                jQuery(".product-menu").on('click', '.product .col-bl', function (e){
                    e.preventDefault();
                    if(Podschet(jQuery(this),this.id)){
                        onSession(jQuery(this).parent().find(".collichestvo"));
                    }
                });
                jQuery(".product-menu").on('change', '.product #col-bld', function (){
                    if(Podschet(jQuery(this),false)){
                        onSession(jQuery(this).parent().find(".collichestvo"));
                    }
                });
                jQuery(".product-menu").on('change', '.personi #col-per', function (){
                    if(Podschet(jQuery(this),false)){
                        onSession(jQuery(this).parent().find(".collichestvo"));
                    }
                });
                jQuery(".product-menu").on('keypress', '.numbersOnly', function (e) {
                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        return false;
                    }
                });
                jQuery(".product-menu").on('keyup', '.numbersOnly', function(){
                    if($(this).attr('id') == 'tel1'){
                        if($(this).val().length == 3){
                            $("#tel2").focus();
                        }
                    }
                    if($(this).attr('id') == 'tel2'){
                        if($(this).val().length == 3){
                            $("#tel3").focus();
                        }
                    }
                    if($(this).attr('id') == 'tel3'){
                        if($(this).val().length == 2){
                            $("#tel4").focus();
                        }
                    }
                    if($(this).attr('id') == 'time1'){
                        if($(this).val().length == 2){
                            $("#time2").focus();
                        }
                    }
                });
            });
            function prokrutitMF (elemClick, block, shirena, all_shirena){
                var pLeft = block.position().left;
                if(elemClick.hasClass('glyphicon-chevron-left') && elemClick.hasClass('active')){
                    pLeft = pLeft + (shirena/2);
                    if(pLeft>0){
                        pLeft = 0;
                        elemClick.removeClass('active');
                    }
                    block.css('left',pLeft+'px');
                    jQuery(".product-types .glyphicon-chevron-right").addClass('active');
                }
                if(elemClick.hasClass('glyphicon-chevron-right') && elemClick.hasClass('active')){
                    pLeft = pLeft - (shirena/2);
                    if(((-pLeft)+shirena)>all_shirena){
                        pLeft = -(all_shirena-shirena);
                        elemClick.removeClass('active');
                    }
                    block.css('left',pLeft+'px');
                    jQuery(".product-types .glyphicon-chevron-left").addClass('active');
                }
            }
            function onSession (elem){
                var coll = 1;
                if(elem.data('id')==-1){
                    coll = elem.val();
                }else{
                    coll = [];
                    coll[elem.data('id')] = elem.val();
                }
                jQuery.ajax({
                  type: "GET",
                  url: "<?php echo JRoute::_ ( 'index.php?option=com_jshopmenu&task=category.onsesion' , false); ?>",
                  dataType: "html",
                  data: {coll:coll,
                            menu_type_id:elem.data('menu-type-id'),
                            category_id:<?=$this->category->category_id?>}
                });
            }
            function OnTabChange (elem) {
                var parentx = jQuery(elem).closest(".tabs");
                var indexx = 0;
                
                parentx.find(".tab-elem").each(function () {
                    jQuery(this).removeClass('active');
                });
                
                elem.parent().addClass('active');
                    
                parentx.find(".tab-elem").each(function (index) {
                    if ( jQuery(this).hasClass('active') ) {
                        indexx = index;
                    }
                });
                var cont = parentx.nextAll(".tab-desc");
                cont.children('.tab-cont').each(function(){
                    jQuery(this).removeClass('active');
                });
                
                cont.children('.tab-cont')[indexx].addClass('active');
                
                return true;
            }
            
            function Podschet (elem,ID_elem) {
                var forma = elem.closest('form');
                var coll_elem = parseInt(elem.parent().children('input').val())
                var new_coll = 0;
                var coll_person = 1;
                var elem_change = elem.parent().children('input[type="text"]');
                
                var one_prises = forma.find('.one-prices span');
                var one_veses = forma.find('.one-veses span');
                var one_veses_voda = forma.find('.one-veses-voda span');
                var prices_l = forma.find('.prices span');
                var obslugivanie = forma.find('.obslugivanie span');
                var all_price = forma.find('.all-price span');
                
                
                if(!ID_elem){
                    if(elem_change.hasClass('personi')){
                        if(parseInt(elem_change.val()) < 1){
                            elem_change.val(1);
                        }
                    }else{
                        if(parseInt(elem_change.val()) < 0){
                            elem_change.val(0);
                        }
                    }
                }else{
                    if(ID_elem == 'prev' && coll_elem > 0){
                        new_coll = coll_elem - 1;
                    }
                    if(ID_elem == 'next'){
                        new_coll = coll_elem + 1;
                    }
                    if(elem_change.hasClass('personi')){
                        if(new_coll > 0){
                            elem_change.val(new_coll);
                        }
                    }else{
                        elem_change.val(new_coll);
                    }
                }
                coll_person = parseInt(jQuery('.personi #col-per').val());
                
                
                if(elem_change.hasClass('col-tovar')){
                    elem_change.closest(".product").find('.one-ves').text(function(){
                        return ((parseInt(elem_change.closest(".product").find('input.ves').val())*parseInt(elem_change.val()))/coll_person).toFixed(2);
                    });
                }else{
                    forma.find(".product").each(function(){
                        var product = jQuery(this);
                        product.find('.one-ves').text(function(){
                            return ((parseInt(product.find('input.ves').val())*parseInt(product.find('input.col-tovar').val()))/coll_person).toFixed(2);
                        });
                    });
                }
                
                var prices_k = 0;
                
                one_prises.text(function(){
                    var prices = 0;
                    forma.find(".product").each(function(){
                        prices += parseInt(jQuery(this).find("input.price").val())*parseInt(jQuery(this).find(".col-tovar").val());
                    });
                    prices_k = prices;
                    prices = (prices_k/coll_person) + (((prices_k*10)/100)/coll_person);
                    forma.find('input[name="menu[one_prices]"]').val(prices);
                    return prices.toFixed(2);
                });
                one_veses.text(function(){
                    var veses = 0;
                    forma.find(".product").each(function(){
                        if(jQuery(this).data('napitki') == 0){
                            veses += parseInt(jQuery(this).find("input.ves").val())*parseInt(jQuery(this).find(".col-tovar").val());
                        }
                    });
                    veses = veses/coll_person;
                    forma.find('input[name="menu[veses]"]').val(parseInt(veses));
                    return parseInt(veses);
                });
                one_veses_voda.text(function(){
                    var veses = 0;
                    forma.find(".product").each(function(){
                        if(jQuery(this).data('napitki') == 1){
                            veses += parseInt(jQuery(this).find("input.ves").val())*parseInt(jQuery(this).find(".col-tovar").val());
                        }
                    });
                    veses = veses/coll_person;
                    return parseInt(veses);
                });
                
                prices_l.text(function(){
                    forma.find('input[name="menu[prices]"]').val(prices_k);
                    return prices_k.toFixed(2);
                });
                obslugivanie.text(function(){
                    forma.find('input[name="menu[obslugivanie]"]').val((prices_k*10)/100);
                    return ((prices_k*10)/100).toFixed(2);
                });
                all_price.text(function(){
                    forma.find('input[name="menu[all_price]"]').val(((prices_k*10)/100)+prices_k);
                    return (((prices_k*10)/100)+prices_k).toFixed(2);
                });
                
                return true;
            }
        </script>
    </div>
    <?php if($this->category->params->get('show_category_image')): ?>
    <div class="cat-image">
        <img src="<?=$this->category->image?>" title="<?=$this->category->name?>" />
    </div>
    <?php endif; ?>
    <?php if($this->category->params->get('show_category_description')): ?>
    <div class="cat-desc">
        <?=$this->category->description?>
    </div>
    <?php endif; ?>
</section>
</div>