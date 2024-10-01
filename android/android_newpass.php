<?php
header("Content-Type: application/json");

// Database connection
include 'connect.php';  // Include your database connection file

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check if required data is provided
if (isset($data['username']) && isset($data['code']) && isset($data['new_password'])) {
    $username = $data['username'];
    $code = $data['code'];
    $new_password = md5($data['new_password']);  // Hash the new password

    // Verify the code
    $result = mysqli_query($conn, "SELECT * FROM `admin` WHERE `username`='$username' AND `stase`='$code'");
    if (mysqli_num_rows($result) == 1) {
        // Code matches, update the password
        mysqli_query($conn, "UPDATE `admin` SET `password`='$new_password', `stase`=NULL WHERE `username`='$username'");

        // Return JSON success response
        echo json_encode([
            'status' => 'success',
            'message' => 'Password has been reset successfully'
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid verification code']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'All fields (username, code, new_password) are required']);
}
?>
