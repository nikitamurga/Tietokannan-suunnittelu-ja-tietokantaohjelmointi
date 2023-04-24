<?php
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $artist_name = $_POST['artist_name'];
    $album_name = $_POST['album_name'];
    $track_name = $_POST['track_name'];
    $composer = $_POST['composer'];
    
    try {
        $conn->beginTransaction();
        
        // Добавляем нового артиста
        $stmt = $conn->prepare('INSERT INTO artists (name) VALUES (?)');
        $stmt->execute([$artist_name]);
        $artist_id = $conn->lastInsertId();
        
        // Добавляем новый альбом для артиста
        $stmt = $conn->prepare('INSERT INTO albums (title, artist_id) VALUES (?, ?)');
        $stmt->execute([$album_name, $artist_id]);
        $album_id = $conn->lastInsertId();
        
        // Добавляем новую песню в альбом
        $stmt = $conn->prepare('INSERT INTO tracks (name, album_id, composer) VALUES (?, ?, ?)');
        $stmt->execute([$track_name, $album_id, $composer]);
        
        $conn->commit();
        echo "New artist, album, and track added successfully!";
    } catch(PDOException $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method!";
}
