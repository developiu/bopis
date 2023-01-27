<?php

use XPort\Bopis\SupplySource\SupplySourceModel;

$this->layout('layouts/layout');

/** @var SupplySourceModel $store (importata dal controller) */
/** @var bool $just_saved (importato dal controller) */

$address = $store->getAddress()->toArray();
$isAddressDefined = boolval(array_filter($address));
?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Registrazione Store</h4>
        <p class="card-description">
            Dati di registrazione
        </p>
        <form id="store-form" method="post" action="/store/save">
            <input type="hidden" name="supplySourceId" value="<?= $store->getSupplySourceId() ?? '' ?>">
            <input type="hidden" name="supplySourceCode" value="<?= $store->getSupplySourceCode() ?? '' ?>">
            <div class="form-group">
                <div class="row">
                    <div class="col-12">
                        <label for="name">Store Alias</label>
                        <input type="text"  name="alias"  value="<?= $store->getAlias() ?>" required
                               class="form-control" id="alias" placeholder="Usa il formato Store name - address line 1 - post code">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-6">
                      <label for="addressLine1">Indirizzo (prima riga)</label>
                      <input type="text"  name="address[addressLine1]"  value="<?= $address['addressLine1'] ?>" required
                             class="form-control" id="addressLine1" <?= $isAddressDefined ? "readonly" : ""?>>
                    </div>
                    <div class="col-6">
                        <label for="addressLine2">Indirizzo (seconda riga)</label>
                        <input type="text"  name="address[addressLine2]"  value="<?= $address['addressLine2'] ?>"
                               class="form-control" id="addressLine2" <?= $isAddressDefined ? "readonly" : ""?>>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <label for="country-code">Nazione (codice ISO)</label>
                        <input type="text"  name="address[countryCode]"  value="<?= $address['countryCode'] ?>" required
                               class="form-control" id="country-code" <?= $isAddressDefined ? "readonly" : ""?>>
                    </div>
                    <div class="col-2">
                        <label for="city">Città</label>
                        <input type="text"  name="address[city]"  value="<?= $address['city'] ?>" required
                               class="form-control" id="city" <?= $isAddressDefined ? "readonly" : ""?>>
                    </div>
                    <div class="col-2">
                        <label for="postal-code">Codice Postale</label>
                        <input type="text"  name="address[postalCode]"  value="<?= $address['postalCode'] ?>" required
                               class="form-control" id="postal-code <?= $isAddressDefined ? "readonly" : ""?>">
                    </div>
                    <div class="col-2">
                        <label for="timezone">Fuso orario</label>
                        <select name="timezone" class="form-control xport-select">
                            <?php foreach (DateTimeZone::listIdentifiers(DateTimeZone::EUROPE) as $timezone):?>
                                <option value="<?= $timezone ?>" <?php if($store->getTimezone()==$timezone): ?>selected<?php endif ?>><?= $timezone ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="handling_time">Tempo di trattamento (ore)</label>
                        <input type="text"  name="handlingTime"  value="<?= $store->getHandlingTime() ?>" pattern="[0-9-]+" required
                               class="form-control" id="handling_time" title="il valore inserito non è un numero intero">
                    </div>
                    <div class="col-2">
                        <label for="inventory_hold_period">Per. conservazione (giorni)</label>
                        <input type="text"  name="inventoryHoldPeriod"  value="<?= $store->getInventoryHoldPeriod() ?>" required
                               class="form-control" id="inventory_hold_period" pattern="[0-9-]+"
                               title="il valore inserito non è un numero intero">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="email">Email</label>
                        <input type="email"  name="email"  value="<?= $store->getEmail() ?>" required
                               class="form-control" id="email">
                    </div>
                    <div class="col-6">
                        <label for="phone">Telefono</label>
                        <input type="text"  name="phone"  value="<?= $store->getPhone() ?>"
                               class="form-control" id="phone" pattern="[0-9-]+" required
                               title="il valore inserito non è un numero di telefono valido">
                    </div>
                </div>

                <p class="card-description my-4">
                    Orario dello shop
                </p>

                    <?php foreach([
                            'monday' => 'Lunedì', 'tuesday' => 'Martedì', 'wednesday' => 'Mercoledì',
                            'thursday' => 'Giovedì','friday' => 'Venerdì', 'saturday' => 'Sabato', 'sunday' => ' Domenica'] as $dayCode=>$dayLabel):?>
                    <div class="row">
                        <div class="col-3">
                            <?= $dayLabel ?>
                        </div>
                        <div class="col-3">
                            <input type="time"  name="operatingHours[<?=$dayCode ?>][startTime]"  value="<?= $store->getOperatingHours()->getStartTime($dayCode)?>" class="form-control day_start" />
                        </div>
                        <div class="col-3">
                            <input type="time"  name="operatingHours[<?=$dayCode ?>][endTime]"  value="<?= $store->getOperatingHours()->getEndTime($dayCode)?>" class="form-control day_end" />
                        </div>
                        <div class="col-3 d-flex align-items-center">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="<?=$dayCode ?>_enabled" class="custom-control-input day-switcher" id="<?= $dayCode ?>_switch" checked>
                                <label class="custom-control-label" for="<?= $dayCode ?>_switch"></label>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

            <button type="submit" class="btn btn-primary mt-3">Registra</button>
        </form>
    </div>
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded',function() {
        function update_after_day_switch(node) {
            if (jQuery(node).is(":checked")) {
                jQuery(node).closest(".row").find("[type=time]").each(function () {
                    jQuery(this).removeAttr('disabled');
                });
            } else {
                jQuery(node).closest(".row").find("[type=time]").each(function () {
                    jQuery(this).val("");
                    jQuery(this).attr('disabled', 'disabled');
                });
            }
        }

        jQuery(".day-switcher").click(function () {
            update_after_day_switch(jQuery(this));
        });

        jQuery(".day-switcher").each(function() {
            if(jQuery(this).closest(".row").find(".day_start").val()=="" && jQuery(this).closest(".row").find(".day_end").val()=="") {
                jQuery(this).prop("checked",false);
                update_after_day_switch(jQuery(this));
            }
        });


        function validateSwitcher(node) {
            let switcherNode = jQuery(node).closest(".row").find('.day-switcher');
            switcherNode = switcherNode.get(0);
            switcherNode.setCustomValidity("");
            if(!jQuery(switcherNode).is(":checked")) {
                return;
            }

            if(jQuery(switcherNode).closest(".row").find(".day_start").val()=="" || jQuery(switcherNode).closest(".row").find(".day_end").val()=="") {
                switcherNode.setCustomValidity("Specificare l'orario di inizio e fine o disabilitare il giorno");
                return;
            }


            let dateStart = new Date("1970-01-01T" + jQuery(switcherNode).closest(".row").find(".day_start").val());
            let dateEnd = new Date("1970-01-01T" + jQuery(switcherNode).closest(".row").find(".day_end").val());

            if(dateStart.getTime()>=dateEnd.getTime()) {
                switcherNode.setCustomValidity("L'orario di fine deve essere successivo a quello di inizio");
                return;
            }

        }

        jQuery(".day-switcher").each(function() {
            this.addEventListener("click", function() { validateSwitcher(this); } );
        });
        jQuery(".day_start, .day_end").each(function() {
            this.addEventListener("change", function() { validateSwitcher(this); });
        });

        <?php if($just_saved):?>
            jQuery.notify("Lo store è stato aggiornato", {type: "success"});
        <?php endif ?>

    });
</script>

