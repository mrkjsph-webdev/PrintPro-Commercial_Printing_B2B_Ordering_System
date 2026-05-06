<?php
session_start();
require "db.php";

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ---------- LOGIN CHECK ---------- */
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Not logged in"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

/* ---------- FILE CHECK ---------- */
if (!isset($_FILES['file'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No file uploaded"
    ]);
    exit;
}

$file = $_FILES['file'];

/* ---------- VALIDATE IMAGE TYPE ---------- */
$allowedTypes = ['image/png', 'image/jpeg'];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mime, $allowedTypes)) {
    echo json_encode([
        "status" => "error",
        "message" => "Only PNG and JPG allowed"
    ]);
    exit;
}

/* ---------- CREATE FOLDER ---------- */
$uploadDir = __DIR__ . "/uploaded_files/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

/* ---------- GENERATE UNIQUE FILE NAME ---------- */
$extension = ($mime === "image/png") ? ".png" : ".jpg";
$uniqueName = uniqid("img_", true) . $extension;

$targetPath = $uploadDir . $uniqueName;

/* ---------- MOVE FILE ---------- */
if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to move uploaded file"
    ]);
    exit;
}

/* ---------- PATH SAVED TO DB ---------- */
$dbImagePath = "uploaded_files/" . $uniqueName;

/* ---------- INSERT INTO DB ---------- */
$stmt = $conn->prepare("
    INSERT INTO file_upload (user_id, image, upload_date)
    VALUES (?, ?, NOW())
");

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
    exit;
}

$stmt->bind_param("is", $user_id, $dbImagePath);

if (!$stmt->execute()) {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
    exit;
}

/* ---------- SUCCESS RESPONSE ---------- */
echo json_encode([
    "status" => "success",
    "file_id" => $stmt->insert_id,
    "image" => $dbImagePath
]);

$stmt->close();
?>
