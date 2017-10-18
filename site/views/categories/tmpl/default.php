<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
?>
<div class="jshopmenu-categories">
<?php if ($this->params->get('show_page_heading')) : ?>
    <header class="categories-header clearfix">
        <h1 class="categories-title">
            <?php echo $this->escape($this->params->get('page_heading')); ?>
        </h1>
    </header>
<?php endif; ?>
<section class="categories-content">
    <div class="category-list">
    <div>
    </div>
        <ul class="category-nav">
            <?php foreach($this->categories as $category): ?>
                <li>
                    <a href="<?php echo JRoute::_ (JShopMenuHelper::getCategoryRoute((int)$category->category_id,$category->slug) , false); ?>">
                        <span class="image" style="background-image: url(<?=$category->image?>);"></span>
                        <span><?=$category->name?></span>
                    </a>
                    <?php if($category->params->get('show_category_short_description')):?>
                    <p><?=$category->short_description?></p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
</div>