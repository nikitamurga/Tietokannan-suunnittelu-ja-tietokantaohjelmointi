<?php
require "dbconnection.php";
$dbcon = createDbConnection();

$host = 'localhost';
$dbname = 'your_database_name';
$user = 'your_database_username';
$password = 'your_database_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $artist_name = $_POST['artist_name'];
    $album_name = $_POST['album_name'];
    $track_name = $_POST['track_name'];
    $composer = $_POST['composer'];
    
    try {
        $pdo->beginTransaction();
        
        
        $stmt = $pdo->prepare('INSERT INTO artists (name) VALUES (?)');
        $stmt->execute([$artist_name]);
        $artist_id = $pdo->lastInsertId();
        
        
        $stmt = $pdo->prepare('INSERT INTO albums (title, artist_id) VALUES (?, ?)');
        $stmt->execute([$album_name, $artist_id]);
        $album_id = $pdo->lastInsertId();
        
        
        $stmt = $pdo->prepare('INSERT INTO tracks (name, album_id, composer) VALUES (?, ?, ?)');
        $stmt->execute([$track_name, $album_id, $composer]);
        
        $pdo->commit();
        echo "New artist, album, and track added successfully!";
    } catch(PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method!";
}

?>