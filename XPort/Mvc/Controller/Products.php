<?php

namespace XPort\Mvc\Controller;

use XPort\Mapper\ProductMapper;
use XPort\Mvc\AbstractController;

class Products extends AbstractController
{
    public function index()
    {
        $mapper = new ProductMapper();
        $prodotti = $mapper->fetchAll();

        echo $this->getRenderer()->render('products/index',['prodotti' => $prodotti]);
    }

    public function updateQuantity()
    {
        $productId = $_GET['pid'] ?? null;
        if($productId === null) {
            echo json_encode(['status' => false]);
            exit;
        }

        $newQuantity = $_GET['qty'] ?? 1;
        $mapper = new ProductMapper();

        $result = $mapper->update($productId, 'quantity', $newQuantity);
        if($result === false) {
            echo json_encode(['status' => false]);
            exit;
        }

        echo json_encode(['status' => true, 'updated' => $result]);
        exit;
    }
    
}