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
                <label for="store-name">Nome dello store</label>
                <input type="text"  name="store_name"  value="<?= $this->e($store['name']) ?>"
                       class="form-control" id="store-name" placeholder="nome dello store">
            </div>
            <div class="form-group">
              <label for="store-address">Textarea</label>
              <textarea name="store_address" class="form-control" id="store-address" rows="4"><?= $this->e($store['address']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary mr-2">Salva</button>
        </form>
    </div>
</div>

