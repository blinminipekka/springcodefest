<?php
session_start(); // Start the session to retrieve session data

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Get the username from the session
} else {
    // If the session doesn't contain 'username', redirect to the login page
    header('Location: login.html');
    exit();
}

// Define the user's music folder
$userMusicFolder = 'uploads/' . $username;

// Ensure the folder exists
if (!file_exists($userMusicFolder)) {
    mkdir($userMusicFolder, 0777, true); // In case the folder doesn't exist
}

// Get the music files in the user's folder
$musicFiles = scandir($userMusicFolder);
$musicFiles = array_diff($musicFiles, array('.', '..')); // Remove . and .. from the directory listing
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Welcome <?php echo $username; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            padding-bottom: 100px; /* Space for the music player at the bottom */
        }

        .music-boxes {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .box {
            padding: 10px;
            background: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .music-player-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            z-index: 100; /* Keep it above other content */
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.2);
        }

        .music-player-container button {
            margin: 5px;
            padding: 8px 15px;
            cursor: pointer;
            background-color: #444;
            border: none;
            color: white;
        }

        .music-player-container button:hover {
            background-color: #555;
        }

        /* Prevent the player from blocking content by giving it a height */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <main>
        <div class="container">
            <h1>Welcome, <?php echo $username; ?>!</h1>
            <p>Here are your recent tracks and music uploads:</p>

            <!-- Music Boxes to Display User's Music -->
            <div class="music-boxes">
                <?php
                // Check if the user has uploaded any music
                if (empty($musicFiles)) {
                    echo "<p>No music uploaded yet.</p>";
                } else {
                    // Loop through the files and display them
                    foreach ($musicFiles as $file) {
                        // Check if the file is a valid music file (MP3 or WAV)
                        $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        if (in_array($fileExt, ['mp3', 'wav'])) {
                            echo "<div class='box'>
                                    <h3>" . basename($file) . "</h3>
                                    <audio class='music-track' data-file='{$file}' controls>
                                        <source src='{$userMusicFolder}/{$file}' type='audio/{$fileExt}' />
                                        Your browser does not support the audio element.
                                    </audio>
                                  </div>";
                        }
                    }
                }
                ?>
            </div>

            <!-- Buttons -->
            <button onclick="window.location.href='upload.php'">Upload New Music</button>

            <!-- Logout Button -->
            <form action="logout.php" method="POST">
                <button type="submit">Logout</button>
            </form>
        </div>
    </main>

    <!-- Music Player at the Bottom -->
    <div class="music-player-container" id="musicPlayerContainer">
        <div>
            <span id="currentSong">No song playing</span>
        </div>
        <audio id="audioPlayer" controls>
            <source id="audioSource" src="" type="audio/mp3">
            Your browser does not support the audio element.
        </audio>
        <br>
        <button id="shuffleButton">Shuffle</button>
        <button id="prevButton">Prev</button>
        <button id="pauseButton">Pause</button>
        <button id="nextButton">Next</button>
    </div>

    <script>
        // Music player logic
        const musicFiles = <?php echo json_encode(array_values(array_filter($musicFiles, function($file) { return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['mp3', 'wav']); }))); ?>;
        const audioPlayer = document.getElementById("audioPlayer");
        const audioSource = document.getElementById("audioSource");
        const currentSongElement = document.getElementById("currentSong");
        const musicPlayerContainer = document.getElementById("musicPlayerContainer");

        let currentSongIndex = 0;
        let isShuffling = false;

        // Show the player when a song is playing
        function showPlayer() {
            musicPlayerContainer.style.display = 'block';
        }

        // Update the audio player with the current song
        function loadSong(songIndex) {
            currentSongIndex = songIndex;
            const song = musicFiles[songIndex];
            audioSource.src = `uploads/<?php echo $username; ?>/${song}`;
            currentSongElement.innerText = `Now Playing: ${song}`;
            audioPlayer.load();
            audioPlayer.play();
            showPlayer();
        }

        // Shuffle the playlist
        function shuffleSongs() {
            isShuffling = !isShuffling;
            if (isShuffling) {
                musicFiles.sort(() => Math.random() - 0.5); // Shuffle array
                document.getElementById("shuffleButton").innerText = "Shuffle On";
            } else {
                musicFiles.sort(); // Reset to original order
                document.getElementById("shuffleButton").innerText = "Shuffle";
            }
        }

        // Play the next song in the playlist
        function nextSong() {
            if (isShuffling) {
                loadSong(Math.floor(Math.random() * musicFiles.length));
            } else {
                currentSongIndex = (currentSongIndex + 1) % musicFiles.length;
                loadSong(currentSongIndex);
            }
        }

        // Play the previous song in the playlist
        function prevSong() {
            if (isShuffling) {
                loadSong(Math.floor(Math.random() * musicFiles.length));
            } else {
                currentSongIndex = (currentSongIndex - 1 + musicFiles.length) % musicFiles.length;
                loadSong(currentSongIndex);
            }
        }

        // Pause the song
        function pauseSong() {
            audioPlayer.pause();
        }

        // Event Listeners
        audioPlayer.addEventListener("ended", nextSong); // Play next song when current song ends

        document.getElementById("shuffleButton").addEventListener("click", shuffleSongs);
        document.getElementById("nextButton").addEventListener("click", nextSong);
        document.getElementById("prevButton").addEventListener("click", prevSong);
        document.getElementById("pauseButton").addEventListener("click", pauseSong);

        // Load the first song on page load
        if (musicFiles.length > 0) {
            loadSong(0);
        }
    </script>
</body>
</html>
