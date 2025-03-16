<?php
session_start();  // Start the session to store user data

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "spfest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit();
}

$user = $_POST["user"];
$pass = $_POST["password"];

// Use prepared statement to prevent SQL injection
$sql = "SELECT * FROM users WHERE name = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $user);  // bind parameters to prevent SQL injection
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify password
    if (password_verify($pass, $row["password"])) {
        // Set session variables on successful login
        $_SESSION['user_id'] = $row['id'];  // Store user ID in session
        $_SESSION['username'] = $row['name'];  // Store username in session

        // Return success response
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Incorrect password"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "No user found"]);
}

$stmt->close();
$conn->close();
?>
