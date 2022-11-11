<?php

use XPort\StringUtils;

$this->layout('layouts/layout');

/** @var array $prodotti (importata dal controller) */
?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Prodotti</h4>

        <form class="form-inline flex-row-reverse">
            <button type="button" class="btn btn-primary update-products-button">Aggiorna quantità</button>
        </form>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>EAN</th>
                        <th>ASIN</th>
                        <th>SKU</th>
                        <th>Quantità</th>
                        <th>Sincronizzato</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($prodotti as $prodotto): ?>
                    <tr data-product-id="<?= $prodotto['id'] ?>">
                        <td class="py-1"><?= $prodotto['id'] ?></td>
                        <td><?= StringUtils::truncate($prodotto['name'],30) ?></td>
                        <td><?= $prodotto['ean']?></td>
                        <td><?= $prodotto['asin']?></td>
                        <td><?= $prodotto['sku']?></td>
                        <td><input type="number" value="<?= $prodotto['quantity']?>" style="max-width: 5em" class="update-quantity-selector" /></td>
                        <td class="sync-label"><?php if($prodotto['synced']):?>
                                <label class="badge badge-success">Sincronizzato</label>
                            <?php else: ?>
                                <label class="badge badge-danger">Da sincronizzare</label>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        jQuery(".update-quantity-selector").change(function() {
            let btn = jQuery(this);
            let newQuantity = jQuery(this).val();
            let productId = jQuery(this).closest('tr').data('product-id');
            jQuery.ajax({
                url: '/products/update-quantity',
                method: 'GET',
                dataType: 'json',
                data: { qty: newQuantity, pid: productId },
                success: function(data) {
                    if(data.status == false) {
                        return;
                    }
                    if(data.updated == 1) {
                        jQuery(btn).closest('tr').find('.sync-label').html('<label class="badge badge-danger">Da sincronizzare</label>');
                    }
                }
            });
        });
    });
</script>
