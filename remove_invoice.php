<?php
require "dbconnection.php";
$dbcon = createDbConnection();

if(isset($_POST['invoice_id'])){
    $invoice_id = $_POST['invoice_id'];

    $sql = "DELETE FROM invoice_item WHERE invoice_id = ?";
    $statement = $dbcon->prepare($sql);
    $statement->execute(array($invoice_id));
    
    $stmt->execute([$invoice_id]);
    
    echo "Invoice item with invoice_id " . $invoice_id . " has been removed.";
} else {
    echo "Please provide an invoice_id to remove the invoice item.";
}
?>
