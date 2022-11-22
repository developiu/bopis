<?php

use XPort\StringUtils;

$this->layout('layouts/layout');

/** @var array $ordini (importata dal controller) */
?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Ordini</h4>

        <form class="form-inline flex-row-reverse">
            <button type="button" class="btn btn-primary m-1 disabled" title="non ancora implementato">Importa ordini</button>
            <div class="input-group">
                <select>
                    <option value="NEW">Nuovo</option>
                    <option value="CANCELLED">Cancellato</option>
                    <option value="READY_FOR_PICKUP">Pronto all'aquisto</option>
                    <option value="PICKED_UP">Acquistato</option>
                    <option value="REFOUNDED">Rimborsato</option>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-primary update-status-button" type="button">Notifica amazon</button>
                </div>
            </div>
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
                    <tr data-order-id="<?= $ordine['id'] ?>">
                        <td class="py-1 text-center">
                            <input class="selection-checkbox" type="checkbox" value="1">
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
            columns: [
                {name: "selection", orderable: false},
                {name: "id", orderable: true},
                {name: "creation_date", orderable: false},
                {name: "username", orderable: true},
                {name: "amount", orderable: true, type: "num"},
                {name: "status", orderable: true},
                {name: "actions", orderable: false}
            ],
            language: {
                url: '/js/datatables_it_plugin.json'
            },
            stateSave: true
        });

        jQuery(".update-status-button").click(function() {
            let newStatus = jQuery(this).closest(".input-group").find("select").val();
            let orderIds = jQuery("#order-table")
                .find(".selection-checkbox:checked").closest("tr")
                .map(function() { return jQuery(this).data('order-id');});

            jQuery.ajax({
                url: '/orders/update-status',
                data: { status: newStatus , id: jQuery.makeArray(orderIds) },
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if(data.status=='success') {
                        window.location.reload();
                    }
                    else {
                        jQuery.notify(data.message,{type: 'danger'});
                    }
                }
            });
        });
    });

</script>
