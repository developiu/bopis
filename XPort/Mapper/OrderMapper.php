<?php

namespace XPort\Mapper;

use DomainException;
use PDO;
use RuntimeException;

class OrderMapper
{
    /** @var PDO */
    private $pdo;

    /** @var array */
    private static $allowedStatusTransitions = [
        'NEW' => [ 'NEW', 'READY_FOR_PICKUP'],
        'READY_FOR_PICKUP' => ['READY_FOR_PICKUP', 'PICKED_UP','REFUSED'],
        'PICKED_UP' => ['PICKED_UP', 'REFUSED'],
        'REFUSED' => ['REFUSED']
    ];

    /** @var string[] */
    private array $statuses = ['NEW', 'READY_FOR_PICKUP', 'PICKED_UP', 'REFUSED'];

    public function __construct()
    {
         $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME;
         $this->pdo = new PDO( $dsn,DBUSER, DBPASS);
    }

    /**
     * @param array $where Un array del tipo [ field1 => value1, ... ] impostarla ritorna le righe che hanno field1=value1, field2=value2 etc
     * @return array|false
     */
    public function fetchAll(array $where=[])
    {
        $whereSql = implode(" AND ", array_map(function($key) {
            return "$key=?";
        }, array_keys($where)));
        if($whereSql) {
            $whereSql = "WHERE " . $whereSql;
        }

        $query = "SELECT * FROM orders $whereSql ORDER BY id desc";
        $statement = $this->pdo->prepare($query);
        if($statement === false) {
            return false;
        }
        $statement->execute($where ? array_values($where) : null);

        return $statement->fetchAll();
    }

    /**
     * Modifica il campo $field dell'ordine di ordine $orderId a $newValue; se $orderId è un array applica la modifica
     * a tutti gli ordini di id specificati.
     *
     * @param array|string $orderId
     * @param string $field
     * @param mixed $newValue
     * @return bool True on success, false on failure
     */
    public function updateOrders(array|string $orderId, string $field, $newValue)
    {
        if(is_string($orderId))  {
            $orderId = [ $orderId ];
        }

        $idPlaceholders = implode(", ", array_fill(0,count($orderId), "?"));
        $query = "UPDATE orders set $field=? WHERE id IN ($idPlaceholders)";
        $statement = $this->pdo->prepare($query);
        if($statement === false) {
            return false;
        }
        try {
            $parameters = array_merge([$newValue],$orderId);
            $statement->execute($parameters);
        }
        catch(\PDOException $e) {
            return false;
        }

        return true;
    }
    
    /**
     *  Annulla l'ordine di dato id: se l'ordine è compatibile con lo stato CANCELLED, mettilo CANCELLED, ALTRIMENTI
     * mettilo REFOUNDED.
     *
     * @param string $id
     * @throws RuntimeException In caso di errore nell'esecuzione della query
     */
    public function cancelOrder($id)
    {
        $updated = $this->updateOrderStatus([ $id ], 'CANCELLED');
        if(!$updated) {
            $this->updateOrderStatus([ $id ], 'REFOUNDED');
        }
    }

    /**
     * Aggiorna lo status dell'ordine di dato id.
     *
     * @param string|array $id L'id dell'ordine da aggiornare; se è un array aggiorna l'ordine di tutti gli ordini corrispondenti
     *                         Se qualcuno di questi ordini ha uno stato corrente che non è compatibile con il nuovo stato richiesto, quest'ordine
     *                         viene silenziosamente ignorato
     * @param string $newStatus il nuovo stato dell'ordine (deve essere uno dei valori in $statuses)
     * @return bool True se l'ordine è stato modificato, False altrimenti
     * @throws RuntimeException In caso di errore nell'esecuzione della query
     * @throws DomainException Se $newStatus non è uno degli stati ammessi
     *
     */
    public function updateOrderStatus($id, $newStatus)
    {
        if(!in_array($newStatus, $this->statuses)) {
            throw new DomainException("'$newStatus' non è uno stato ammesso per un ordine");
        }
        if(is_string($id)) {
            $id = [ $id ];
        }

        if(empty($id)) {
            return true;
        }

        $statusesFrom = self::OrderStatusFrom($newStatus);
        $frm = implode(", ", array_fill(0,count($statusesFrom), "?"));
        $in = implode(", ", array_fill(0, count($id), "?"));
        $statement = $this->pdo->prepare("UPDATE orders set status=? WHERE id IN ($in) AND status IN ($frm)" );

        if($statement === false) {
            throw new RuntimeException("Errore di database");
        }
        $statement->execute(array_merge([$newStatus],$id,$statusesFrom));

        return $statement->rowCount() > 0;
    }

    /**
     * Ritorna gli ordini, tra quelli di id in $ids, il cui stato corrente rende possibile di passare allo stato
     * $newStatus.
     *
     * @param string $newStatus
     * @param array $ids
     * @return array
     * @throws DomainException Se $newStatus non è uno degli stati ammessi
     * @throws RuntimeException In caso di errore nell'esecuzione della query
     */
    public function ordersCompatibleWithNewStatus($newStatus, $ids)
    {
        if(!in_array($newStatus, $this->statuses)) {
            throw new DomainException("'$newStatus' non è uno stato ammesso per un ordine");
        }

        if(empty($ids)) {
            return $ids;
        }

        $statusesFrom = self::OrderStatusFrom($newStatus);
        $frm = implode(", ", array_fill(0,count($statusesFrom), "?"));
        $in = implode(", ", array_fill(0, count($ids), "?"));

        $statement = $this->pdo->prepare("SELECT * FROM orders WHERE id in ($in) AND status IN ($frm)");
        if($statement === false) {
            throw new RuntimeException("Errore di database");
        }

        $statement->execute(array_merge($ids,$statusesFrom));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllowedStatuses()
    {
        return $this->statuses;
    }

    /**
     * Ritorna un array con tutti gli stati cui può andare un ordine che ha stato corrente $newStatus
     *
     * @param string $newStatus
     * @return string
     * @throws DomainException Se $newStatus non è uno degli stati ammessi
     */
    public static function OrderStatusTo($newStatus)
    {
        if(!isset(self::$allowedStatusTransitions[$newStatus])) {
            throw new DomainException("'$newStatus' non è uno stato ammesso per un ordine");
        }

        return self::$allowedStatusTransitions[$newStatus];
    }

    /**
     * Ritorna un array con tutti gli stati da cui un ordine può andare nello stato $newStatus
     *
     * @param string $newStatus
     * @return string
     * @throws DomainException Se $newStatus non è uno degli stati ammessi
     */
    public static function OrderStatusFrom($newStatus)
    {
        if(!isset(self::$allowedStatusTransitions[$newStatus])) {
            throw new DomainException("'$newStatus' non è uno stato ammesso per un ordine");
        }

        $statusesFrom = [];
        foreach (self::$allowedStatusTransitions as $statusFrom=>$statusesTo) {
            if(in_array($newStatus, $statusesTo)) {
                $statusesFrom[]=$statusFrom;
            }
        }

        return array_unique($statusesFrom);
    }

    public static function getAllowedTransitions()
    {
        return self::$allowedStatusTransitions;
    }

    public function getAdapter(): PDO
    {
        return $this->pdo;
    }
}