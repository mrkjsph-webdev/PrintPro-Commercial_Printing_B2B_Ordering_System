<?php
include 'db.php';
$email            = $_POST['email'] ?? '';
$new_password     = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate inputs
if (empty($email) || empty($new_password) || empty($confirm_password)) {
    echo "Please fill in all fields";
    exit;
}

if ($new_password !== $confirm_password) {
    echo "Passwords do not match";
    exit;
}

// Check if user exists
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    // Hash Password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update Password
    $update = $conn->prepare("UPDATE users SET user_password = ? WHERE email = ?");
    $update->bind_param("ss", $hashed_password, $email);

    if ($update->execute()) {
        header("Location: login.html?reset=success");
        exit;
    } else {
        echo "Update failed";
    }

    $update->close();

} else {
    echo "Email does not match our records";
}

$stmt->close();
$conn->close();
?>