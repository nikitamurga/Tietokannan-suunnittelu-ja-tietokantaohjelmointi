<?php
require "dbconnection.php";
$dbcon = createDbConnection();

if(isset($_POST["artist_id"])) {
    $artist_id = $_POST["artist_id"];

    try {
        $pdo->beginTransaction();

        $statement = $pdo->prepare("DELETE FROM invoice_items WHERE track_id IN (SELECT track_id FROM tracks WHERE album_id IN (SELECT album_id FROM albums WHERE artist_id = ?))");
        $statement->execute([$artist_id]);

        $statement = $pdo->prepare("DELETE FROM tracks WHERE album_id IN (SELECT album_id FROM albums WHERE artist_id = ?)");
        $statement->execute([$artist_id]);

        $statement = $pdo->prepare("DELETE FROM albums WHERE artist_id = ?");
        $statement->execute([$artist_id]);

        $statement = $pdo->prepare("DELETE FROM artists WHERE artist_id = ?");
        $statement->execute([$artist_id]);

        $pdo->commit();
        echo "Artist and associated data deleted successfully!";
    } catch(PDOException $e) {
        $pdo->rollback();
        echo "Error deleting artist: " . $e->getMessage();
    }
} else {
    echo "Artist ID not provided";
}

if(isset($_POST['invoice_id'])){
    $invoice_id = $_POST['invoice_id'];

    $sql = "DELETE FROM invoice_item WHERE invoice_id = ?";
    $statement = $dbcon->prepare($sql);
    $statement->execute([$invoice_id]);

    echo "Invoice item with invoice_id " . $invoice_id . " has been removed.";
} else {
    echo "Please provide an invoice_id to remove the invoice item.";
}
?>
