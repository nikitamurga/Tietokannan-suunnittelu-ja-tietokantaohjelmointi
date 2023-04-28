<?php
require "dbconnection.php";
$dbcon = createDbConnection();

if(isset($_POST['playlist_id'])){
    $playlist_id = $_POST['playlist_id'];

    $sql = "SELECT track.name AS track_name, artist.name AS artist_name, composer.name AS composer_name
            FROM playlist_track
            INNER JOIN track ON playlist_track.track_id = track.track_id
            INNER JOIN album ON track.album_id = album.album_id
            INNER JOIN artist ON album.artist_id = artist.artist_id
            INNER JOIN composer ON track.composer_id = composer.composer_id
            WHERE playlist_track.playlist_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$playlist_id]);

    echo "Tracks in playlist with playlist_id " . $playlist_id . ":<br>";
    while($row = $stmt->fetch()){
        echo $row['track_name'] . " - " . $row['artist_name'] . " - " . $row['composer_name'] . "<br>";
    }
} else {
    echo "Please provide a playlist_id to get the playlist information.";
}
?>
