<?php
session_start(); // Start the session to store session data

header('Content-Type: application/json'); // Set content type to JSON for the response

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "spfest";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit();
}

// Get the user input from the form
$name = $_POST["username"];
$email = $_POST["email"];
$pass = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password for security

// Check if the username or email already exists
$check_user = "SELECT * FROM users WHERE name='$name' OR email='$email'";
$result = $conn->query($check_user);

if ($result->num_rows > 0) {
    // If a user with the same username or email already exists, return an error
    echo json_encode(["success" => false, "error" => "Username or Email already exists"]);
    exit();
}

$sql = "CREATE TABLE IF NOT EXISTS music_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    filename VARCHAR(255) NOT NULL
)";

// Insert the new user into the database
$sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$pass')";

// Check if the query was successful
if ($conn->query($sql) === TRUE) {
    // Store the username in the session after successful registration
    $_SESSION['username'] = $name;
    
    // Return a success response as JSON
    echo json_encode(["success" => true]);
    exit(); // Make sure to stop the script execution after returning JSON
} else {
    // If there was an error inserting the user, return an error message
    echo json_encode(["success" => false, "error" => "Error registering user"]);
}

// Close the database connection
$conn->close();
?>
