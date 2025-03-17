<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username'];
$userId = $_SESSION['user_id']; // Get the user ID from the session

// Handle the file upload process
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['music_file'])) {
    $file = $_FILES['music_file'];
    $targetDir = "uploads/";
    $filePath = $targetDir . basename($file["name"]);
    
    // Validate file type (optional)
    $allowedTypes = ['audio/mp3', 'audio/mpeg'];
    if (!in_array($file['type'], $allowedTypes)) {
        echo "Invalid file type. Please upload an MP3 file.";
        exit();
    }

    // Move the uploaded file to the uploads folder
    if (move_uploaded_file($file["tmp_name"], $filePath)) {
        // Save the file details to the database
        $songTitle = $_POST['song_title'];

        $conn = new mysqli("localhost", "root", "", "music_platform");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert file details into the database
        $stmt = $conn->prepare("INSERT INTO music_files (user_id, song_title, file_name, file_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $songTitle, $file['name'], $filePath);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        echo "File uploaded successfully!";
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Music</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Upload Music</h1>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="song_title">Song Title:</label>
            <input type="text" name="song_title" required>

            <label for="music_file">Choose Music File:</label>
            <input type="file" name="music_file" accept="audio/mp3, audio/mpeg" required>

            <button type="submit">Upload</button>
        </form>
        <br>
        <a href="home1.php">Go back to Home</a>
    </div>
</body>
</html>
