<?php

namespace XPort\Mapper;

use DomainException;
use PDO;
use RuntimeException;

class OrderMapper
{
    /** @var PDO */
    private $pdo;

    /** @var string[] */
    private array $statuses = ['NEW', 'CANCELLED', 'READY_FOR_PICKUP', 'PICKED_UP', 'REFOUNDED'];

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

    /**
     * Aggiorna lo status dell'ordine di dato id.
     *
     * @param string|array $id l'id dell'ordine da aggiornare; se è un array aggiorna l'ordine di tutti gli ordini corrispondenti
     * @param string $newStatus il nuovo stato dell'ordine (deve essere uno dei valori in $statuses)
     * @return bool True se l'ordine è stato modificato, False altrimenti
     * @throws RuntimeException In caso di errore nell'esecuzione della query
     * @throws DomainException Se $newStatus non è uno degli stati ammessi
     */
    public function updateOrderStatus($id, $newStatus)
    {
        if(!in_array($newStatus, $this->statuses)) {
            throw new DomainException("'$newStatus' non è uno stato ammesso per un ordine");
        }
        if(is_string($id)) {
            $id = [ $id ];
        }

        $in = implode(", ", array_fill(0, count($id), "?"));
        $statement = $this->pdo->prepare("UPDATE orders set status=? WHERE id IN ($in)" );
        if($statement === false) {
            throw new RuntimeException("Errore di database");
        }
        $statement->execute(array_merge([$newStatus],$id));

        return $statement->rowCount() > 0;
    }

    public function getAllowedStatuses()
    {
        return $this->statuses;
    }

    public function getAdapter(): PDO
    {
        return $this->pdo;
    }
}