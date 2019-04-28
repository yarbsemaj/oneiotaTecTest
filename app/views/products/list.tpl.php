<div class="product-listing">
	<h1>Products</h1>

	<?php foreach ($products as $product): ?>
		<div class="product">
		<?php echo $this->render('products/item', array('product' => $product)); ?>
		</div>
	<?php endforeach; ?>
</div>

<div class="clear"></div>