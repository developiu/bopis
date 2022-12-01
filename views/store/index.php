<?php

$this->layout('layouts/layout');

/** @var array $store (importata dal controller) */
?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Registrazione Store</h4>
        <p class="card-description">
            Registrazione e modifica dei dati dello store
        </p>
        <form method="post">
            <div class="form-group">
                <div class="row">
                    <div class="col-8">
                        <label for="name">Nome dello store</label>
                        <input type="text"  name="alias"  value="<?= $this->e($store['alias']) ?>"
                               class="form-control" id="alias" placeholder="nome dello store">
                    </div>
                    <div class="col-4">
                        <label for="supply-source-code">Codice del magazzino</label>
                        <input type="text"  name="supply_source_code"  value="<?= $this->e($store['supply_source_code']) ?>"
                               class="form-control" id="supply-source-code">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-8">
                      <label for="addressline1">Indirizzo (prima riga)</label>
                      <input type="text"  name="addressline1"  value="<?= $this->e($store['addressline1']) ?>"
                             class="form-control" id="addressline1">
                    </div>
                    <div class="col-2">
                        <label for="city">Città</label>
                        <input type="text"  name="city"  value="<?= $this->e($store['city']) ?>"
                               class="form-control" id="city">
                    </div>
                    <div class="col-2">
                        <label for="county">Circoscrizione</label>
                        <input type="text"  name="county"  value="<?= $this->e($store['county']) ?>"
                               class="form-control" id="county">
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <label for="addressline2">Indirizzo (seconda riga)</label>
                        <input type="text"  name="addressline2"  value="<?= $this->e($store['addressline2']) ?>"
                               class="form-control" id="addressline2">
                    </div>
                    <div class="col-2">
                        <label for="district">Distretto</label>
                        <input type="text"  name="district"  value="<?= $this->e($store['district']) ?>"
                               class="form-control" id="district">
                    </div>
                    <div class="col-2">
                        <label for="state-or-region">Provincia o Regione</label>
                        <input type="text"  name="state_or_region"  value="<?= $this->e($store['state_or_region']) ?>"
                               class="form-control" id="state-or-region">
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <label for="addressline3">Indirizzo (terza riga)</label>
                        <input type="text"  name="addressline3"  value="<?= $this->e($store['addressline3']) ?>"
                               class="form-control" id="addressline3">
                    </div>
                    <div class="col-2">
                        <label for="postal-code">Postal Code</label>
                        <input type="text"  name="postal_code"  value="<?= $this->e($store['postal_code']) ?>"
                               class="form-control" id="postal-code">
                    </div>
                    <div class="col-2">
                        <label for="country-code">Nazione (codice ISO)</label>
                        <input type="text"  name="country_code"  value="<?= $this->e($store['country_code']) ?>"
                               class="form-control" id="country-code">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mr-2">Salva</button>
        </form>
    </div>
</div>

