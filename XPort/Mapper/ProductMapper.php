<?php

namespace XPort\Mapper;

use PDO;
use RuntimeException;

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
     * @param string $ean l'ean del prodotto di cui si vuole ridurre la quantità
     * @return bool|int false se nessun prodotto è stato aggiornato, altrimenti la quantità aggiornata del prodotto. Tale quantità
     * può essere negativa (ad esempio se la quantità originale era 0, diventa -1)
     * @throws RuntimeException In caso di errore nell'esecuzione della query
     */
    public function decreaseQuantity($ean)
    {
        $statement = $this->pdo->prepare("UPDATE products SET quantity=quantity-1, synced=0  WHERE ean=?");
        if($statement == false) {
            throw new RuntimeException("Errore di database");
        }
        $statement->execute([$ean]);
        if($statement->rowCount()==0) {
            return false;
        }

        $product = $this->getByEan($ean);

        return $product['quantity'];
    }

    /**
     * Prende come argomento l'ean di un prodotto e ritorna null se non esiste un prodotto con tale ean; se invece
     * tale prodotto esiste ritorna l'array con i campi del prodotto.
     *
     * @param string $ean
     * @return array|null
     * @throws RuntimeException in caso di errore nell'esecuzione della query
     * @throws \LogicException se è presente più di un prodotto con l'EAN specificato
     */
    public function getByEan($ean)
    {
        $statement = $this->pdo->prepare("SELECT * FROM products WHERE ean=?");
        if($statement == false) {
            throw new RuntimeException("Errore di database");
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
     * Ritorna i prodotti il cui campo $field vale $value. Se $value è un array ritorna i prodotti il cui campo $field
     * è uno dei valori dell'array.
     *
     * @param string $field
     * @param string|array $value
     * @return array
     * @throws RuntimeException In caso di errore nell'esecuzione della query
     */
    public function getProductsByField($field,$value)
    {
        if(is_array($value)) {
            $fieldCondition = count($value)>0 ? " $field IN (" . implode(',',array_fill(0,count($value),'?')) . ")" : "1=0";
            $query = "SELECT * FROM products WHERE $fieldCondition";
        }
        else {
            $query = "SELECT * FROM products WHERE $field=?";
        }

        //$query = is_array($value) ? "SELECT * FROM products WHERE $field IN (" . implode(", ",$value) .  ")" : "SELECT * FROM products WHERE $field=?";

        $statement = $this->pdo->prepare($query);
        if($statement == false) {
            throw new RuntimeException("Errore di database");
        }
        $statement->execute($value);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un prodotto con i campi specificati dall'array
     *
     * @param array $product
     * @return void
     * @throws RuntimeException in caso di errore nell'esecuzione della query
     */
    public function createProduct($product)
    {
        $statement = $this->pdo->prepare("INSERT INTO products(name,quantity,sku,asin,ean) VALUES(?,?,?,?,?)");
        if($statement == false) {
            throw new RuntimeException("Errore di database");
        }
        $statement->execute([$product['name'],$product['quantity'],$product['sku'],$product['asin'],$product['ean'] ]);
    }

    /**
     * Aggiorna il prodotto di dato id con i campi specificati dall'array
     *
     * @param array $product
     * @param int $id
     * @return int True se il prodotto esisteva ed è stato modificato, false altrimenti
     * @throws RuntimeException in caso di errore nell'esecuzione della query
     */
    public function updateProduct($product, $id)
    {
        $statement = $this->pdo->prepare("UPDATE products SET name=?, quantity=?,sku=?, asin=?, ean=? WHERE id=?");
        if($statement === false) {
            throw new RuntimeException("Errore di database");
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
     * @return bool True se il prodotto esisteva ed è stato eliminato, false altrimenti
     * @throws RuntimeException in caso di errore nell'esecuzione della query
     */
    public function deleteProduct($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM products WHERE id=?");
        if($statement === false) {
            throw new RuntimeException("Errore di database");
        }
        $statement->execute([ $id ]);

        return $statement->rowCount()>0;
    }

    public function getAdapter(): PDO
    {
        return $this->pdo;
    }
}