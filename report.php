<?php
include 'db.php';
error_reporting(E_ERROR | E_PARSE);

$userID      = $_POST['userID'] ?? '';
$category    = $_POST['category'] ?? '';
$floor       = $_POST['floor'] ?? '';
$spot        = $_POST['spot'] ?? '';
$description = $_POST['description'] ?? '';
$imageString = $_POST['image_string'] ?? '';

if ($userID === '' || empty($category) || empty($floor) || empty($spot)) {
    echo "Required fields are missing. Received ID: " . $userID;
    exit;
}

// DEFAULT IMAGE
$imageName = "no_image.jpg";

if (!empty($imageString)) {
    $imageName = "IMG_" . time() . "_" . $userID . ".jpg";
    $path = "uploads/" . $imageName;
    
    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    // REMOVE BASE64 PREFIX IF EXISTS
    if (strpos($imageString, ',') !== false) {
        $imageString = explode(',', $imageString)[1];
    }

    $imageData = base64_decode($imageString);

    if ($imageData === false) {
        echo "Invalid image data";
        exit;
    }

    

    if (!file_put_contents($path, $imageData)) {
        echo "Image upload failed";
        exit;
    }
}


// INSERT REPORT
$sql = "INSERT INTO reports 
(userID, category, floor, spot, description, image_name, status)
VALUES 
('$userID', '$category', '$floor', '$spot', '$description', '$imageName', 'Pending')";

if ($conn->query($sql) === TRUE) {
    echo $conn->insert_id;
} else {
    echo "Error";
}

$conn->close();
?>