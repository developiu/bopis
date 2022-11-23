<?php

$this->layout('layouts/layout');
?>
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Vendite</h4>
        <p class="card-description">
            Effettua lo scan di un prodotto per attestarne la vendita.<br>
            <strong>c</strong>: cancella l'elenco dei prodotti scansionati<br>
            <strong>INVIO</strong>: registra i prodotti elencati come venduta aggiornando le rispettive quantità
        </p>
        <div style="min-height: 500px">
            <table id="product-list" class="table table-striped">
                <tr>
                    <th>EAN</th>
                    <th>Nome</th>
                </tr>
                <tr class="row-for-empty-message">
                    <td colspan="2">Nessun prodotto scannerizzato</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded',function() {
        function scanLoop() {
            get_from_barcode_scanner("#main-barcode-overlay","c","Enter")
                .then((ean) => {
                    jQuery.ajax({
                        url: '/products/get-by-ean',
                        method: 'GET',
                        data: {ean: ean},
                        success: function(data) {
                            if(data.status == 'error') {
                                jQuery.notify(data.message,{ type: "danger"});
                            }
                            else {
                                jQuery(".row-for-empty-message").hide();
                                let newrow = jQuery("<tr class='product'><td class='pean'></td><td class='pname'></td></tr>");
                                newrow.find('.pean').html(ean);
                                newrow.find('.pname').html(data.product.name);
                                jQuery("#product-list").append(newrow);
                            }
                            scanLoop();
                        }
                    });
                })
                .catch( (key ) => {
                    if(key == 'Enter') {
                        let eans = jQuery.makeArray(jQuery("#product-list .pean").map(function() { return jQuery(this).html();}));
                        if(eans.length>0) {
                            jQuery.ajax({
                                url: '/products/reduce-quantity-by-ean',
                                data: { ean: eans },
                                method: 'POST',
                                dataType: 'json',
                                success: function(data) {
                                    if(data.status == 'error') {
                                        jQuery.notify(data.message,{ type: "danger"});
                                    }
                                    else if(data.status == 'warning') {
                                        jQuery.notify(data.message,{ type: "warning", delay: 1000*60*60, close: true});
                                    }
                                    else if(data.status == 'success') {
                                        jQuery.notify(data.message,{ type: "success"});
                                    }
                                }
                            });
                        }
                    }
                    jQuery("#product-list tr.product").remove();
                    jQuery(".row-for-empty-message").show();
                    scanLoop();
                });
        };

        scanLoop();
    });




</script>

