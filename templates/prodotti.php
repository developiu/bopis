<?php

use XPort\Mapper\ProductMapper;
use XPort\StringUtils;

$mapper = new ProductMapper();
$prodotti = $mapper->fetchAll();


$this->layout('layouts/layout');
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
                    <tr>
                        <td class="py-1"><?= $prodotto['id'] ?></td>
                        <td><?= StringUtils::truncate($prodotto['name'],30) ?></td>
                        <td><?= $prodotto['ean']?></td>
                        <td><?= $prodotto['asin']?></td>
                        <td><?= $prodotto['sku']?></td>
                        <td><input type="number" value="<?= $prodotto['quantity']?>" style="max-width: 5em" class="update-quantity-selector" /></td>
                        <td><?php if($prodotto['synced']):?>
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
            console.log(jQuery(this).val());
        });
    });
</script>
