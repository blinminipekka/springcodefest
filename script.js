document.addEventListener('DOMContentLoaded', () => {
    const musicGrid = document.querySelector('.music-grid');

    // Function to create a music box
    function createMusicBox(trackName, audioPath) {
        const musicBox = document.createElement('div');
        musicBox.classList.add('music-box');

        const audioElement = document.createElement('audio');
        audioElement.controls = true;

        const sourceElement = document.createElement('source');
        sourceElement.src = audioPath;
        sourceElement.type = 'audio/mpeg';

        audioElement.appendChild(sourceElement);
        musicBox.appendChild(audioElement);

        const trackNameElement = document.createElement('p');
        trackNameElement.textContent = trackName;
        musicBox.appendChild(trackNameElement);

        // Check if audio file exists
        fetch(audioPath, { method: 'HEAD' })
            .then(response => {
                if (!response.ok) {
                    audioElement.innerHTML = 'No Sound Found';
                }
            })
            .catch(() => {
                audioElement.innerHTML = 'No Sound Found';
            });

        musicGrid.appendChild(musicBox);
    }

    // Example: Add a new track
    createMusicBox('Track Name', 'path/to/your/audio/file.mp3');

    // Add event listener to the 'Add Track' button
    document.querySelector('.add-track-btn').addEventListener('click', () => {
        // Logic to add a new track (e.g., open a file picker)
    });
});
