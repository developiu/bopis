<?php

use XPort\StringUtils;

$this->layout('layouts/layout');

/** @var array $prodotti (importata dal controller) */
?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Prodotti</h4>

        <form class="form-inline flex-row-reverse">
            <button type="button" class="btn btn-primary update-products-button m-1 disabled" title="non ancora implementato">Aggiorna quantità</button>
            <button type="button" class="btn btn-primary update-create-product m-1">Aggiorna/Crea prodotto</button>
        </form>
        <div class="table-responsive mt-3">
            <table id="product-table" class="table table-striped">
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

<!-- Modal usata per creare/modificare i prodotti -->
<div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        jQuery("#product-table").dataTable({
            language: {
                url: '/js/datatables_it_plugin.json'
            }
        });

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
                        jQuery.notify("Errore durante l'aggiornamento della quantità",{type: 'danger'});
                        return;
                    }
                    if(data.updated == 1) {
                        jQuery(btn).closest('tr').find('.sync-label').html('<label class="badge badge-danger">Da sincronizzare</label>');
                        jQuery.notify("Quantità associata al prodotto aggiornata",{type: 'success'});
                    }
                }
            });
        });

        jQuery(".update-create-product").click(function() {
            get_from_barcode_scanner("#main-barcode-overlay")
                .then((ean) => {
                    jQuery.ajax({
                        url: '/products/update-product-modal',
                        data: { ean: ean },
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            jQuery("#product-modal").on("show.bs.modal", function (e) {
                                jQuery("#product-modal .modal-title").html(data.title);
                                jQuery("#product-modal .modal-body").html(data.form);
                            }).modal('show');
                        }
                    });
                })
                .catch( () => {
                    jQuery.notify("Per favore inserisci un barcode da scanner",{ type: "danger"});
                });
        });
    });

</script>
