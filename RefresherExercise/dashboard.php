<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.html");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="d-flex justify-content-end mt-3 me-3">
        <a href="index.html" class="btn btn-outline-danger btn-sm">
            Log Out
        </a>
    </div>
    <div class="container py-5">
        <div class="row g-4">
            <h1 class="mb-4">
                Welcome to Dashboard,
                <?php echo $_SESSION['user']['name']; ?>!
            </h1>

            <!-- This must be appear only when the first user is logged in. -->
            <?php if ($user['email'] == "markjoseph.lopena741@gmail.com") { ?>

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">My Resume</h5>
                        <a href="profile_a.php" class="btn btn-primary">
                            View Resume
                        </a>
                    </div>
                </div>
            </div>

            <?php } ?>

            <!-- This must be appear only when the second user is logged in. -->
            <?php if ($user['email'] == "johnandrei.caguioa@gmail.com") { ?>

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">My Portfolio</h5>
                        <a href="profile_b.php" class="btn btn-success">
                            View Portfolio
                        </a>
                    </div>
                </div>
            </div>

            <?php } ?>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>