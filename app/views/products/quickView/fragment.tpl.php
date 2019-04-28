<h3><?php echo $product->name; ?></h3>

<img src="<?php echo $product->mainImage; ?>" width="250px"/>

<div class="product-details">
    <h4>&pound;<?php echo $product->price->amount; ?></h4>
    <b><?php echo $product->stockStatus; ?></b>
    <br>
    <h5>Sizes Available</h5>
    <div class="sizes">
        <?php foreach ($product->sizes as $size): ?>
            <div class="size">
                <?php echo $size; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <p><?php echo $product->description; ?></p>
</div>