<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$userId = $_SESSION['user_id'];

if (isset($_POST['music_id'])) {
    $musicId = $_POST['music_id'];

    $conn = new mysqli("localhost", "root", "", "music_platform");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get file path for deletion
    $stmt = $conn->prepare("SELECT file_path FROM music_files WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $musicId, $userId);
    $stmt->execute();
    $stmt->bind_result($filePath);
    $stmt->fetch();
    $stmt->close();

    if ($filePath && unlink($filePath)) {
        // Delete the file from the database
        $stmt = $conn->prepare("DELETE FROM music_files WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $musicId, $userId);
        $stmt->execute();
        $stmt->close();

        echo "Music file deleted successfully!";
    } else {
        echo "Error deleting music file.";
    }

    $conn->close();
} else {
    echo "No music ID provided.";
}
