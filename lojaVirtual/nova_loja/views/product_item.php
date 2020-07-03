<div class="product_item">
    <a href="">
        <div class="product_tags">
            <?php if($sale == 1):?>   
                <div class="product_tag product_tag_red"><?php $this->lang->get('SALE') ?></div>
            <?php endif; ?>
            <?php if($bestseller == 1): ?>
                <div class="product_tag product_tag_green"><?php $this->lang->get('BESTSELLER') ?></div>
            <?php endif;?>
            <?php if($new_product == 1):?>
                <div class="product_tag product_tag_blue"><?php $this->lang->get('NEW') ?></div>
            <?php endif;?>
        </div>
        <div class="product_image">
            <img src="<?php echo BASE_URL;?>media/products/<?php echo $images[0]['url'] ?>" width="100%">
        </div>
        <div class="product_name"><?php echo $name ?></div>
        <div class="product_brand"><?php echo $brand_name?></div>

        <?php if($price_from != 0): ?>
            <div class="product_price_from">R$ <?php echo $price_from?> </div>
        <?php endif; ?>
        
        <div class="product_price">R$ <?php echo $price?></div>
        <div style="clear:both"></div> <!-- Usado para 'espalhar' o hover. Não sei pqê funciona -->
    </a>
</div>