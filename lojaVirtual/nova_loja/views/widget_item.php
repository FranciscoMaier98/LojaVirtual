<?php foreach($list as $widget_item):?>
<div class="widget_item">
	<a href="">
		<div class="widget_info">
			<div class="widget_productname"><?php echo $widget_item['name']; ?></div>
			<div class="widget_price"><span><?php echo number_format( $widget_item['price_from'], 2, ',', '.')?></span> <?php echo number_format( $widget_item['price'], 2, ',', '.')?></div>
		</div>
		<div class="widget_photo">
			<img src="<?php echo BASE_URL;?>media/products/<?php echo $widget_item['images'][0]['url'] ?>" >
		</div>
	</a>
</div>
<div style="clear:both"></div>
<?php endforeach; ?>

