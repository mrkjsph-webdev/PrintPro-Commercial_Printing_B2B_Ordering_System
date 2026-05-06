<?php
session_start();
require "db.php";
ob_clean();
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

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

/* upload folder */
$uploadDir = __DIR__ . "/uploaded_files/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$extension = ($mime == "image/png") ? ".png" : ".jpg";
$uniqueName = uniqid("design_", true) . $extension;

/* physical path (server) */
$targetPath = $uploadDir . $uniqueName;

/* move file */
if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode(["status" => "error", "message" => "Failed to move uploaded file"]);
    exit;
}

/* IMPORTANT: what we store in DB */
$dbFilePath = "uploaded_files/" . $uniqueName; // relative path for browser use

$fileName = $file['file_name'];
$fileSize = $file['file_size'];

/* insert into database */
$stmt = $conn->prepare("
    INSERT INTO file_uploads
    (user_id, file_name, file_path, file_type, file_size, image, upload_date)
    VALUES (?, ?, ?, ?, ?, ?, NOW())
");

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param(
    "isssis",
    $user_id,
    $fileName,
    $dbFilePath,
    $mime,
    $fileSize,
    $dbFilePath
);

if (!$stmt->execute()) {
    echo json_encode([
        "status" => "error",
        "message" => "Execute failed: " . $stmt->error
    ]);
    exit;
}

echo json_encode([
    "status" => "success",
    "file_id" => $stmt->insert_id,
    "file_path" => $dbFilePath
]);

$stmt->close();
?>