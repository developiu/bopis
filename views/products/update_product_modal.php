<form method="post" action="/products/update-product-modal">
    <?php if(isset($product['id'])):?>
    <input type="hidden" name="id" value="<?= $product['id']?>" />
    <?php endif ?>
    <div class="container">
        <div class="row">
            <div class="form-group col-10">
                <label for="name">Nome</label>
                <input type="text" name="name" class="form-control" value="<?= $product['name'] ?? ''?>" required="required">
            </div>
            <div class="form-group col-2">
                <label for="quantity">Quantità</label>
                <input type="number" name="quantity" class="form-control" value="<?= $product['quantity'] ?? ''?>" required="required">
            </div>
        </div>
        <div class="row">
            <div class="form-group col">
                <label for="sku">SKU</label>
                <input type="text" name="sku" class="form-control" value="<?= $product['sku'] ?? ''?>" required="required">
            </div>
            <div class="form-group col">
                <label for="asin">ASIN</label>
                <input type="text" name="asin" class="form-control" value="<?= $product['asin'] ?? '' ?>" required="required">
            </div>
        </div>
        <div class="row">
            <div class="form-group col">
                <label for="ean">EAN</label>
                <input type="text" name="ean" class="form-control" value="<?= $product['ean'] ?? '' ?>" required="required">
            </div>
        </div>

        <input type="submit" class="btn btn-primary" value="<?= $submit_label ?>" />
    </div>

</form>