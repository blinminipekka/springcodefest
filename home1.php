<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.html'); // Redirect to login if not logged in
    exit();
}

$username = $_SESSION['username'];
$userId = $_SESSION['user_id']; // Get the user ID from the session

// Database connection
$conn = new mysqli("localhost", "root", "", "music_platform");

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Handle the deletion of music files
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['music_id'])) {
    $musicId = $_POST['music_id'];

    // Prepare SQL query to delete the music file
    $stmt = $conn->prepare("DELETE FROM music_files WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $musicId, $userId);
    $stmt->execute();
    $stmt->close();

    // Reload the page after deletion
    echo "<script>window.location.reload();</script>";
    exit(); // Make sure the script stops executing after reload
}

// Retrieve music files from the database
$stmt = $conn->prepare("SELECT id, song_title, file_name, file_path FROM music_files WHERE user_id = ?");
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
    <title>Home - Welcome <?php echo htmlspecialchars($username); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Container Setup */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
            text-align: center;
        }

        /* Music Display */
        .music-boxes {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .box {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            text-align: center;
        }

        /* Buttons */
        .btn {
            padding: 12px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        /* Fixed Music Player */
        .music-player-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            z-index: 999;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        @media (max-width: 768px) {
            .music-player-container {
                flex-direction: column;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>Here are your recent tracks and music uploads:</p>


<div class="music-boxes" id="musicBoxes">
    <?php if (empty($musicFiles)): ?>
        <p>No music uploaded yet.</p>
    <?php else: ?>
        <?php foreach ($musicFiles as $musicFile): ?>
            <div class="box" style="background-color: black;">
                <h3 style="color: white;"><?php echo htmlspecialchars($musicFile['song_title']); ?></h3>
                <audio controls>
                    <source src="<?php echo htmlspecialchars($musicFile['file_path']); ?>" type="audio/mp3">
                    Your browser does not support the audio element.
                </audio>
                <form action="home1.php" method="POST" style="display: inline-block;">
                    <input type="hidden" name="music_id" value="<?php echo $musicFile['id']; ?>">
                    <button type="submit" class="btn delete-btn">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

        <button onclick="window.location.href='upload.php'" class="btn">Upload New Music</button>
        <form action="logout.php" method="POST">
            <button type="submit" class="btn">Logout</button>
        </form>
    </div>

    <!-- Music Player -->
    <div class="music-player-container">
        <span id="currentSong">No song playing</span>
        <audio id="audioPlayer" controls>
            <source id="audioSource" src="" type="audio/mp3">
            Your browser does not support the audio element.
        </audio>
        <button id="shuffleButton">Shuffle</button>
        <button id="prevButton">Prev</button>
        <button id="playPauseButton">Play</button>
        <button id="nextButton">Next</button>
        <button id="repeatButton">Repeat Off</button>
    </div>

    <script>
        const musicFiles = <?php echo json_encode(array_column($musicFiles, 'file_path')); ?>;
        const audioPlayer = document.getElementById("audioPlayer");
        const audioSource = document.getElementById("audioSource");
        const currentSongElement = document.getElementById("currentSong");
        const playPauseButton = document.getElementById("playPauseButton");
        let currentSongIndex = 0;
        let isShuffling = false;
        let isRepeating = false;

        function loadSong(songIndex) {
            currentSongIndex = songIndex;
            if (!musicFiles[songIndex]) return;
            audioSource.src = musicFiles[songIndex];
            currentSongElement.innerText = `Now Playing: ${musicFiles[songIndex]}`;
            audioPlayer.load();
            audioPlayer.play();
            playPauseButton.innerText = "Pause";
        }

        function shuffleSongs() {
            isShuffling = !isShuffling;
            document.getElementById("shuffleButton").innerText = isShuffling ? "Shuffle On" : "Shuffle";
        }

        function toggleRepeat() {
            isRepeating = !isRepeating;
            audioPlayer.loop = isRepeating;
            document.getElementById("repeatButton").innerText = isRepeating ? "Repeat On" : "Repeat Off";
        }

        document.getElementById("nextButton").onclick = () => loadSong((currentSongIndex + 1) % musicFiles.length);
        document.getElementById("prevButton").onclick = () => loadSong((currentSongIndex - 1 + musicFiles.length) % musicFiles.length);
        playPauseButton.onclick = () => {
            if (audioPlayer.paused) {
                audioPlayer.play();
                playPauseButton.innerText = "Pause";
            } else {
                audioPlayer.pause();
                playPauseButton.innerText = "Play";
            }
        };

        loadSong(currentSongIndex); // Initialize player with the first song
    </script>
</body>
</html>
