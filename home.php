<?php
session_start(); // Start the session to retrieve session data

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Get the username from the session
    $userId = $_SESSION['user_id'];   // Get the user ID from session
} else {
    // If the session doesn't contain 'username', redirect to the login page
    header('Location: login.html');
    exit();
}

// Retrieve music files from the database
$conn = new mysqli("localhost", "root", "", "music_platform"); // Change these to your DB credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the music files of the logged-in user
$stmt = $conn->prepare("SELECT file_name, file_path FROM music_files WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$musicFiles = [];
while ($row = $result->fetch_assoc()) {
    $musicFiles[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Welcome <?php echo $username; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $username; ?>!</h1>
        <p>Here are your recent tracks and music uploads:</p>

        <!-- Display user's music -->
        <div class="music-boxes">
            <?php
            // Check if the user has uploaded any music
            if (empty($musicFiles)) {
                echo "<p>No music uploaded yet.</p>";
            } else {
                // Loop through the music files and display them
                foreach ($musicFiles as $musicFile) {
                    echo "<div class='box'>
                            <h3>" . basename($musicFile['file_name']) . "</h3>
                            <audio class='music-track' controls>
                                <source src='" . $musicFile['file_path'] . "' type='audio/mp3'>
                                Your browser does not support the audio element.
                            </audio>
                          </div>";
                }
            }
            ?>
        </div>

        <button onclick="window.location.href='upload.php'">Upload New Music</button>
        <form action="logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>