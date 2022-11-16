<?php

namespace XPort\Mapper;

use PDO;

class ProductMapper
{
    /** @var PDO */
    private $pdo;

    public function __construct()
    {
         $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME;
         $this->pdo = new PDO( $dsn,DBUSER, DBPASS);
    }

    /**
     * @return array|false
     */
    public function fetchAll()
    {
        $statement = $this->pdo->query('SELECT * FROM products ORDER BY id desc');
        if($statement === false) {
            return false;
        }

        return $statement->fetchAll();
    }

    /**
     * @param int $productId
     * @param string $field
     * @param string $newValue
     * @return bool|int false in caso di errore, altrimenti il numero di elementi modificati (0 o 1)
     */
    public function update($productId,$field,$newValue)
    {
        $affected = $this->pdo->exec('UPDATE products set ' . $field . '=' . $newValue . ' WHERE id=' . $productId);
        if($affected === false) {
            return false;
        }
        if($affected) {
            $this->pdo->exec('UPDATE products set synced=0 WHERE id=' . $productId);
            return 1;
        }

        return 0;
    }

    /**
     * Prende come argomento l'ean di un prodotto e ritorna null se non esiste un prodotto con tale ean; se invece
     * tale prodotto esiste ritorna l'array con i campi del prodotto.
     *
     * @param string $ean
     * @return array|null
     * @throws \RuntimeException in caso di errore nell'esecuzione della query
     * @throws \LogicException se è presente più di un prodotto con l'EAN specificato
     */
    public function getByEan($ean)
    {
        $statement = $this->pdo->prepare("SELECT * FROM products WHERE ean=?");
        if($statement == false) {
            throw new \RuntimeException("Errore di database");
        }
        $statement->execute([$ean]);
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(empty($products)) {
            return null;
        }

        if(count($products) > 1) {
            throw new \LogicException("Esiste più di un prodotto con l'EAN specificato");
        }

        return $products[0];
    }

    /**
     * Crea un prodotto con i campi specificati dall'array
     *
     * @param array $product
     * @return void
     * @throws \RuntimeException in caso di errore nell'esecuzione della query
     */
    public function createProduct($product)
    {
        $statement = $this->pdo->prepare("INSERT INTO products(name,quantity,sku,asin,ean) VALUES(?,?,?,?,?)");
        if($statement == false) {
            throw new \RuntimeException("Errore di database");
        }
        $statement->execute([$product['name'],$product['quantity'],$product['sku'],$product['asin'],$product['ean'] ]);
    }

    /**
     * Aggiorna il prodotto di dato id con i campi specificati dall'array
     *
     * @param array $product
     * @param int $id
     * @return int True se il prodotto esisteva ed è stato modificato, false altrimenti
     * @throws \RuntimeException in caso di errore nell'esecuzione della query
     */
    public function updateProduct($product, $id)
    {
        $statement = $this->pdo->prepare("UPDATE products SET name=?, quantity=?,sku=?, asin=?, ean=? WHERE id=?");
        if($statement === false) {
            throw new \RuntimeException("Errore di database");
        }
        $statement->execute([$product['name'],$product['quantity'],$product['sku'],$product['asin'],$product['ean'], $id ]);
        if($statement->rowCount() > 0) {
            $this->update($id, 'synced',0);
        }


        return $statement->rowCount()>0;
    }

    /**
     * Elimina il prodotto di dato id
     *
     * @param int $id
     * @return int True se il prodotto esisteva ed è stato eliminato, false altrimenti
     * @throws \RuntimeException in caso di errore nell'esecuzione della query
     */
    public function deleteProduct($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM products WHERE id=?");
        if($statement === false) {
            throw new \RuntimeException("Errore di database");
        }
        $statement->execute([ $id ]);

        return $statement->rowCount()>0;
    }

}