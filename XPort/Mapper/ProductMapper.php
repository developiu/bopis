<?php

namespace XPort\Mapper;

class ProductMapper
{
    /** @var \PDO */
    private $pdo;

    public function __construct()
    {
         $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME;
         $this->pdo = new \PDO( $dsn,DBUSER, DBPASS);
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

}