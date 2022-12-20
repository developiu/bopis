<?php

$this->layout('layouts/layout');

/** @var array $store (importata dal controller) */
?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Registrazione Store</h4>
        <p class="card-description">
            Registrazione di uno store su Amazon
        </p>
        <form method="post">
            <div class="form-group">
                <div class="row">
                    <div class="col-8">
                        <label for="name">Nome dello store</label>
                        <input type="text"  name="alias"  value="" required
                               class="form-control" id="alias" placeholder="nome dello store">
                    </div>
                    <div class="col-4">
                        <label for="supply-source-code">Codice del magazzino</label>
                        <input type="text"  name="supply_source_code"  value=""
                               class="form-control" id="supply-source-code">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-8">
                      <label for="addressLine1">Indirizzo (prima riga)</label>
                      <input type="text"  name="addressLine1"  value="" required
                             class="form-control" id="addressLine1">
                    </div>
                    <div class="col-2">
                        <label for="city">Città</label>
                        <input type="text"  name="city"  value="" required
                               class="form-control" id="city">
                    </div>
                    <div class="col-2">
                        <label for="county">Circoscrizione</label>
                        <input type="text"  name="county"  value=""
                               class="form-control" id="county">
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <label for="addressLine2">Indirizzo (seconda riga)</label>
                        <input type="text"  name="addressLine2"  value=""
                               class="form-control" id="addressLine2">
                    </div>
                    <div class="col-2">
                        <label for="district">Distretto</label>
                        <input type="text"  name="district"  value=""
                               class="form-control" id="district">
                    </div>
                    <div class="col-2">
                        <label for="state-or-region">Provincia o Regione</label>
                        <input type="text"  name="stateOrRegion"  value="" required
                               class="form-control" id="state-or-region">
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <label for="addressLine3">Indirizzo (terza riga)</label>
                        <input type="text"  name="addressLine3"  value=""
                               class="form-control" id="addressLine3">
                    </div>
                    <div class="col-2">
                        <label for="postal-code">Codice Postale</label>
                        <input type="text"  name="postalCode"  value="" required
                               class="form-control" id="postal-code">
                    </div>
                    <div class="col-2">
                        <label for="country-code">Nazione (codice ISO)</label>
                        <input type="text"  name="countryCode"  value="" required
                               class="form-control" id="country-code">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="email">Email</label>
                        <input type="email"  name="email"  value=""
                               class="form-control" id="email">
                    </div>
                    <div class="col-6">
                        <label for="phone">Telefono</label>
                        <input type="text"  name="phone"  value=""
                               class="form-control" id="phone" pattern="[0-9-]+"
                               title="il valore inserito non è un numero di telefono valido">
                    </div>
                </div>
            <button type="submit" class="btn btn-primary mt-3">Registra</button>
        </form>
    </div>
</div>

