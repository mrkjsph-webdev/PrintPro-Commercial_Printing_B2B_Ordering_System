<?php
session_start();
require "db.php";

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_FILES['file'])) {
    echo json_encode(["status" => "error", "message" => "No file uploaded"]);
    exit;
}

$file = $_FILES['file'];

$allowedTypes = ['image/png', 'image/jpeg'];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mime, $allowedTypes)) {
    echo json_encode(["status" => "error", "message" => "Only PNG and JPG allowed"]);
    exit;
}

$uploadDir = __DIR__ . "/uploaded_files/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$extension = ($mime === "image/png") ? ".png" : ".jpg";
$uniqueName = uniqid("img_", true) . $extension;

$targetPath = $uploadDir . $uniqueName;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode(["status" => "error", "message" => "Upload failed"]);
    exit;
}

$dbImagePath = "uploaded_files/" . $uniqueName;

$stmt = $conn->prepare("
    INSERT INTO file_upload (user_id, image, upload_date)
    VALUES (?, ?, NOW())
");

$stmt->bind_param("is", $user_id, $dbImagePath);

$stmt->execute();

echo json_encode([
    "status" => "success",
    "file_id" => $stmt->insert_id,
    "image" => $dbImagePath
]);

$stmt->close();
$conn->close();
?>
