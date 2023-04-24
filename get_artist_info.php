<?php
require_once("pdo_connection.php");

if(isset($_POST["artist_id"])) {
    $artist_id = $_POST["artist_id"];

    try {
        $stmt = $pdo->prepare("SELECT name FROM artists WHERE artist_id = :artist_id");
        $stmt->bindParam(":artist_id", $artist_id);
        $stmt->execute();
        $artist_name = $stmt->fetch(PDO::FETCH_ASSOC)["name"];

        $stmt = $pdo->prepare("SELECT album_id, title FROM albums WHERE artist_id = :artist_id");
        $stmt->bindParam(":artist_id", $artist_id);
        $stmt->execute();
        $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($albums as &$album) {
            $stmt = $pdo->prepare("SELECT track_id, name FROM tracks WHERE album_id = :album_id");
            $stmt->bindParam(":album_id", $album["album_id"]);
            $stmt->execute();
            $tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $album["tracks"] = $tracks;
        }

        $result = array("artist_name" => $artist_name, "albums" => $albums);
        echo json_encode($result);
    } catch(PDOException $e) {
        echo "Error retrieving artist info: " . $e->getMessage();
    }
} else {
    echo "Artist ID not provided";
}
?>
