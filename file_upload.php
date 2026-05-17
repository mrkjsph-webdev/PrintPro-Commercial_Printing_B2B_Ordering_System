<?php
ob_start();
session_start();
require "db.php";

header('Content-Type: application/json');
ob_clean();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$frontFile = isset($_FILES['frontImage']) ? $_FILES['frontImage'] : null;
$backFile = isset($_FILES['backImage']) ? $_FILES['backImage'] : null;

if (!$frontFile || $frontFile['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["status" => "error", "message" => "Front image is required"]);
    exit;
}

$allowedTypes = ['image/png', 'image/jpeg'];
$uploadDir = __DIR__ . "/uploaded_files/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$frontImagePath = null;
$backImagePath = "";

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $frontFile['tmp_name']);
finfo_close($finfo);

if (!in_array($mime, $allowedTypes)) {
    echo json_encode(["status" => "error", "message" => "Front image: Only PNG and JPG allowed"]);
    exit;
}

$extension = ($mime === "image/png") ? ".png" : ".jpg";
$uniqueName = uniqid("img_front_", true) . $extension;
$targetPath = $uploadDir . $uniqueName;

if (!move_uploaded_file($frontFile['tmp_name'], $targetPath)) {
    echo json_encode(["status" => "error", "message" => "Front image upload failed"]);
    exit;
}

$frontImagePath = "uploaded_files/" . $uniqueName;

if ($backFile && $backFile['error'] === UPLOAD_ERR_OK) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $backFile['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowedTypes)) {
        echo json_encode(["status" => "error", "message" => "Back image: Only PNG and JPG allowed"]);
        exit;
    }

    $extension = ($mime === "image/png") ? ".png" : ".jpg";
    $uniqueName = uniqid("img_back_", true) . $extension;
    $targetPath = $uploadDir . $uniqueName;

    if (!move_uploaded_file($backFile['tmp_name'], $targetPath)) {
        echo json_encode(["status" => "error", "message" => "Back image upload failed"]);
        exit;
    }

    $backImagePath = "uploaded_files/" . $uniqueName;
}

$stmt = $conn->prepare("INSERT INTO file_upload (user_id, image1, image2, upload_date) VALUES (?, ?, ?, NOW())");

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "DB prepare failed"]);
    exit;
}

$stmt->bind_param("iss", $user_id, $frontImagePath, $backImagePath);

if (!$stmt->execute()) {
    echo json_encode(["status" => "error", "message" => "DB execute failed"]);
    $stmt->close();
    $conn->close();
    exit;
}

$file_id = $stmt->insert_id;
$stmt->close();
$conn->close();

echo json_encode([
    "status" => "success",
    "file_id" => $file_id,
    "frontImage" => $frontImagePath,
    "backImage" => $backImagePath
]);
?>