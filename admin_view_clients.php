<?php
session_start();
require "db.php";

$user_id = $_GET['user_id'] ?? 0;

if (!$user_id) {
    die("Invalid User ID");
}

/* GET USER INFO */
$query = "
SELECT
    user_id,
    first_name,
    last_name,
    email,
    contact_number,
    occupation
FROM users
WHERE user_id = ?
";

$stmt = $conn->prepare($query);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

function e($str) {
    return htmlspecialchars($str ?? '');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image_resources/logo.png">
    <title>User Profile - PrintPro</title>

    <link href="bootstrap.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <style>

           body {
            font-family: 'Poppins', sans-serif;
            background: #f4f5f7;
            display: flex;
            overflow-x: hidden;
            scrollbar-gutter: stable;

            overflow-y: scroll;
            scrollbar-width: none;


            body::-webkit-scrollbar {
                display: none;
            }
        }

        .container-box{
            max-width:700px;
            margin:50px auto;
        }

        .card-box{
            background:white;
            border-radius:15px;
            padding:30px;
            box-shadow:0 5px 20px rgba(0,0,0,0.08);
        }

        .avatar{
            width:100px;
            height:100px;
            border-radius:50%;
            background:#eef2ff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:40px;
            margin:auto;
            color:#2c2e7a;
            font-weight:bold;
        }

        .label{
            color:#888;
            font-size:14px;
        }

        .value{
            font-weight:500;
        }

    </style>
</head>

<body>

<div class="container container-box">

    <a href="admin_clients.php" class="btn btn-outline-secondary mb-3">
        ← Back
    </a>

    <div class="card-box">

        <div class="text-center mb-4">

            <div class="avatar">
                <?= strtoupper(substr($user['first_name'], 0, 1)) ?>
            </div>

            <h3 class="mt-3">
                <?= e($user['first_name'] . ' ' . $user['last_name']) ?>
            </h3>

            <p class="text-muted">
                <?= e($user['occupation']) ?>
            </p>

        </div>

        <hr>

        <div class="mb-3">
            <div class="label">User ID</div>
            <div class="value">
                #<?= e($user['user_id']) ?>
            </div>
        </div>

        <div class="mb-3">
            <div class="label">Email Address</div>
            <div class="value">
                <?= e($user['email']) ?>
            </div>
        </div>

        <div class="mb-3">
            <div class="label">Contact Number</div>
            <div class="value">
                <?= e($user['contact_number']) ?>
            </div>
        </div>

        <div class="mb-3">
            <div class="label">Occupation</div>
            <div class="value">
                <?= e($user['occupation']) ?>
            </div>
        </div>

    </div>

</div>

</body>
</html>