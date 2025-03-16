<?php
session_start(); // Start the session

// Check if the user is logged in (i.e., if the session variable 'username' and 'user_id' exist)
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.html'); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username'];  // Get the username from the session
$userId = $_SESSION['user_id'];    // Get the user ID from the session

// Handle file upload logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['music_file'])) {
    $file = $_FILES['music_file'];

    // Get file details
    $fileName = $_FILES['music_file']['name'];
    $fileTmpName = $_FILES['music_file']['tmp_name'];
    $fileSize = $_FILES['music_file']['size'];
    $fileError = $_FILES['music_file']['error'];
    $fileType = $_FILES['music_file']['type'];

    // Check the file extension
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Define allowed file types (e.g., mp3, wav)
    $allowedTypes = ['mp3', 'wav'];

    // Check for errors and file type validation
    if ($fileError === 0) {
        if (in_array($fileExt, $allowedTypes)) {
            // Create a unique name for the file (e.g., using user ID and a unique ID to prevent overwriting)
            $fileNameNew = 'user_' . $userId . '_' . uniqid('', true) . '.' . $fileExt;

            // Define the upload directory
            $uploadDir = 'uploads/' . $username . '/';

            // Check if the user-specific directory exists, if not, create it
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create directory with permissions
            }

            $fileDestination = $uploadDir . $fileNameNew;

            // Move the uploaded file to the designated directory
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                // Successfully uploaded, now save the music info to the database (optional)
                // Here, we can save the file path and other details to the database for later retrieval
                $conn = new mysqli("localhost", "root", "", "music_platform"); // Adjust database credentials

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare the SQL query to insert the music info
                $stmt = $conn->prepare("INSERT INTO music (user_id, file_name, file_path) VALUES (?, ?, ?)");

                // Check if the preparation failed and show the error
                if ($stmt === false) {
                    die("Error preparing the query: " . $conn->error);
                }

                // Bind the parameters
                $stmt->bind_param("iss", $userId, $fileNameNew, $fileDestination);

                // Execute the query and check if it was successful
                if ($stmt->execute()) {
                    echo "<p class='success'>Music uploaded successfully!</p>";
                } else {
                    echo "<p class='error'>Failed to save music information to the database. Error: " . $stmt->error . "</p>";
                }

                // Close the statement and connection
                $stmt->close();
                $conn->close();
            } else {
                echo "<p class='error'>Failed to upload the music file. Please try again.</p>";
            }
        } else {
            echo "<p class='error'>Invalid file type. Only MP3 and WAV are allowed.</p>";
        }
    } else {
        echo "<p class='error'>There was an error uploading the file. Error code: " . $fileError . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Music</title>
    <link rel="stylesheet" href="style1.css"> <!-- Link to your custom CSS -->
</head>
<body>
    <div class="container">
        <h1>Upload Your Music</h1>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="music_file">Choose a music file (MP3 or WAV):</label>
            <input type="file" name="music_file" id="music_file" accept=".mp3,.wav" required>
            <button type="submit">Upload Music</button>
        </form>

        <p class="text-link"><a href="home1.php">Go back to Home</a></p>
    </div>
</body>
</html>
