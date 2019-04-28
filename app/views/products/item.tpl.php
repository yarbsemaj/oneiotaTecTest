<h3><?php echo $product->name; ?></h3>

<img src="<?php echo $product->mainImage; ?>" width="250px" />

<div class="product-details">
	<h4>&pound;<?php echo $product->price->amount; ?></h4>

	<button data-sku="<?php echo $product->SKU ?>" >Quick View</button>
</div>