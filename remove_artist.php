<?php
require_once("pdo_connection.php");

if(isset($_POST["artist_id"])) {
    $artist_id = $_POST["artist_id"];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("DELETE FROM invoice_items WHERE track_id IN (SELECT track_id FROM tracks WHERE album_id IN (SELECT album_id FROM albums WHERE artist_id = :artist_id))");
        $stmt->bindParam(":artist_id", $artist_id);
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM tracks WHERE album_id IN (SELECT album_id FROM albums WHERE artist_id = :artist_id)");
        $stmt->bindParam(":artist_id", $artist_id);
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM albums WHERE artist_id = :artist_id");
        $stmt->bindParam(":artist_id", $artist_id);
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM artists WHERE artist_id = :artist_id");
        $stmt->bindParam(":artist_id", $artist_id);
        $stmt->execute();

        $pdo->commit();
        echo "Artist and associated data deleted successfully!";
    } catch(PDOException $e) {
        $pdo->rollback();
        echo "Error deleting artist: " . $e->getMessage();
    }
} else {
    echo "Artist ID not provided";
}
?>
