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

    public function updateProductModal()
    {
        header('Content-Type: application/json');
        if($_POST) {
            $this->saveProduct();
            header('location: /products');
            exit;
        }

        $ean = $_GET['ean'] ?? "";
        if($ean === "") {
            echo json_encode(['status' => 'error', 'message' => 'Inserisci un ean']);
            exit;
        }

        $mapper = new ProductMapper();
        $product = $mapper->getByEan($ean);
        if($product === null) {
            $viewParameters =  ['product' => [ 'ean' => $ean ],'submit_label' => 'Crea prodotto'];
        }
        else {
            $viewParameters = ['product' => $product,'submit_label' => 'Modifica prodotto'];
        }
        $formHtml = $this->getRenderer()->render('products/update_product_modal', $viewParameters );
        echo json_encode(['title' => $viewParameters['submit_label'], 'form' => $formHtml]);
        exit;
    }

    private function saveProduct()
    {
        $id = $_POST['id'] ?? null;

        $product = [
            'name' => $_POST['name'],
            'quantity' => $_POST['quantity'],
            'sku' => $_POST['sku'],
            'asin' => $_POST['asin'],
            'ean' => $_POST['ean']
        ];
        $mapper = new ProductMapper();
        if($id===null) {
            $mapper->createProduct($product);
        }
        else {
            $mapper->updateProduct($product, $id);
        }

    }


}