<?php

namespace XPort\Mapper;

use PDO;
use RuntimeException;

class OrderMapper
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
        $statement = $this->pdo->query('SELECT * FROM orders ORDER BY id desc');
        if($statement === false) {
            return false;
        }

        return $statement->fetchAll();
    }

    /**
     * Elimina l'ordine di dato id
     *
     * @param string $id
     * @return bool True se l'ordine esisteva ed è stato eliminato, false altrimenti
     * @throws RuntimeException In caso di errore nell'esecuzione della query
     */
    public function deleteOrder($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM orders WHERE id=?");
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