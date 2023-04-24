<?php
require_once('pdo_connect.php');


if(isset($_POST['invoice_id'])){
    $invoice_id = $_POST['invoice_id'];

    
    $stmt = $pdo->prepare("DELETE FROM invoice_item WHERE invoice_id = ?");
    $stmt->execute([$invoice_id]);

    
    echo "Invoice item with invoice_id " . $invoice_id . " has been removed.";
} else {
    echo "Please provide an invoice_id to remove the invoice item.";
}
?>
