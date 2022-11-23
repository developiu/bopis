<?php

namespace XPort\Mvc\Controller;

use Exception;
use XPort\Mapper\ProductMapper;
use XPort\Mvc\AbstractController;
use XPort\StringUtils;

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

    /**
     * Chiamata ajax: prende un parametro 'ean' e ritorna status:
     *   - error se i parametri sono errati o se il prodotto non esiste; in tal caso 'message' conterrà un messaggio esplicativo
     *   - success se il prodotto esiste; in questo caso la chiave product conterrà i dettagli del prodotto
     */
    public function getByEan()
    {
        header('Content-Type: application/json');
        $ean = $_GET['ean'] ?? "";
        if($ean === "") {
            echo json_encode(['status' => 'error', 'message' => 'parametro ean mancante']);
            exit;
        }

        $mapper = new ProductMapper();
        $product = $mapper->getByEan($ean);
        if($product === null) {
            echo json_encode(['status' => 'error', 'message' => 'prodotto non registrato']);
            exit;
        }

        echo json_encode(['status' => 'success', 'product' => $product]);
        exit;
    }

    /**
     * Chiamata ajax: prende un parametro un array di ean e riduce la quantità dei rispettivi prodotti di uno.
     *
     * Ritorna un json con un parametro status e con un corrispondente messaggio esplicativo. Il parametro
     * 'ean' può essere un ean singolo o un array di ean (nel qual caso tutti i prodotti corrispondenti avranno la quantità
     * corrispondente ridotta di 1). Il json ritornato ha la seguente struttura:
     * [
     *    'status' => success|error|warning (success se è andato tutto bene, error in caso di errore e warning se alcuni dei
     *    prodotti hanno raggiunto una quantità minore o uguale di zero
     * ]
     */
    public function reduceQuantityByEan()
    {
        header('Content-Type: application/json');
        if([$_POST['ean']] === null) {
            echo json_encode(['status' => 'error', 'message' => 'EAN non definiti']);
            exit;
        }

        $eans = $_POST['ean'];
        if(is_string($eans)) {
            $eans = [ $eans ];
        }

        $problematicEans = [];
        $mapper = new ProductMapper();
        foreach($eans as $ean) {
            $newQuantity = $mapper->decreaseQuantity($ean);
            if($newQuantity <= 0) {
                $problematicEans[$ean] = true;
            }
        }

        if($problematicEans) {
            echo json_encode(['status' => 'warning', 'message' => 'I seguenti prodotti risultano esauriti: ' . implode(", ", array_keys($problematicEans))]);
            exit;
        }

        echo json_encode(['status' => 'success', 'message' => 'Quantità aggiornate']);
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

    public function deleteProduct()
    {
        if(!isset($_GET['id'])) {
            http_response_code(403);
            echo $this->getRenderer()->render('error-pages/general_error',['error_code' => 403,'message' => "Accesso vietato"]);
            exit;
        }
        $id = intval($_GET['id']);
        $mapper = new ProductMapper();
        $mapper->deleteProduct($id);
        header("location: /products");
    }

    public function importFromCsv()
    {
        header('Content-Type: application/json');
        if(!isset($_FILES['csv'])) {
            echo json_encode([ 'status' => 'error', 'message' => 'file csv mancante']);
            exit;
        }

        if(!is_readable($_FILES['csv']['tmp_name'])) {
            echo json_encode([ 'status' => 'error', 'message' => 'errore nel caricamento del file csv']);
            exit;
        }
        $csvContents = file_get_contents($_FILES['csv']['tmp_name']);
        if(empty($csvContents)) {
            echo json_encode([ 'status' => 'error', 'message' => 'file csv vuoto']);
            exit;
        }

        $csvData = StringUtils::convertCsvToArray($csvContents);
        if(count($csvData[0])!=5) {
            echo json_encode([ 'status' => 'error', 'message' => 'Il file csv ha un formato scorretto']);
            exit;
        }

        // troviamo prima l'indice delle colonne, poi scartiamo la riga dei nomi delle colonne che non serve più
        $indexes = [];
        foreach($csvData[0] as $key) {
            $indexes[$key]= array_search($key, $csvData[0]);
        }
        array_shift($csvData);

        $messages = $this->validateCsv($csvData, $indexes);
        if($messages) {
            $txtMsg = "";
            foreach($messages as $key=>$errors) {
                $txtMsg .= "$key:<br>";
                $txtMsg .= implode("<br>",$errors);
                $txtMsg .= "<br><br>";
            }
            echo json_encode([ 'status' => 'error', 'message' => "Il file non ha il formato corretto:<br>$txtMsg"]);
            exit;
        }

        // verifichiamo se uno degli EAN è presente nel sistema
        $mapper = new ProductMapper();
        $productsWithConflictingEan = $mapper->getProductsByField('ean', array_column($csvData, $indexes['ean']));
        if(!empty($productsWithConflictingEan)) {
            echo json_encode([ 'status' => 'error', 'message' => 'I seguenti EAN risultano già presenti nel sistema: ' .
                implode(',', array_column($productsWithConflictingEan,'ean'))]);
            exit;
        }

        $mapper->getAdapter()->beginTransaction();
        try {
            foreach($csvData as $productData) {
                $product = [];
                foreach($indexes as $key=>$index) {
                    $product[$key] = $productData[$index];
                }
                $mapper->createProduct($product);
            }
            $mapper->getAdapter()->commit();
        }
        catch(Exception $e) {
            $mapper->getAdapter()->rollBack();
            echo json_encode([ 'status' => 'error', 'message' => 'Errore di database']);
            exit;
        }

        echo json_encode(['status' => 'success', 'message' => 'prodotti importati nel sistema' ]);
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

    /**
     * @param array $csvData
     * @param array $indexes
     * @return array un array di messaggi con tutti gli errori rilevati, raggruppati per linea
     */
    private function validateCsv($csvData, $indexes)
    {
        $messages = [];
        foreach($csvData as $index=>$line) {
            $errors = [];
            if(count($line)!=5) {
                return false;
            }
            $ean = $line[$indexes['ean']];
            $asin = $line[$indexes['asin']];
            $quantity = $line[$indexes['quantity']];

            if(!preg_match("/[0-9]{13}/", $ean)) {
                $errors[] = "'$ean' non è un ean13 valido";
            }

            if(!preg_match('/^(B[\dA-Z]{9}|\d{9}(X|\d))$/', $asin)) {
                $errors[] = "'$asin' non è un identificativo amazon valido";
            }

            if(!ctype_digit($quantity)) {
                $errors[] = "'$quantity' non è un intero maggiore o uguale di zero";
            }

            if($errors) {
                $messages["Riga " . ($index+1)]=$errors;
            }
        }

        return $messages;

        return true;
    }


}