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
                <div class="row">
                    <div class="col-6">
                        <label for="email">Email</label>
                        <input type="email"  name="email"  value="<?= $this->e($store['email']) ?>"
                               class="form-control" id="email">
                    </div>
                    <div class="col-6">
                        <label for="phone">Telefono</label>
                        <input type="text"  name="phone"  value="<?= $this->e($store['phone']) ?>"
                               class="form-control" id="phone" pattern="[0-9-]+"
                               title="il valore inserito non è un numero di telefono valido">
                    </div>
                </div>
                <fieldset>
                    <legend>Orari</legend>
                    <div class="row">
                        <div class="col-3">
                            <label for="monday_start">Lunedì da</label>
                            <input type="time"  name="monday_start"  value="<?= $this->e($store['monday_start']) ?>"
                                   class="form-control" id="monday_start">
                        </div>
                        <div class="col-3">
                            <label for="monday_end">Lunedì a</label>
                            <input type="time"  name="monday_end"  value="<?= $this->e($store['monday_end']) ?>"
                                   class="form-control" id="monday_end">
                        </div>
                        <div class="col-3">
                            <label for="tuesday_start">Martedì da</label>
                            <input type="time"  name="tuesday_start"  value="<?= $this->e($store['tuesday_start']) ?>"
                                   class="form-control" id="monday_start">
                        </div>
                        <div class="col-3">
                            <label for="tuesday_end">Martedì a</label>
                            <input type="time"  name="tuesday_end"  value="<?= $this->e($store['tuesday_end']) ?>"
                                   class="form-control" id="tuesday_end">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label for="wednesday_start">Mercoledì da</label>
                            <input type="time"  name="wednesday_start"  value="<?= $this->e($store['wednesday_start']) ?>"
                                   class="form-control" id="wednesday_start">
                        </div>
                        <div class="col-3">
                            <label for="wednesday_end">Mercoledì a</label>
                            <input type="time"  name="wednesday_end"  value="<?= $this->e($store['wednesday_end']) ?>"
                                   class="form-control" id="wednesday_end">
                        </div>
                        <div class="col-3">
                            <label for="thursday_start">Giovedì da</label>
                            <input type="time"  name="thursday_start"  value="<?= $this->e($store['thursday_start']) ?>"
                                   class="form-control" id=thursday_start">
                        </div>
                        <div class="col-3">
                            <label for="thursday_end">Giovedì a</label>
                            <input type="time"  name="thursday_end"  value="<?= $this->e($store['thursday_end']) ?>"
                                   class="form-control" id="thursday_end">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label for="friday_start">Venerdì da</label>
                            <input type="time"  name="friday_start"  value="<?= $this->e($store['friday_start']) ?>"
                                   class="form-control" id="friday_start">
                        </div>
                        <div class="col-3">
                            <label for="friday_end">Venerdì a</label>
                            <input type="time"  name="friday_end"  value="<?= $this->e($store['friday_end']) ?>"
                                   class="form-control" id="friday_end">
                        </div>
                        <div class="col-3">
                            <label for="saturday_start">Sabato da</label>
                            <input type="time"  name="saturday_start"  value="<?= $this->e($store['saturday_start']) ?>"
                                   class="form-control" id=saturday_start">
                        </div>
                        <div class="col-3">
                            <label for="saturday_end">Sabato a</label>
                            <input type="time"  name="saturday_end"  value="<?= $this->e($store['saturday_end']) ?>"
                                   class="form-control" id="saturday_end">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label for="sunday_start">Domenica da</label>
                            <input type="time"  name="sunday_start"  value="<?= $this->e($store['sunday_start']) ?>"
                                   class="form-control" id="sunday_start">
                        </div>
                        <div class="col-3">
                            <label for="sunday_end">Domenica a</label>
                            <input type="time"  name="sunday_end"  value="<?= $this->e($store['sunday_end']) ?>"
                                   class="form-control" id="sunday_end">
                        </div>
                    </div>
                </fieldset>
            <button type="submit" class="btn btn-primary mr-2">Salva</button>
        </form>
    </div>
</div>

