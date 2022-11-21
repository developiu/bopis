<?php

use XPort\StringUtils;

$this->layout('layouts/layout');

/** @var array $ordini (importata dal controller) */
?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Ordini</h4>

        <form class="form-inline flex-row-reverse">
<!--            <button type="button" class="btn btn-primary update-products-button m-1 disabled" title="non ancora implementato">Aggiorna quantità</button>-->
<!--            <button type="button" class="btn btn-primary update-create-product m-1">Aggiorna/Crea prodotto</button>-->
<!--            <div class="form-group">-->
<!--                <input type="file" name="product-csv-file" class="file-upload-default">-->
<!--                <div class="input-group">-->
<!--                    <input type="text" class="form-control file-upload-info" placeholder="Importa prodotti">-->
<!--                    <span class="input-group-append">-->
<!--                          <button class="file-upload-browse btn btn-primary" type="button">Carica</button>-->
<!--                        </span>-->
<!--                </div>-->
<!--            </div>-->
        </form>
        <div class="table-responsive mt-3">
            <table id="order-table" class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Seleziona</th>
                        <th>Id</th>
                        <th>Creato il</th>
                        <th>Utente</th>
                        <th>Importo</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($ordini as $ordine): ?>
                    <?php $dateObj = new DateTime($ordine['creation_date']) ?>
                    <tr>
                        <td class="py-1 text-center">
                            <input type="checkbox" value="1">
                        </td>
                        <td><?= $ordine['id']?></td>
                        <td><?= $dateObj->format('d/m/Y') ?></td>
                        <td><?= $ordine['user_name'] ?></td>
                        <td><?= $ordine['amount']?></td>
                        <td><?= $ordine['status']?></td>
                        <td class="text-center">
                            <a href="/orders/delete-order?id=<?=$ordine['id']?>" target="_blank"><i class="typcn typcn-trash"></i></a>
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
        jQuery("#order-table").dataTable({
            language: {
                url: '/js/datatables_it_plugin.json'
            },
            stateSave: true
        });
    });

</script>
