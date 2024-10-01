<?php
header("Content-Type: application/json");

// Database connection
include 'connect.php';  // Include your database connection file

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check if required data is provided
if (isset($data['username']) && isset($data['code'])) {
    $username = $data['username'];
    $code = $data['code'];

    // Verify the code
    $result = mysqli_query($conn, "SELECT * FROM `admin` WHERE `username`='$username' AND `stase`='$code'");
    if (mysqli_num_rows($result) == 1) {
        // Return JSON success response
        echo json_encode([
            'status' => 'success',
            'message' => 'Verification Code Success'
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid verification code']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Code are required']);
}
?>
