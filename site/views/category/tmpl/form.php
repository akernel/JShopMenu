<?php
defined('_JEXEC') or die;
?>
<div class="form-block">
    <div class="form-block-title">
        <h3>Заказать меню</h3>
    </div>
    <div class="form-block-form">
        <form method="post" action="/index.php" data-hide-error="7000" data-hide-hint="7000" data-hint-background="bg-red" data-hint-color="fg-white" data-role="validator" novalidate="novalidate" target="_parent">
            <?php
                if(count($this->fields) > 0):
                foreach($this->fields as $key=>$field):
            ?>
            <div class="full-size">
            <?php
                
                switch($field->fieldtypes):
                    case 'text':
                    case 'password':
                    case 'email':
                    case 'tel':
                    case 'time':
                    case 'number':
                    case 'date':
                        $fieldtype = $field->fieldtypes=='date' 
                                        || $field->fieldtypes=='email' 
                                        || $field->fieldtypes=='tel' 
                                        || $field->fieldtypes=='time' 
                                            ? 'text' : $field->fieldtypes;
            ?>
                <?php if($field->label_on): ?>
                <label for="<?=$field->name?>">
                    <?=$field->label?>
                    <?php if($field->required): ?>
                    <span style="color: #ff0000; font-size: 12px; vertical-align: top;">*</span>
                    <?php endif; ?>
                </label>
                <?php endif; ?>
                <div class="input-control <?=$fieldtype?> full-size<?php echo($field->required ? ' required' : ''); ?>"<?php echo($field->fieldtypes=='date' ? ' data-role="datepicker" data-locale="ru" data-position="top"' : ' data-role="input"'); ?>>
                    <input 
                        id="<?=$field->name?>"
                        type="<?=$fieldtype?>"
                        name="<?=$field->name?>"
                        value="<?=$field->value?>"
                        <?php if($field->placeholder_on): ?>
                        placeholder="<?=$field->label?>"
                        <?php endif; ?>
                        <?php if($field->fieldtypes=='email'): ?>
                        class="email"
                        <?php elseif($field->fieldtypes=='tel'): ?>
                        class="telefon"
                        <?php elseif($field->fieldtypes=='time'): ?>
                        class="time"
                        <?php endif; ?>
                        <?php if($field->required): ?>
                            <?php if($field->fieldtypes=='email'): ?>
                            data-validate-func="email"
                            data-validate-hint="Введите верный E-mail" 
                            <?php elseif($field->fieldtypes=='tel'): ?>
                            data-validate-func="pattern"
                            data-validate-arg="^[0-9/+/\s-/(/)/\s/]+$"
                            data-validate-hint="Введите верный телефон"
                            <?php elseif($field->fieldtypes=='time'): ?>
                            data-validate-func="pattern"
                            data-validate-arg="^[0-9/:/]+$"
                            data-validate-hint="Введите верное время"
                            <?php else: ?>
                            data-validate-func="required"
                            data-validate-hint="Это поле обезательное для заполнения!"
                            <?php endif; ?>
                            data-validate-hint-position="top"
                        <?php endif; ?>
                        <?php echo(
                            ($field->required ? ' required="required"' : '').
                            ($field->readonly ? ' readonly="readonly"' : '').
                            ($field->disabled ? ' disabled="disabled"' : '')); ?>
                        />
                        <?php if($field->fieldtypes=='text'): ?>
                        <button class="button helper-button clear"><span class="mif-cross"></span></button>
                        <?php elseif($field->fieldtypes=='password'): ?>
                        <button class="button helper-button reveal"><span class="mif-looks"></span></button>
                        <?php elseif($field->fieldtypes=='date'): ?>
                        <button class="button"><span class="mif-calendar"></span></button>
                        <?php endif; ?>
                        <?php if($field->required): ?>
                        <span class="input-state-error mif-warning"></span>
                        <span class="input-state-success mif-checkmark"></span>
                        <?php endif; ?>
                </div>
            <?php   
                        break;
                    case 'radio':
                    case 'checkbox':
            ?>
            <?php if(isset($field->value) && count($field->value)>0): ?>
                <?php if($field->label_on): ?>
                <h5><?=$field->label?><?php echo ($field->required ? '<span style="color: #ff0000; font-size: 12px; vertical-align: top;">*</span>' : ''); ?></h5>
                <?php endif; ?>
                <?php foreach($field->value as $value): ?>
                    <label class="input-control <?=$field->fieldtypes?> small-check<?php echo($field->required ? ' required' : ''); ?>">
                        <input 
                        id="<?=$field->name?>" 
                        type="<?=$field->fieldtypes?>" 
                        name="<?=$field->name?><?php echo($field->fieldtypes=='checkbox' && count($field->value)>1 ? '[]' : ''); ?>" 
                        value="<?=$value?>"
                        <?php echo($field->required ? ' required="required"' : ''); ?> 
                        />
                        <span class="check"></span>
                        <span class="caption"><?=$value?></span>
                    </label>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php
                        break;
                   case 'textarea': 
            ?>
                <?php if($field->label_on): ?>
                <label for="<?=$field->name?>">
                    <?=$field->label?>
                    <?php if($field->required): ?>
                    <span style="color: #ff0000; font-size: 12px; vertical-align: top;">*</span>
                    <?php endif; ?>
                </label>
                <?php endif; ?>
                <div class="input-control <?=$field->fieldtypes?> full-size">
                    <textarea 
                    id="<?=$field->name?>"
                    name="<?=$field->name?>"
                    <?php if($field->placeholder_on): ?>
                    placeholder="<?=$field->label?>"
                    <?php endif; ?>
                    <?php echo(
                            ($field->required ? ' required="required"' : '').
                            ($field->readonly ? ' readonly="readonly"' : '').
                            ($field->disabled ? ' disabled="disabled"' : '')); ?>
                    ><?=$field->value?></textarea>
                </div>
            <?php
                        break;
                   case 'select': 
            ?>
            <?php if((isset($field->value) && count($field->value)>0) || $field->empty): ?>
                <?php if($field->label_on): ?>
                <label for="<?=$field->name?>">
                    <?=$field->label?>
                    <?php if($field->required): ?>
                    <span style="color: #ff0000; font-size: 12px; vertical-align: top;">*</span>
                    <?php endif; ?>
                </label>
                <?php endif; ?>
                <div class="input-control <?=$field->fieldtypes?> full-size" data-role="select">
                    <select 
                        id="<?=$field->name?>" 
                        name="<?=$field->name?>"
                        <?php echo($field->required ? ' required="required"' : ''); ?>
                        >
                    <?php if($field->empty): ?>
                        <option value="" selected="selected"><?=$field->empty_text?></option>
                    <?php endif; ?>
                    <?php if(isset($field->value) && count($field->value)>0): ?>
                    <?php foreach($field->value as $value): ?>
                        <option value="<?=$value?>"><?=$value?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </select>
                </div>
            <?php endif; ?>
            <?php
                        break;
                   case 'file': 
            ?>
                <?php if($field->label_on): ?>
                <label for="<?=$field->name?>">
                    <?=$field->label?>
                    <?php if($field->required): ?>
                    <span style="color: #ff0000; font-size: 12px; vertical-align: top;">*</span>
                    <?php endif; ?>
                </label>
                <?php endif; ?>
                <div class="input-control file full-size" data-role="input">
                    <input 
                        id="<?=$field->name?>" 
                        type="file" 
                        name="<?=$field->name?>" 
                        <?php echo($field->required ? ' required="required"' : ''); ?> />
                    <button class="button" type="button"><span class="mif-folder"></span></button>
                </div>
            <?php
                        break;
                   case 'hidden': 
            ?>
                <input name="<?=$field->name?>" type="hidden" value="<?=$field->value?>" />
            <?php
                        break;
                   case 'text_block': 
            ?>
                <?php if(!empty($field->label)): ?>
                <h3><?=$field->label?></h3>
                <?php endif; ?>
                <?php if(!empty($field->value)): ?>
                <h5><?=$field->value?></h5>
                <?php endif; ?>
            <?php
                        break;
            ?>
            <?php endswitch; ?>
            </div>
            <?php endforeach; ?>  
            <?php endif; ?>  
            <div class="input-submit">
                <input class="button" type="submit" name="submit" value="Заказать" />
            </div>
            
            <input type="hidden" name="category_id" value="<?=$this->category_id?>" />
            <input type="hidden" name="menu_type_id" value="<?=$this->menu_type_id?>" />
            <input type="hidden" name="url_return" value="<?=$this->url_return?>" />
        </form>
    </div>
</div>